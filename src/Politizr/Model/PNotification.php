<?php

namespace Politizr\Model;

use Politizr\Model\om\BasePNotification;

class PNotification extends BasePNotification
{

    // ******************************************************** //
    //            Constantes des notifications / BDD                //
    // ******************************************************** //


    // ************ DOCUMENTS ******* //
    const ID_D_COMMENT_PUBLISH = 1;
    const ID_D_NOTE_POS = 2;
    const ID_D_NOTE_NEG = 3;

    // ************ DÉBATS ******* //
    const ID_D_D_REACTION_PUBLISH = 4;
    const ID_D_D_FOLLOWED = 5;


    // ************ RÉACTIONS ******* //
       const ID_D_R_REACTION_PUBLISH = 6;

    // ************ COMMENTAIRES ******* //
    const ID_D_C_NOTE_POS = 7;
    const ID_D_C_NOTE_NEG = 8;


    // ************ USER ******* //
    const ID_U_FOLLOWED = 9;
    const ID_U_BADGE = 10;


    // ************ SUBSCRIBE ******* //
    const ID_S_U_DEBATE_PUBLISH = 11;
    const ID_S_U_REACTION_PUBLISH = 12;
    const ID_S_U_COMMENT_PUBLISH = 13;

    const ID_S_D_REACTION_PUBLISH = 14;
}
