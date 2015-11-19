<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

use Politizr\Constant\UserConstants;
use Politizr\Constant\ReputationConstants;

/**
 * User query
 *
 * @author Lionel Bouzonville
 */
class PUserQuery extends BasePUserQuery
{
    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */
        
    /**
     * Users' suggestion for user.
     *
     * @see app/sql/suggestions.sql
     *
     * @todo:
     *   > + suggestions depuis les users déjà suivis
     *
     * @param  integer     $userId
     * @param  integer     $offset
     * @param  integer     $count
     * @return string
     */
    private function getSuggestionsSql($userId, $offset, $count = 10)
    {
        // Requête SQL
        $sql = "
#  Concordance des tags suivis / tags caractérisant des users
SELECT DISTINCT
    id,
    uuid,
    provider,
    provider_id,
    nickname,
    realname,
    username,
    username_canonical,
    email,
    email_canonical,
    enabled,
    salt,
    password,
    last_login,
    locked,
    expired,
    expires_at,
    confirmation_token,
    password_requested_at,
    credentials_expired,
    credentials_expire_at,
    roles,
    last_activity,
    p_u_status_id,
    file_name,
    back_file_name,
    copyright,
    gender,
    firstname,
    name,
    birthday,
    subtitle,
    biography,
    website,
    twitter,
    facebook,
    phone,
    newsletter,
    last_connect,
    nb_connected_days,
    nb_views,
    qualified,
    validated,
    online,
    banned,
    banned_nb_days_left,
    banned_nb_total,
    abuse_level,
    created_at,
    updated_at,
    slug
FROM (
( SELECT p_user.*, COUNT(p_user.id) as nb_users, 1 as unionsorting
FROM p_user
    LEFT JOIN p_u_tagged_t
        ON p_user.id = p_u_tagged_t.p_user_id
WHERE
    p_u_tagged_t.p_tag_id IN (
                SELECT p_tag.id
                FROM p_tag
                    LEFT JOIN p_u_follow_t
                        ON p_tag.id = p_u_follow_t.p_tag_id
                WHERE
                    p_tag.online = true
                    AND p_u_follow_t.p_user_id = ".$userId."
    )
    AND p_user.online = 1
    AND p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_follower_id = ".$userId.")
    AND p_user.id <> ".$userId." 
)

UNION DISTINCT

#  Users les plus populaires
( SELECT p_user.*, COUNT(p_u_follow_u.p_user_id) as nb_users, 2 as unionsorting
FROM p_user
    LEFT JOIN p_u_follow_u
        ON p_user.id = p_u_follow_u.p_user_id
WHERE
    p_user.online = 1
    AND p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_follower_id = ".$userId.")
    AND p_user.id <> ".$userId."
GROUP BY p_user.id
ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC
) unionsorting

LIMIT ".$offset.", ".$count."
        ";

        return $sql;
    }

    /**
     * PUsers objects hydratation from raw sql
     *
     * @param string $sql
     * @return PropelCollection
     */
    private function hydrateFromSql($sql)
    {
        $timeline = array();

        if ($sql) {
            // Exécution de la requête brute
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            $collection = new \PropelCollection();
            foreach ($result as $row) {
                $user = new PUser();
                $user->hydrate($row);

                $collection->append($user);
            }
        }

        return $collection;
    }

    /**
     * Find users by user's suggestion
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection[PUser]
     */
    public function findBySuggestion($userId, $offset = 0, $limit = 10)
    {
        $sql = $this->getSuggestionsSql($userId, $offset, $limit);
        $users = $this->hydrateFromSql($sql);

        return $users;
    }

    /* ######################################################################################################## */
    /*                                             AGGREGATIONS                                                 */
    /* ######################################################################################################## */
    
    /**
     *
     * @return PUserQuery
     */
    public function online()
    {
        return $this
            ->filterByOnline(true)
            ->filterByPUStatusId(UserConstants::STATUS_ACTIVED);
    }

    /**
     *
     * @param string $keyword
     * @return PUserQuery
     */
    public function orderWithKeyword($keyword = 'last')
    {
        return $this
            ->_if('mostFollowed' === $keyword)
                ->orderByMostFollowed()
            ->_elseif('mostActive' === $keyword)
                ->orderByMostActive()
            ->_elseif('last' === $keyword)
                ->orderByLast()
            ->_endif();
    }

    /**
     *
     * @return PUserQuery
     */
    public function orderByMostFollowed()
    {
        return $this
            ->joinPUFollowURelatedByPUserId('PUFollowU', \Criteria::LEFT_JOIN)
            ->withColumn('COUNT(PUFollowU.PUserId)', 'NbFollowers')
            ->groupBy('Id')
            ->orderBy('NbFollowers', 'desc');
    }

    /**
     * Note: only "positives" actions are counted
     * @return PUserQuery
     */
    public function orderByMostActive()
    {
        return $this
            ->withColumn('COUNT(p_u_reputation.id)', 'MostActive')
            ->join('PUReputation', \Criteria::LEFT_JOIN)
            ->where('PUReputation.p_r_action_id IN ('.implode(',', ReputationConstants::getPositivesPRActionsId()).')')
            ->groupBy('Id')
            ->orderBy('MostActive', 'desc');
    }

    /**
     *
     * @return PUserQuery
     */
    public function orderByLast()
    {
        return $this->orderByCreatedAt('desc');
    }

    /**
     *
     * @param array[string] $keywords
     * @return PUserQuery
     */
    public function filterByKeywords($keywords = null)
    {
        return $this
            ->_if($keywords && (in_array('lastDay', $keywords)))
                ->filterByLastDay()
            ->_endif()
            ->_if($keywords && (in_array('lastWeek', $keywords)))
                ->filterByLastWeek()
            ->_endif()
            ->_if($keywords && (in_array('lastMonth', $keywords)))
                ->filterByLastMonth()
            ->_endif()
            ->_if($keywords && in_array('qualified', $keywords))
                ->filterByQualified(true)
            ->_endif()
            ->_if($keywords && in_array('citizen', $keywords))
                ->filterByQualified(false)
            ->_endif();
    }

    /**
     *
     * @return PUserQuery
     */
    public function filterByLastDay()
    {
        // Dates début / fin
        $now = new \DateTime();
        $fromAt = new \DateTime();
        $fromAt->modify('-1 day');

        return $this->filterByCreatedAt(array('min' => $fromAt));
    }

    /**
     *
     * @return PUserQuery
     */
    public function filterByLastWeek()
    {
        // Dates début / fin
        $now = new \DateTime();
        $fromAt = new \DateTime();
        $fromAt->modify('-1 week');

        return $this->filterByCreatedAt(array('min' => $fromAt));
    }

    /**
     *
     * @return PUserQuery
     */
    public function filterByLastMonth()
    {
        // Dates début / fin
        $now = new \DateTime();
        $fromAt = new \DateTime();
        $fromAt->modify('-1 month');

        return $this->filterByCreatedAt(array('min' => $fromAt));
    }

    /**
     * Filter by array of tags id
     *
     * @param array[int]
     * @return PDDebateQuery
     */
    public function filterByTags($tagIds)
    {
        return $this
            ->usePuTaggedTPUserQuery()
                ->filterByPTagId($tagIds)
            ->endUse();
    }

    /**
     * Filter by geolocalization
     *
     * @param Geocoded $geocoded
     * @return PDDebateQuery
     */
    public function filterByGeolocalization(Geocoded $geocoded)
    {
    }

    /* ######################################################################################################## */
    /*                                              FILTERBY IF                                                 */
    /* ######################################################################################################## */

    /**
     *
     * @param boolean $online
     * @return PUserQuery
     */
    public function filterIfOnline($online = null)
    {
        return $this
            ->_if(null !== $online)
                ->filterByOnline($online)
            ->_endif();
    }

    /**
     *
     * @param boolean $qualified
     * @return PUserQuery
     */
    public function filterIfQualified($qualified = null)
    {
        return $this
            ->_if(null !== $qualified)
                ->filterByQualified($qualified)
            ->_endif();
    }

    /**
     *
     * @param boolean $notifReaction
     * @return PUserQuery
     */
    public function filterIfNotifReaction($notifReaction = null)
    {
        return $this
            ->_if(null !== $notifReaction)
                ->usePuFollowDdPUserQuery()
                    ->filterByNotifReaction($notifReaction)
                ->endUse()
            ->_endif();
    }
}
