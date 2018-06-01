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
            ->filterByBanned(false)
            ->filterByPUStatusId(UserConstants::STATUS_ACTIVED);
    }

    /**
     *
     * @return PUserQuery
     */
    public function offline()
    {
        return $this
            ->filterByOnline(false)
            ->_or()
            ->filterByPUStatusId(UserConstants::STATUS_ACTIVED, "<>")
            ->_or()
            ->filterByBanned(true)
            ;
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
     * cf. http://stackoverflow.com/questions/14549120/mysql-count-left-join-group-by-to-return-zero-rows
     *
     * @return PUserQuery
     */
    public function orderByMostActive()
    {
        return $this
            ->withColumn('COUNT(p_u_reputation.id)', 'MostActive')
            ->join('PUReputation', \Criteria::LEFT_JOIN)
            ->addJoinCondition('PUReputation', 'PUReputation.PRActionId IN ('.implode(',', ReputationConstants::getPositivesPRActionsId()).')')
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
     * @return PUserQuery
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
     * @return PUserQuery
     */
    public function filterByGeolocalization(Geocoded $geocoded)
    {
    }

    /**
     * Filter by custom filters
     *
     * @param array $filters ['only_elected' => boolean, 'city_insee_code' => string, 'department_code' => string ]
     * @return PUserQuery
     */
    public function filterByCustomFilters(array $filters = null)
    {
        if ($filters) {
            if ($filters['only_elected'] === true) {
                $this->filterByQualified(true);
            }

            if (!empty($filters['city_insee_code'])) {
                $this
                    ->usePLCityQuery()
                        ->filterByMunicipalityCode($filters['city_insee_code'])
                    ->endUse();
            }

            if (!empty($filters['department_code'])) {
                $this
                    ->usePLCityQuery()
                        ->usePLDepartmentQuery()
                            ->filterByCode($filters['department_code'])
                        ->endUse()
                    ->endUse();
            }
        }

        return $this;
    }

    /* ######################################################################################################## */
    /*                                              FILTERBY IF                                                 */
    /* ######################################################################################################## */

    /**
     *
     * @param array $cityIds
     * @return PUserQuery
     */
    public function filterIfCities($cityIds = null)
    {
        if ($cityIds && sizeof($cityIds) > 0) {
            return $this->filterByPLCityId($cityIds);
        }

        return $this;
    }

    /**
     *
     * @param array $tagIds
     * @return PUserQuery
     */
    public function filterIfTags($tagIds = null)
    {
        if ($tagIds && sizeof($tagIds) > 0) {
            return $this->filterByTags($tagIds);
        }

        return $this;
    }

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
}
