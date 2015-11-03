<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDReactionQuery;

/**
 * Reaction query
 *
 * @author Lionel Bouzonville
 */
class PDReactionQuery extends BasePDReactionQuery
{
    /* ######################################################################################################## */
    /*                                               AGGREGATION                                                */
    /* ######################################################################################################## */

    /**
     *
     */
    public function online()
    {
        return $this->filterByOnline(true)->filterByPublished(true);
    }

    /* ######################################################################################################## */
    /*                                         CUSTOM FILTERS / ORDERS                                          */
    /* ######################################################################################################## */
    
    /**
     * Order by keyword
     * @todo refactor keyword to constant
     *
     * @param string $keyword
     * @return PDDebateQuery
     */
    public function orderWithKeyword($keyword = 'last')
    {
        return $this
            ->_if('bestNote' === $keyword)
                ->orderByNote()
            ->_elseif('last' === $keyword)
                ->orderByLast()
            ->_endif();
    }

    /**
     * Order by note pos
     *
     * @return PDDebateQuery
     */
    public function orderByNote()
    {
        return $this->orderByNotePos('desc')->orderByNoteNeg('asc');
    }

    /**
     * Order by last published
     *
     * @return PDDebateQuery
     */
    public function orderByLast()
    {
        return $this->orderByPublishedAt('desc');
    }

    /**
     * Filter by keyword
     *
     * @param array[string] $keywords
     * @return PDDebateQuery
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
                ->filterByQualified()
            ->_endif()
            ->_if($keywords && in_array('citizen', $keywords))
                ->filterByCitizen()
            ->_endif();
    }

    /**
     * Filter by published during last 24h
     *
     * @return PDDebateQuery
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
     * Filter by published during last week
     *
     * @return PDDebateQuery
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
     * Filter by published during last month
     *
     * @return PDDebateQuery
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
     * Filter by qualified
     *
     * @return PDDebateQuery
     */
    public function filterByQualified()
    {
        return $this
            ->usePUserQuery()
                ->filterByQualified(true)
            ->endUse();
    }

    /**
     * Filter by not qualified
     *
     * @return PDDebateQuery
     */
    public function filterByCitizen()
    {
        return $this
            ->usePUserQuery()
                ->filterByQualified(false)
            ->endUse();
    }

//     /**
//      * Filter by array of tags id
//      *
//      * @param array[int]
//      * @return PDDebateQuery
//      */
//     public function filterByTags($tagIds)
//     {
//         return $this
//             ->usePDDTaggedTQuery()
//                 ->filterByPTagId($tagIds)
//             ->endUse();
//     }

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
     * @param boolean $published
     */
    public function filterIfPublished($published = null)
    {
        return $this
            ->_if(null !== $published)
                ->filterByPublished($published)
            ->_endif();
    }

    /**
     *
     * @param boolean $treeLevel
     */
    public function filterIfTreeLevel($treeLevel = null)
    {
        return $this
            ->_if(null !== $treeLevel)
                ->filterByTreeLevel($treeLevel)
            ->_endif();
    }
}
