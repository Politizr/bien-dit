<?php

namespace Politizr\Constant;

/**
 * Listing constants for pagination
 *
 * @author Lionel Bouzonville
 */
class ListingConstants
{
    // ************************************************************ //
    //              Pagination Constants                            //
    // ************************************************************ //

    const LISTING_CLASSIC_PAGINATION = 20;
    const TIMELINE_CLASSIC_PAGINATION = 20;
    const TIMELINE_USER_CLASSIC_PAGINATION = 20;
    const REPUTATION_CHARTS_PAGINATION = 20;
    const LISTING_RSS = 50;

    // ************************************************************ //
    //              LISTING & Timeline Type Constants                 //
    // ************************************************************ //

    const LISTING_TYPE_SEARCH = 1;
    const LISTING_TYPE_RANKING = 2;
    const LISTING_TYPE_SUGGESTION = 3;
    const LISTING_TYPE_TAG = 4;
    const LISTING_TYPE_GEOTAG = 5;
    const LISTING_TYPE_ORGANIZATION = 6;
    const LISTING_TYPE_FOLLOWED = 7;
    const LISTING_TYPE_FOLLOWER = 8;

    const TIMELINE_TYPE = 9;
    const TIMELINE_USER_TYPE = 10;
    
    const FEED_DEBATE_TYPE = 11;

    const MY_DRAFTS_TYPE = 12;
    const MY_PUBLICATIONS_TYPE = 13;

    // ************************************************************ //
    //                  Top Constants                               //
    // ************************************************************ //
    const LISTING_TOP_TAGS_LIMIT = 10;
    const LISTING_TOP_DOCUMENTS_LIMIT = 5;
    const LISTING_SUGGESTION_DOCUMENTS_LIMIT = 10;

    const LISTING_LAST_DEBATE_FOLLOWERS = 14;
    const LISTING_LAST_USER_FOLLOWERS = 14;
    const LISTING_LAST_USER_SUBSCRIBERS = 14;
    
    const LISTING_DEBATE_SIMILARS = 3;

    const LISTING_HOMEPAGE_DOCUMENTS_LIMIT = 9;
    const LISTING_HOMEPAGE_USERS_LIMIT = 6;


    // ************************************************************ //
    //            Filters & Order keyword constants                 //
    // ************************************************************ //

    const ORDER_BY_KEYWORD_MOST_FOLLOWED = 'mostFollowed';
    const ORDER_BY_KEYWORD_MOST_ACTIVE = 'mostActive';
    const ORDER_BY_KEYWORD_BEST_NOTE = 'bestNote';
    const ORDER_BY_KEYWORD_LAST = 'last';
    const ORDER_BY_KEYWORD_MOST_REACTIONS = 'mostReactions';
    const ORDER_BY_KEYWORD_MOST_COMMENTS = 'mostComments';
    const ORDER_BY_KEYWORD_MOST_VIEWS = 'mostViews';
    const ORDER_BY_KEYWORD_MOST_LENGTH = 'mostLength';

    const FILTER_KEYWORD_ALL_DATE = 'allDate';
    const FILTER_KEYWORD_LAST_DAY = 'lastDay';
    const FILTER_KEYWORD_LAST_WEEK = 'lastWeek';
    const FILTER_KEYWORD_LAST_MONTH = 'lastMonth';
    const FILTER_KEYWORD_EXACT_MONTH = 'exactMonth';
    
    const FILTER_KEYWORD_ALL_USERS = 'allUsers';
    const FILTER_KEYWORD_QUALIFIED = 'qualified';
    const FILTER_KEYWORD_CITIZEN = 'citizen';

    const FILTER_KEYWORD_ALL_PUBLICATIONS = 'allPublications';
    const FILTER_KEYWORD_DEBATES_AND_REACTIONS = 'debatesReactionsPublications';
    const FILTER_KEYWORD_DEBATES = 'debates';
    const FILTER_KEYWORD_REACTIONS = 'reactions';
    const FILTER_KEYWORD_COMMENTS = 'comments';
}
