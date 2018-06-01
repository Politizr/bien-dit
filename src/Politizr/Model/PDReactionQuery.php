<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDReactionQuery;

use Politizr\Constant\ListingConstants;

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
     * @return PDReactionQuery
     */
    public function online()
    {
        return $this
            ->filterByOnline(true)
            ->filterByModerated(false)
            ->_or()
            ->filterByModerated(null)
            ->filterByPublished(true)
            ->filterByPublishedAt(null, \Criteria::NOT_EQUAL)
            ->filterByTreeLevel(0, \Criteria::NOT_EQUAL);
    }

    /**
     *
     * @return PDReactionQuery
     */
    public function offline()
    {
        return $this
            ->filterByOnline(false)
            ->_or()
            ->filterByModerated(true)
            ->_or()
            ->filterByPublished(false)
            ->_or()
            ->filterByPublishedAt(null);
    }

    /**
     * Filter publication's author qualified
     */
    public function onlyElected()
    {
        return $this
            ->usePUserQuery()
                ->filterByQualified(true)
            ->endUse();
    }

    /* ######################################################################################################## */
    /*                                         CUSTOM FILTERS / ORDERS                                          */
    /* ######################################################################################################## */
    
    /**
     * Order by keyword
     *
     * @param string $keyword
     * @return PDReactionQuery
     */
    public function orderWithKeyword($keyword = ListingConstants::ORDER_BY_KEYWORD_LAST)
    {
        return $this
            ->_if(ListingConstants::ORDER_BY_KEYWORD_BEST_NOTE === $keyword)
                ->orderByNote()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_LAST === $keyword)
                ->orderByLast()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_MOST_REACTIONS === $keyword)
                ->orderByMostReactions()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_MOST_COMMENTS === $keyword)
                ->orderByMostComments()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_MOST_VIEWS === $keyword)
                ->orderByMostViews()
            ->_endif();
    }

    /**
     * Order by note pos
     *
     * @return PDReactionQuery
     */
    public function orderByNote()
    {
        return $this->orderByNotePos('desc')->orderByNoteNeg('asc');
    }

    /**
     * Order by number of reactions
     *
     * @return PDReactionQuery
     */
    public function orderByMostReactions()
    {
        return $this
            ->withColumn('(p_d_reaction.tree_right - p_d_reaction.tree_left)', 'NbChildrenReactions')
            ->where('(p_d_reaction.tree_right - p_d_reaction.tree_left) > 1')
            ->orderBy('NbChildrenReactions', 'desc');
    }

    /**
     * Order by number of comments
     *
     * @return PDReactionQuery
     */
    public function orderByMostComments()
    {
        return $this
            ->withColumn('COUNT(p_d_r_comment.Id)', 'NbComments')
            ->join('PDRComment', \Criteria::LEFT_JOIN)
            ->where('PDRComment.online = true')
            ->groupBy('Id')
            ->orderBy('NbComments', 'desc');
    }

    /**
     * Order by number of views
     *
     * @return PDReactionQuery
     */
    public function orderByMostViews()
    {
        return $this
            ->orderByNbViews('desc');
    }

    /**
     * Order by last published
     *
     * @return PDReactionQuery
     */
    public function orderByLast()
    {
        return $this->orderByPublishedAt('desc');
    }

    /**
     * Filter by keyword
     *
     * @param array[string] $keywords
     * @return PDReactionQuery
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
                ->filterByQualified()
            ->_endif()
            ->_if($keywords && in_array(ListingConstants::FILTER_KEYWORD_CITIZEN, $keywords))
                ->filterByCitizen()
            ->_endif();
    }

    /**
     * Filter by published during last 24h
     *
     * @return PDReactionQuery
     */
    public function filterByLastDay()
    {
        $fromAt = new \DateTime();
        $fromAt->modify('-1 day');

        return $this->filterByPublishedAt(array('min' => $fromAt));
    }

    /**
     * Filter by published during last week
     *
     * @return PDReactionQuery
     */
    public function filterByLastWeek()
    {
        $fromAt = new \DateTime();
        $fromAt->modify('-1 week');

        return $this->filterByPublishedAt(array('min' => $fromAt));
    }

    /**
     * Filter by published during last month
     *
     * @return PDReactionQuery
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
     * @return PDReactionQuery
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
     * @return PDReactionQuery
     */
    public function filterByCitizen()
    {
        return $this
            ->usePUserQuery()
                ->filterByQualified(false)
            ->endUse();
    }

    /**
     * Filter by array of tags id
     *
     * @param array[int]
     * @return PDReactionQuery
     */
    public function filterByTags($tagIds)
    {
        return $this
            ->usePDRTaggedTQuery()
                ->filterByPTagId($tagIds)
            ->endUse();
    }

    /**
     * Filter by geolocalization
     *
     * @param Geocoded $geocoded
     * @return PDReactionQuery
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
