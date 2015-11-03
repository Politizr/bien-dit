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
    const MODAL_USERS = 'MODAL_USERS';

    // ************************************************************ //
    //              Modal & Timeline Type Constants                 //
    // ************************************************************ //

    const MODAL_TYPE_SEARCH = 1;
    const MODAL_TYPE_RANKING = 2;
    const MODAL_TYPE_SUGGESTION = 3;
    const MODAL_TYPE_TAG = 4;
    const MODAL_TYPE_ORGANIZATION = 5;
    const MODAL_TYPE_FOLLOWED = 6;
    const MODAL_TYPE_FOLLOWER = 7;

    const TIMELINE_TYPE = 8;
    const TIMELINE_USER_TYPE = 9;
    
    const FEED_DEBATE_TYPE = 10;

    const MY_DRAFTS_TYPE = 11;
    const MY_PUBLICATIONS_TYPE = 12;
}
