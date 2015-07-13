<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebateQuery;

use Geocoder\Result\Geocoded;

class PDDebateQuery extends BasePDDebateQuery
{
    // *****************************    RAW SQL    ************************* //
    
    /**
     *  Construction de la requête SQL renvoyant les suggestions de users pour un user.
     *
     *  @todo:
     *   > + suggestions depuis les tags des débats déjà suivis
     *
     * #########################
     * # Suggestions de débats #
     * #########################
     * #  Concordance des tags suivis / tags caractérisant des débats
     * SELECT DISTINCT
     *     created_at,
     *     updated_at,
     *     slug,
     *     id,
     *     p_user_id,
     *     title,
     *     file_name,
     *     with_shadow,
     *     copyright,
     *     description,
     *     note_pos,
     *     note_neg,
     *     nb_views,
     *     published,
     *     published_at,
     *     published_by,
     *     favorite,
     *     online
     * FROM (
     * ( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 1 as unionsorting
     * FROM p_d_debate
     *     LEFT JOIN p_d_d_tagged_t
     *         ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
     * WHERE
     *     p_d_d_tagged_t.p_tag_id IN (
     *                 SELECT p_tag.id
     *                 FROM p_tag
     *                     LEFT JOIN p_u_follow_t
     *                         ON p_tag.id = p_u_follow_t.p_tag_id
     *                 WHERE
     *                     p_tag.online = true
     *                     AND p_u_follow_t.p_user_id = 73
     *     )
     *         AND p_d_debate.online = 1
     *         AND p_d_debate.published = 1
     *         AND p_d_debate.id NOT IN (SELECT p_d_debate_id FROM p_u_follow_d_d WHERE p_user_id = 73)
     * )
     *
     * UNION DISTINCT
     *
     * #  Débats les plus populaires
     * ( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 2 as unionsorting
     *             FROM p_d_debate
     *                 LEFT JOIN p_u_follow_d_d
     *                     ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
     *                 WHERE
     *                     p_d_debate.online = 1
     *                     AND p_d_debate.published = 1
     *                 GROUP BY p_d_debate.id
     *                 ORDER BY nb_users DESC
     * )
     *
     * ORDER BY unionsorting ASC
     * ) unionsorting
     *
     * LIMIT 0, 10
     *
     * @param  integer     $userId
     * @param  integer     $offset
     * @param  integer     $limit
     * @return string
     */
    private function getSuggestionsSql($userId, $offset, $limit = 10)
    {
        // Requête SQL
        $sql = "
#########################
# Suggestions de débats #
#########################
#  Concordance des tags suivis / tags caractérisant des débats
SELECT DISTINCT
    created_at,
    updated_at,
    slug,
    id,
    p_user_id,
    title,
    file_name,
    with_shadow,
    copyright,
    description,
    note_pos,
    note_neg,
    nb_views,
    published,
    published_at,
    published_by,
    favorite,
    online
FROM (
( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 1 as unionsorting
FROM p_d_debate
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_d_tagged_t.p_tag_id IN (
                SELECT p_tag.id
                FROM p_tag
                    LEFT JOIN p_u_follow_t
                        ON p_tag.id = p_u_follow_t.p_tag_id
                WHERE
                    p_tag.online = true
                    AND p_u_follow_t.p_user_id = ".$userId."
    )
        AND p_d_debate.online = 1
        AND p_d_debate.published = 1
        AND p_d_debate.id NOT IN (SELECT p_d_debate_id FROM p_u_follow_d_d WHERE p_user_id = ".$userId.")
)

UNION DISTINCT

#  Débats les plus populaires
( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 2 as unionsorting
            FROM p_d_debate
                LEFT JOIN p_u_follow_d_d
                    ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
                WHERE
                    p_d_debate.online = 1
                    AND p_d_debate.published = 1
                GROUP BY p_d_debate.id
                ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC
) unionsorting

LIMIT ".$offset.", ".$limit."
";

        return $sql;
    }

    /**
     * Hydrate des objets PDDebate suite à une requête.
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
                $debate = new PDDebate();
                $debate->hydrate($row);

                $collection->append($debate);
            }
        }

        return $collection;
    }

    /**
     * Filtre les objets en fonction des tags suivis par le user entré en paramètre.
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @return  PropelCollection
     */
    public function findBySuggestion($userId, $offset = 0, $limit = 10)
    {
        $sql = $this->getSuggestionsSql($userId, $offset, $limit);
        $debates = $this->hydrateFromSql($sql);

        return $debates;
    }

    // *****************************    AGGREGATIONS / UTILES    ************************* //
    
    /**
     * Cumule les contraintes associés à un objet en ligne
     *
     * @return  Query
     */
    public function online()
    {
        return $this->filterByOnline(true)->filterByPublished(true);
    }

    /**
     * Ordonne suivant un mot clef défini sur la vue.
     *
     * @param string $keyword      Mot clef pour l'ordonnancement issu du html
     *
     * @return  Query
     */
    public function orderWithKeyword($keyword = 'last')
    {
        return $this->_if('mostFollowed' === $keyword)
                        ->orderByMostFollowed()
                    ->_elseif('bestNote' === $keyword)
                        ->orderByNote()
                    ->_elseif('last' === $keyword)
                        ->orderByLast()
                    ->_endif();
    }

    /**
     * Ordonne les objets par meilleur note
     *
     * @return  Query
     */
    public function orderByNote()
    {
        return $this->orderByNotePos('desc');
    }

    /**
     * Ordonne les objets par nombre de followers
     *
     * @return  Query
     */
    public function orderByMostFollowed()
    {
        return $this->joinPuFollowDdPDDebate('PUFollowDD', \Criteria::LEFT_JOIN)
                ->withColumn('COUNT(PUFollowDD.PUserId)', 'NbFollowers')
                ->groupBy('Id')
                ->orderBy('NbFollowers', 'desc')
                ;
    }

    /**
     * Ordonne les objets par derniers débats publiés
     *
     */
    public function orderByLast()
    {
        return $this->orderByPublishedAt('desc');
    }

    /**
     * Filtre suivant le mot(s) clef(s) défini sur la vue
     *
     * @param array $keywords
     * @return Query
     */
    public function filterByKeywords($keywords = null)
    {
        return $this->_if($keywords && (in_array('lastDay', $keywords)))
                        ->filterByLastDay()
                    ->_endif()
                    ->_if($keywords && (in_array('lastWeek', $keywords)))
                        ->filterByLastWeek()
                    ->_endif()
                    ->_if($keywords && (in_array('lastMonth', $keywords)))
                        ->filterByLastMonth()
                    ->_endif()
                    ->_if($keywords && in_array('qualified', $keywords))
                        ->filterByQualified()
                    ->_endif()
                    ->_if($keywords && in_array('citizen', $keywords))
                        ->filterByCitizen()
                    ->_endif()
                    ;
    }

    /**
     * Filtre les objets publiés durant les dernières 24h
     *
     * @return  Query
     */
    public function filterByLastDay()
    {
        // Dates début / fin
        $now = new \DateTime();
        $fromAt = new \DateTime();
        $fromAt->modify('-1 day');

        return $this->filterByPublishedAt(array('min' => $fromAt));
    }

    /**
     * Filtre les objets publiés durant la dernière semaine
     *
     * @return  Query
     */
    public function filterByLastWeek()
    {
        // Dates début / fin
        $now = new \DateTime();
        $fromAt = new \DateTime();
        $fromAt->modify('-1 week');

        return $this->filterByPublishedAt(array('min' => $fromAt));
    }

    /**
     * Filtre les objets publiés durant le mois dernier
     *
     * @return  Query
     */
    public function filterByLastMonth()
    {
        // Dates début / fin
        $now = new \DateTime();
        $fromAt = new \DateTime();
        $fromAt->modify('-1 month');

        return $this->filterByPublishedAt(array('min' => $fromAt));
    }

    /**
     * Filtre les objets uniquement rédigés par des profils débatteurs
     *
     * @return  Query
     */
    public function filterByQualified()
    {
        return $this->usePUserQuery()
                        ->filterByQualified(true)
                    ->endUse()
                    ;
    }

    /**
     * Filtre les objets uniquement rédigés par des profils citoyens
     *
     * @return  Query
     */
    public function filterByCitizen()
    {
        return $this->usePUserQuery()
                        ->filterByQualified(false)
                    ->endUse()
                    ;
    }

    /**
     * Filtre les objets par géolocalisation
     * @todo requête géoloc / tags
     *
     * @param   Geocoder\Result\Geocoded    $geocoded
     * @return  Query
     */
    public function filterByGeolocalization(Geocoded $geocoded)
    {
    }
}
