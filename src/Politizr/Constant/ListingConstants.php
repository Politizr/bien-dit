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

    const MODAL_CLASSIC_PAGINATION = 10;
    const REPUTATION_CHARTS_PAGINATION = 20;

    // ************************************************************ //
    //              Modal context Constants                         //
    // ************************************************************ //

    const MODAL_DEBATES = 'MODAL_DEBATES';
    const MODAL_REACTIONS = 'MODAL_REACTIONS';
    const MODAL_USERS = 'MODAL_USERS';

    // ************************************************************ //
    //              Modal & Timeline Type Constants                 //
    // ************************************************************ //

    const MODAL_TYPE_SEARCH = 1;
    const MODAL_TYPE_RANKING = 2;
    const MODAL_TYPE_SUGGESTION = 3;
    const MODAL_TYPE_TAG = 4;
    const MODAL_TYPE_GEOTAG = 5;
    const MODAL_TYPE_ORGANIZATION = 6;
    const MODAL_TYPE_FOLLOWED = 7;
    const MODAL_TYPE_FOLLOWER = 8;

    const TIMELINE_TYPE = 9;
    const TIMELINE_USER_TYPE = 10;
    
    const FEED_DEBATE_TYPE = 11;

    const MY_DRAFTS_TYPE = 12;
    const MY_PUBLICATIONS_TYPE = 13;

    // ************************************************************ //
    //              Dashboard type & context constants              //
    // ************************************************************ //
    const DASHBOARD_TYPE_MAP = 'DASHBOARD_MAP';
    const DASHBOARD_TYPE_TOP_DEBATES = 'DASHBOARD_TOP_DEBATES';
    const DASHBOARD_TYPE_TOP_USERS = 'DASHBOARD_TOP_USERS';
    const DASHBOARD_TYPE_GEO_DEBATES = 'DASHBOARD_GEO_DEBATES';
    const DASHBOARD_TYPE_GEO_USERS = 'DASHBOARD_GEO_USERS';
    const DASHBOARD_TYPE_SUGGESTION_DEBATES = 'DASHBOARD_SUGGESTION_DEBATES';
    const DASHBOARD_TYPE_SUGGESTION_USERS = 'DASHBOARD_SUGGESTION_USERS';

    const DASHBOARD_DEBATES = 'DASHBOARD_DEBATES';
    const DASHBOARD_REACTIONS = 'DASHBOARD_REACTIONS';
    const DASHBOARD_USERS = 'DASHBOARD_USERS';

    // ************************************************************ //
    //              Dashboard Constants                             //
    // ************************************************************ //
    const DASHBOARD_MAP_LIMIT = 3;
    const DASHBOARD_TOP_TAGS_LIMIT = 20;
    const DASHBOARD_TOP_DEBATES_LIMIT = 6;
    const DASHBOARD_TOP_USERS_LIMIT = 6;
    const DASHBOARD_GEO_DEBATES_LIMIT = 2;
    const DASHBOARD_GEO_USERS_LIMIT = 2;
    const DASHBOARD_SUGGESTION_DEBATES_LIMIT = 3;
    const DASHBOARD_SUGGESTION_USERS_LIMIT = 5; // @todo /!\ hack > +1 to hack suggestion user query which return 1 first null element

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

    const FILTER_KEYWORD_ALL_DATE = 'allDate';
    const FILTER_KEYWORD_ALL_USERS = 'allUsers';
    const FILTER_KEYWORD_LAST_DAY = 'lastDay';
    const FILTER_KEYWORD_LAST_WEEK = 'lastWeek';
    const FILTER_KEYWORD_LAST_MONTH = 'lastMonth';
    const FILTER_KEYWORD_QUALIFIED = 'qualified';
    const FILTER_KEYWORD_CITIZEN = 'citizen';
}
