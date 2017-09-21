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
    const REASON_AUTHORIZED_CIRCLE_USER = 3;
    const REASON_NOT_AUTHORIZED_CIRCLE_USER = 4;
    const REASON_USER_ELECTED = 5;
    const REASON_USER_OPERATION = 6;
    const REASON_OWNER_OPERATION = 7;
}
