<?php

namespace Politizr\Constant;

/**
 * XHR constants
 *
 * @author Lionel Bouzonville
 */
class XhrConstants
{
    // XHR RETURN RESPONSE TYPE
    const RETURN_BOOLEAN = 1;
    const RETURN_HTML = 2;
    const RETURN_URL = 3;

    // ******************************************************** //
    //                  XHR URL REWRITING
    // ******************************************************** //

    // FOLLOW
    const ROUTE_FOLLOW_DEBATE = 'profil/suivre/debat';
    const ROUTE_FOLLOW_USER = 'profil/suivre/utilisateur';

    // COMMENTS
    const ROUTE_COMMENTS = 'commentaires';
    const ROUTE_COMMENT_CREATE = 'profil/commentaire/nouveau';

    // DEBATES + REACTIONS
    const ROUTE_DOCUMENT_PHOTO_UPLOAD = 'profil/photo/upload';

    // DEBATES
    const ROUTE_DEBATE_UPDATE = 'profil/debat/update';
    const ROUTE_DEBATE_PUBLISH = 'profil/debat/publier';
    const ROUTE_DEBATE_DELETE = 'profil/debat/supprimer';
    const ROUTE_DEBATE_PHOTO_INFO_UPDATE = 'profil/debat/infos/photo/update';

    // REACTIONS
    const ROUTE_REACTION_UPDATE = 'profil/reaction/update';
    const ROUTE_REACTION_PUBLISH = 'profil/reaction/publier';
    const ROUTE_REACTION_DELETE = 'profil/reaction/supprimer';
    const ROUTE_REACTION_PHOTO_INFO_UPDATE = 'profil/reaction/infos/photo/update';

    // NOTIFICATIONS
    const ROUTE_NOTIF_LOADING = 'profil/notif/chargement';
    const ROUTE_NOTIF_CHECK = 'profil/notif/marque-vu';
    const ROUTE_NOTIF_CHECK_ALL = 'profil/notif/tout-marque-vu';
    const ROUTE_NOTIF_EMAIL_SUBSCRIBE = 'profil/notif/email/activer';
    const ROUTE_NOTIF_EMAIL_UNSUBSCRIBE = 'profil/notif/email/desactiver';
    const ROUTE_NOTIF_CONTEXT_DEBATE_SUBSCRIBE = 'profil/notif/debat/contexte/activer';
    const ROUTE_NOTIF_CONTEXT_DEBATE_UNSUBSCRIBE = 'profil/notif/debat/contexte/desactiver';
    const ROUTE_NOTIF_CONTEXT_USER_SUBSCRIBE = 'profil/notif/utilisateur/contexte/activer';
    const ROUTE_NOTIF_CONTEXT_USER_UNSUBSCRIBE = 'profil/notif/utilisateur/contexte/desactiver';

    // MODAL
    const ROUTE_MODAL_PAGINATED_LIST = 'liste';
    const ROUTE_MODAL_FILTERS = 'filtres';
    const ROUTE_MODAL_LIST_ACTIONS = 'profil/liste/historique';
    const ROUTE_MODAL_RANKING_DEBATE_LIST = 'profil/liste/classement/debat';

    // NOTATION
    const ROUTE_NOTE = 'profil/noter';

    // RECHERCHE
    const ROUTE_SEARCH = 'recherche';

    // SECURITY
    const ROUTE_SECURITY_LOGIN = 'login';
    const ROUTE_SECURITY_LOGIN_CHECK = 'login/check';
    const ROUTE_SECURITY_LOST_PASSWORD_CHECK = 'password/init';
    const ROUTE_SECURITY_PAYMENT_PROCESS = 'v/paiement';

    // TAGS
    const ROUTE_TAG_LISTING = 'tags/listing';

    // USER
    const ROUTE_USER_TIMELINE = 'profil/timeline';
    const ROUTE_USER_MANDATE_CREATE = 'profil/utilisateur/mandat/create';
    const ROUTE_USER_PROFILE_UPDATE = 'profil/utilisateur/update';
    const ROUTE_USER_ORGA_UPDATE = 'profil/utilisateur/orga/update';
    const ROUTE_USER_AFFINITIES_UPDATE = 'profil/utilisateur/affinitees/update';
    const ROUTE_USER_MANDATE_UPDATE = 'profil/utilisateur/mandat/update';
    const ROUTE_USER_PERSO_UPDATE = 'profil/utilisateur/perso/update';
    const ROUTE_USER_BACK_PHOTO_DELETE = 'profil/backphoto/delete';
    const ROUTE_USER_PHOTO_DELETE = 'profil/photo/delete';
}
