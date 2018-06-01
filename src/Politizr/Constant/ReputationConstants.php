<?php

namespace Politizr\Constant;

/**
 * https://docs.google.com/document/d/1TJtr-a17c4e2mlHyCizBCC-UKGPsFYrsD59PeN2Uiks/edit#heading=h.lgtqz6a76v9q
 *
 * @author Lionel Bouzonville
 */
class ReputationConstants
{
    // ******************************************************** //
    //            GLOBAL/GENERAL CONSTANTS
    // ******************************************************** //
    const USER_ELECTED_NOT_VALIDATED = 1;
    const SCORE_NOT_REACHED = 2;

    const DEFAULT_CITIZEN_REPUTATION = 100;
    const DEFAULT_ELECTED_REPUTATION = 150;


    // ******************************************************** //
    //            PRBADGETYPE OBJECT ID CONSTANTS
    // ******************************************************** //
    const BADGE_TYPE_DEBATE = 1;
    const BADGE_TYPE_COMMENT = 2;
    const BADGE_TYPE_PARTICIPATION = 3;
    const BADGE_TYPE_MODERATION = 4;
    const BADGE_TYPE_OTHER = 5;

    // ******************************************************** //
    //            PRMETALTYPE OBJECT ID CONSTANTS
    // ******************************************************** //
    const METAL_TYPE_BRONZE = 1;
    const METAL_TYPE_SILVER = 2;
    const METAL_TYPE_GOLD = 3;

    // ******************************************************** //
    //            PRACTION OBJECT ID CONSTANTS
    // ******************************************************** //

    // ******************************************************** //
    //                  UPDATE REPUT VIA ADMIN
    // ******************************************************** //
    const ACTION_ID_R_ADMIN_POS = 1;
    const ACTION_ID_R_ADMIN_NEG = 2;

    // ******************************************************** //
    //              DEBATES / REACTIONS / COMMENTS
    // ******************************************************** //
    const ACTION_ID_D_DEBATE_PUBLISH = 3;
    const ACTION_ID_D_REACTION_PUBLISH = 4;
    const ACTION_ID_D_COMMENT_PUBLISH = 5;
    
    const ACTION_ID_D_TARGET_DEBATE_REACTION_PUBLISH = 6;
    const ACTION_ID_D_TARGET_REACTION_REACTION_PUBLISH = 7;

    const ACTION_ID_D_TARGET_DEBATE_COMMENT_PUBLISH = 8;
    const ACTION_ID_D_TARGET_REACTION_COMMENT_PUBLISH = 9;

    // ******************************************************** //
    //                      NOTATIONS
    // ******************************************************** //
    const ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS = 10;
    const ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG = 11;

    const ACTION_ID_D_AUTHOR_REACTION_NOTE_POS = 12;
    const ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG = 13;

    const ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS = 14;
    const ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG = 15;

    const ACTION_ID_D_TARGET_DEBATE_NOTE_POS = 16;
    const ACTION_ID_D_TARGET_DEBATE_NOTE_NEG = 17;

    const ACTION_ID_D_TARGET_REACTION_NOTE_POS = 18;
    const ACTION_ID_D_TARGET_REACTION_NOTE_NEG = 19;

    const ACTION_ID_D_TARGET_COMMENT_NOTE_POS = 20;
    const ACTION_ID_D_TARGET_COMMENT_NOTE_NEG = 21;

    // ******************************************************** //
    //                      FOLLOWING
    // ******************************************************** //
    const ACTION_ID_D_AUTHOR_DEBATE_FOLLOW = 22;
    const ACTION_ID_D_AUTHOR_DEBATE_UNFOLLOW = 23;

    const ACTION_ID_U_AUTHOR_USER_FOLLOW = 24;
    const ACTION_ID_U_AUTHOR_USER_UNFOLLOW = 25;

    const ACTION_ID_D_TARGET_DEBATE_FOLLOW = 26;
    const ACTION_ID_D_TARGET_DEBATE_UNFOLLOW = 27;

    const ACTION_ID_U_TARGET_USER_FOLLOW = 28;
    const ACTION_ID_U_TARGET_USER_UNFOLLOW = 29;

    // ******************************************************** //
    //                      SOCIAL NETWORK
    // ******************************************************** //
    const ACTION_ID_D_SHARE= 30;
    
    // ******************************************************** //
    //            PRBADGE OBJECT ID CONSTANTS
    // ******************************************************** //
    const BADGE_ID_QUERELLE = 1;
    const BADGE_ID_CONTROVERSE = 2;
    const BADGE_ID_POLEMIQUE = 3;
    const BADGE_ID_REDACTEUR = 4;
    const BADGE_ID_AUTEUR = 5;
    const BADGE_ID_ECRIVAIN = 6;
    const BADGE_ID_ECLAIREUR = 7;
    const BADGE_ID_AVANT_GARDE = 8;
    const BADGE_ID_GUIDE = 9;
    const BADGE_ID_ANNOTATEUR = 10;
    const BADGE_ID_GLOSSATEUR = 11;
    const BADGE_ID_COMMENTATEUR = 12;
    const BADGE_ID_EFFRONTE = 13;
    const BADGE_ID_IMPERTINENT = 14;
    const BADGE_ID_AUDACIEUX = 15;
    const BADGE_ID_STUDIEUX = 16;
    const BADGE_ID_TAGUEUR = 17;
    const BADGE_ID_SURVEILLANT = 18;
    const BADGE_ID_FOUGUEUX = 19;
    const BADGE_ID_ENTHOUSIASTE = 20;
    const BADGE_ID_PASSIONNE = 21;
    const BADGE_ID_PERSIFLEUR = 22;
    const BADGE_ID_REPROBATEUR = 23;
    const BADGE_ID_CRITIQUE = 24;
    const BADGE_ID_ATTENTIF = 25;
    const BADGE_ID_ASSIDU = 26;
    const BADGE_ID_FIDELE = 27;
    const BADGE_ID_SUIVEUR = 28;
    const BADGE_ID_DISCIPLE = 29;
    const BADGE_ID_INCONDITIONNEL = 30;
    const BADGE_ID_IMPORTANT = 31;
    const BADGE_ID_INFLUENT = 32;
    const BADGE_ID_INCONTOURNABLE = 33;
    const BADGE_ID_PORTE_VOIX = 34;
    const BADGE_ID_FAN = 35;
    const BADGE_ID_AMBASSADEUR = 36;

    // ******************************************************** //
    //       ACTIONS' POST INSCRIPTION FUNCTIONAL CONSTANTS
    // ******************************************************** //
    const ACTION_CITIZEN_INSCRIPTION = 100;
    const ACTION_ELECTED_INSCRIPTION = 150;

    // ******************************************************** //
    //             ACTIONS' FUNCTIONAL CONSTANTS
    //            MINIMAL REPUTATION TO REACH FOR ACTION
    // ******************************************************** //
    const ACTION_COMMENT_NOTE_POS = 0;
    const ACTION_REACTION_NOTE_POS = 0;
    const ACTION_DEBATE_NOTE_POS = 0;

    const ACTION_COMMENT_NOTE_NEG = 20;
    const ACTION_REACTION_NOTE_NEG = 20;
    const ACTION_DEBATE_NOTE_NEG = 20;
    
    const ACTION_COMMENT_WRITE = 10;
    const ACTION_DEBATE_WRITE = 50;
    const ACTION_REACTION_WRITE = 100;

    const ACTION_ABUSE_REPORT = 0;


    // ******************************************************** //
    //             BADGES' FUNCTIONAL CONSTANTS
    // ******************************************************** //
    const QUERELLE_NB_REACTIONS = 3;
    const CONTROVERSE_NB_REACTIONS = 5;
    const POLEMIQUE_NB_REACTIONS = 10;

    const REDACTEUR_NB_DOCUMENTS = 3;
    const REDACTEUR_NB_NOTEPOS = 3;
    const AUTEUR_NB_DOCUMENTS = 5;
    const AUTEUR_NB_NOTEPOS = 5;
    const ECRIVAIN_NB_DOCUMENTS = 10;
    const ECRIVAIN_NB_NOTEPOS = 10;

    const ECLAIREUR_NB_DEBATES = 3;
    const AVANT_GARDE_NB_DEBATES = 10;
    const GUIDE_NB_DEBATES = 50;

    const ANNOTATEUR_NB_COMMENTS = 5;
    const GLOSSATEUR_NB_COMMENTS = 20;
    const COMMENTATEUR_NB_COMMENTS = 50;

    const EFFRONTE_NOTEPOS = 5;
    const IMPERTINENT_NOTEPOS = 20;
    const AUDACIEUX_NOTEPOS = 50;

    const TAGUEUR_NB_TAGS = 50;
    const SURVEILLANT_NB_MODERATIONS = 50;
    
    const FOUGUEUX_NB_NOTEPOS = 25;
    const ENTHOUSIASTE_NB_NOTEPOS = 100;
    const PASSIONNE_NB_NOTEPOS = 250;

    const PERSIFLEUR_NB_NOTENEG = 25;
    const REPROBATEUR_NB_NOTENEG = 100;
    const CRITIQUE_NB_NOTENEG = 250;

    const ATTENTIF_NB_DAYS = 5;
    const ASSIDU_NB_DAYS = 15;
    const FIDELE_NB_DAYS = 30;

    const SUIVEUR_NB_SUBSCRIBES = 5;
    const DISCIPLE_NB_SUBSCRIBES = 20;
    const INCONDITIONNEL_NB_SUBSCRIBES = 50;

    const IMPORTANT_NB_FOLLOWERS = 5;
    const INFLUENT_NB_FOLLOWERS = 20;
    const INCONTOURNABLE_NB_FOLLOWERS = 50;

    const PORTE_VOIX_NB_SHARE = 5;
    const FAN_NB_SHARE = 20;
    const AMBASSADEUR_NB_SHARE = 50;


    // ******************************************************** //
    //                  Useful static methods
    // ******************************************************** //

    /**
     * Return "positives" actions: publication, note+, follow
     *
     * @return array
     */
    public static function getPositivesPRActionsId()
    {
        return [
            ReputationConstants::ACTION_ID_D_DEBATE_PUBLISH,
            ReputationConstants::ACTION_ID_D_REACTION_PUBLISH,
            ReputationConstants::ACTION_ID_D_COMMENT_PUBLISH,
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW,
            ReputationConstants::ACTION_ID_U_AUTHOR_USER_FOLLOW,
        ];
    }

    /**
     * Return notation ids
     *
     * @return array
     */
    public static function getNotationPRActionsId()
    {
        return [
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG,
            ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG,
            ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG,
        ];
    }

    /**
     * Return "author" timeline ids
     *
     * @return array
     */
    public static function getTimelineAuthorReputationIds()
    {
        return [
            // ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS,
            // ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG,
            // ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS,
            // ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG,
            // ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS,
            // ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG,
            ReputationConstants::ACTION_ID_U_AUTHOR_USER_FOLLOW,
            ReputationConstants::ACTION_ID_U_AUTHOR_USER_UNFOLLOW,
            ReputationConstants::ACTION_ID_U_TARGET_USER_FOLLOW,
            // ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW,
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_UNFOLLOW,
        ];
    }

    /**
     * Return "target debate" timeline ids
     *
     * @return array
     */
    public static function getTimelineTargetDebateReputationIds()
    {
        return [
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_NEG,
            // ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_FOLLOW,
        ];
    }

    /**
     * Return "target reaction" timeline ids
     *
     * @return array
     */
    public static function getTimelineTargetReactionReputationIds()
    {
        return [
            ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_NEG,
        ];
    }

    /**
     * Return "target comment" timeline ids
     *
     * @return array
     */
    public static function getTimelineTargetCommentReputationIds()
    {
        return [
            ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_NEG,
        ];
    }

    /**
     * Return "following" timeline ids
     *
     * @return array
     */
    public static function getTimelineFollowingReputationIds()
    {
        return [
            ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS,
            ReputationConstants::ACTION_ID_D_AUTHOR_COMMENT_NOTE_POS,
            ReputationConstants::ACTION_ID_U_AUTHOR_USER_FOLLOW,
        ];
    }
}
