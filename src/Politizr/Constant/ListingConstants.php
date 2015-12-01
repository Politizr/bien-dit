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

    const DASHBOARD_DEBATES = 'DASHBOARD_DEBATES';
    const DASHBOARD_REACTIONS = 'DASHBOARD_REACTIONS';
    const DASHBOARD_USERS = 'DASHBOARD_USERS';

    // ************************************************************ //
    //              Dashboard Constants                             //
    // ************************************************************ //
    const DASHBOARD_MAP_LIMIT = 3;
    const DASHBOARD_TAG_LIMIT = 10;
}
