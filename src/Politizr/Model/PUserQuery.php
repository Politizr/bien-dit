<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePUserQuery;

use Politizr\Constant\ListingConstants;
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
    public function orderWithKeyword($keyword = ListingConstants::ORDER_BY_KEYWORD_LAST)
    {
        return $this
            ->_if(ListingConstants::ORDER_BY_KEYWORD_MOST_FOLLOWED === $keyword)
                ->orderByMostFollowed()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE === $keyword)
                ->orderByMostActive()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_LAST === $keyword)
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
            ->_if($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_DAY, $keywords)))
                ->filterByLastDay()
            ->_endif()
            ->_if($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_WEEK, $keywords)))
                ->filterByLastWeek()
            ->_endif()
            ->_if($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_MONTH, $keywords)))
                ->filterByLastMonth()
            ->_endif()
            ->_if($keywords && in_array(ListingConstants::FILTER_KEYWORD_QUALIFIED, $keywords))
                ->filterByQualified(true)
            ->_endif()
            ->_if($keywords && in_array(ListingConstants::FILTER_KEYWORD_CITIZEN, $keywords))
                ->filterByQualified(false)
            ->_endif();
    }

    /**
     *
     * @return PUserQuery
     */
    public function filterByLastDay()
    {
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
