<?php

namespace Politizr\Constant;

/**
 *
 * @author Lionel Bouzonville
 */
class NotificationConstants
{
    // ******************************************************** //
    //                      NOTIFS TYPES                        //
    // ******************************************************** //
    const TYPE_SCREEN = 1;
    const TYPE_EMAIL = 2;
    const TYPE_EMAIL_TXT = 3;
    const TYPE_EMAIL_SUBJECT = 4;

    // ******************************************************** //
    //                      DOCUMENTS                           //
    // ******************************************************** //
    const ID_D_COMMENT_PUBLISH = 1;
    const ID_D_NOTE_POS = 2;
    const ID_D_NOTE_NEG = 3;

    // ******************************************************** //
    //                        DEBATES                           //
    // ******************************************************** //
    const ID_D_D_REACTION_PUBLISH = 4;
    const ID_D_D_FOLLOWED = 5;

    // ******************************************************** //
    //                      REACTIONS                           //
    // ******************************************************** //
    const ID_D_R_REACTION_PUBLISH = 6;

    // ******************************************************** //
    //                      COMMENTS                            //
    // ******************************************************** //
    const ID_D_C_NOTE_POS = 7;
    const ID_D_C_NOTE_NEG = 8;

    // ******************************************************** //
    //                         USERS                            //
    // ******************************************************** //
    const ID_U_FOLLOWED = 9;
    const ID_U_BADGE = 10;

    // ******************************************************** //
    //                SUBSCRIBE / FOLLOWING                     //
    // ******************************************************** //
    const ID_S_U_DEBATE_PUBLISH = 11;
    const ID_S_U_REACTION_PUBLISH = 12;
    const ID_S_U_COMMENT_PUBLISH = 13;

    const ID_S_D_REACTION_PUBLISH = 14;

    const ID_S_D_D_COMMENT_PUBLISH = 15;
    const ID_S_D_R_COMMENT_PUBLISH = 16;

    const ID_S_T_USER = 17;
    const ID_S_T_DOCUMENT = 18;

    // ******************************************************** //
    //                SUBSCRIBE / FOLLOWING                     //
    // ******************************************************** //
    const ID_L_U_CITY = 20;
    const ID_L_U_DEPARTMENT = 21;
    const ID_L_U_REGION = 22;

    const ID_L_D_CITY = 23;
    const ID_L_D_DEPARTMENT = 24;
    const ID_L_D_REGION = 25;

    // ******************************************************** //
    //                        ADMIN                             //
    // ******************************************************** //
    const ID_ADM_MESSAGE = 19;


    /**
     * Return array of region ids
     *
     * @return array
     */
    public static function getDefaultNotificationSubscribeIds()
    {
        return [
            NotificationConstants::ID_D_COMMENT_PUBLISH,
            NotificationConstants::ID_D_NOTE_POS,
            NotificationConstants::ID_D_NOTE_NEG,
            NotificationConstants::ID_D_D_REACTION_PUBLISH,
            NotificationConstants::ID_D_D_FOLLOWED,
            NotificationConstants::ID_D_R_REACTION_PUBLISH,
            NotificationConstants::ID_D_C_NOTE_POS,
            NotificationConstants::ID_D_C_NOTE_NEG,
            NotificationConstants::ID_U_FOLLOWED,
            NotificationConstants::ID_U_BADGE,
            NotificationConstants::ID_S_U_DEBATE_PUBLISH,
            NotificationConstants::ID_S_U_REACTION_PUBLISH,
            NotificationConstants::ID_S_U_COMMENT_PUBLISH,
            NotificationConstants::ID_S_D_REACTION_PUBLISH,
            NotificationConstants::ID_S_D_D_COMMENT_PUBLISH,
            NotificationConstants::ID_S_D_R_COMMENT_PUBLISH,
            NotificationConstants::ID_L_U_CITY,
            NotificationConstants::ID_L_U_DEPARTMENT,
            NotificationConstants::ID_L_U_REGION,
            NotificationConstants::ID_L_D_CITY,
            NotificationConstants::ID_L_D_DEPARTMENT,
            NotificationConstants::ID_ADM_MESSAGE
        ];
    }

}
