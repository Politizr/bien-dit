<?php

namespace Politizr\Constant;

/**
 *
 * @author Lionel Bouzonville
 */
class DocumentConstants
{
    // ******************************************************** //
    //                   "WANT BOOST" TYPES                     //
    // ******************************************************** //
    const WB_NO_RESPONSE = 0;
    const WB_OK = 1;
    const WB_NO = 2;

    // ******************************************************** //
    //                   REACTION REASONS                       //
    // ******************************************************** //
    const REASON_NONE = 0;
    const REASON_USER_NOT_LOGGED = 1;
    const REASON_DEBATE_OWNER = 2;
    const REASON_CIRCLE_READ_ONLY = 3;
    const REASON_AUTHORIZED_CIRCLE_USER = 4;
    const REASON_NOT_AUTHORIZED_CIRCLE_USER = 5;
    const REASON_USER_ELECTED = 6;
    const REASON_USER_NOT_CERTIFIED = 7;
    const REASON_USER_OPERATION = 8;
    const REASON_OWNER_OPERATION = 9;
    const REASON_NO_REPUTATION = 10;

    // ******************************************************** //
    //                   NOTATION REASONS                       //
    // ******************************************************** //
    const NOTATION_AUTHORIZED = 0;
    const NOTATION_REASON_NOT_LOGGED = 1;
    const NOTATION_REASON_VOTE_POS_DONE = 2;
    const NOTATION_REASON_VOTE_NEG_DONE = 3;
    const NOTATION_REASON_DOC_OWNER = 4;
    const NOTATION_REASON_NO_REPUTATION = 5;
    const NOTATION_REASON_CIRCLE_READ_ONLY = 6;

    // ******************************************************* //
    const PRIVATE_DOC_LENGTH = 500;

    // ******************************************************** //
    //                      DOC MEDIAS                          //
    // ******************************************************** //
    const DOC_THUMBNAIL_PREFIX = 'thb-';
    const DOC_MAIN_IMAGE_MIN_WIDTH = 250;
    const DOC_IMAGE_UPLOAD_FAILED = 'broken_image.jpg';
}
