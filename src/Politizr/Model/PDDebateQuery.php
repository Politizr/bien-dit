<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePDDebateQuery;

use Politizr\Constant\ListingConstants;

use Geocoder\Result\Geocoded;

/**
 * Debate query
 *
 * @author Lionel Bouzonville
 */
class PDDebateQuery extends BasePDDebateQuery
{
    /* ######################################################################################################## */
    /*                                             AGGREGATIONS                                                 */
    /* ######################################################################################################## */
    
    /**
     *
     * @return PDDebateQuery
     */
    public function online()
    {
        return $this
            ->filterByOnline(true)
            ->filterByModerated(false)
            ->_or()
            ->filterByModerated(null)
            ->filterByPublished(true)
            ->filterByPublishedAt(null, \Criteria::NOT_EQUAL);
    }

    /**
     *
     * @return PDDebateQuery
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
     * @return PDDebateQuery
     */
    public function orderWithKeyword($keyword = ListingConstants::ORDER_BY_KEYWORD_LAST)
    {
        return $this
            ->_if(ListingConstants::ORDER_BY_KEYWORD_MOST_FOLLOWED === $keyword)
                ->orderByMostFollowed()
            ->_elseif(ListingConstants::ORDER_BY_KEYWORD_BEST_NOTE === $keyword)
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
     * @return PDDebateQuery
     */
    public function orderByNote()
    {
        return $this->orderByNotePos('desc')->orderByNoteNeg('asc');
    }

    /**
     * Order by number of followers
     *
     * @return PDDebateQuery
     */
    public function orderByMostFollowed()
    {
        return $this
            ->joinPuFollowDdPDDebate('PUFollowDD', \Criteria::LEFT_JOIN)
            ->withColumn('COUNT(PUFollowDD.PUserId)', 'NbFollowers')
            ->groupBy('Id')
            ->orderBy('NbFollowers', 'desc');
    }

    /**
     * Order by number of reactions
     *
     * @return PDDebateQuery
     */
    public function orderByMostReactions()
    {
        return $this
            ->withColumn('COUNT(p_d_reaction.Id)', 'NbReactions')
            ->join('PDReaction', \Criteria::LEFT_JOIN)
            ->where('PDReaction.published = true')
            ->groupBy('Id')
            ->orderBy('NbReactions', 'desc');
    }

    /**
     * Order by number of comments
     *
     * @return PDDebateQuery
     */
    public function orderByMostComments()
    {
        return $this
            ->withColumn('COUNT(p_d_d_comment.Id)', 'NbComments')
            ->join('PDDComment', \Criteria::LEFT_JOIN)
            ->where('PDDComment.online = true')
            ->groupBy('Id')
            ->orderBy('NbComments', 'desc');
    }

    /**
     * Order by number of views
     *
     * @return PDDebateQuery
     */
    public function orderByMostViews()
    {
        return $this
            ->orderByNbViews('desc');
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
     * @return PDDebateQuery
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
     * @return PDDebateQuery
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
     * @return PDDebateQuery
     */
    public function filterByLastMonth()
    {
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

    /**
     * Filter by array of tags id
     *
     * @param array[int]
     * @return PDDebateQuery
     */
    public function filterByTags($tagIds)
    {
        return $this
            ->usePDDTaggedTQuery()
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
}
