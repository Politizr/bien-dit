<?php

namespace Politizr\Constant;

/**
 * XHR constants
 * /!\ to sync w. js/politizr/xhr.js
 *
 * @author Lionel Bouzonville
 */
class XhrConstants
{
    // ******************************************************** //
    //                  XHR RETURN RESPONSE TYPE
    // ******************************************************** //

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
    const ROUTE_DOCUMENT_DRAFTS = 'profil/contributions/mes-brouillons';
    const ROUTE_DEBATE_MY_PUBLICATIONS = 'profil/contributions/mes-publications';

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
    const ROUTE_MODAL_SEARCH_INIT_FILTERS_LIST = 'filtres-liste/recherche';
    const ROUTE_MODAL_LIST_ACTIONS = 'profil/liste/historique';
    const ROUTE_MODAL_RANKING_DEBATE_LIST = 'profil/liste/classement/debat';
    const ROUTE_MODAL_RANKING_REACTION_LIST = 'profil/liste/classement/reaction';
    const ROUTE_MODAL_RANKING_USER_LIST = 'profil/liste/classement/utilisateur';
    const ROUTE_MODAL_SUGGESTION_DEBATE_LIST = 'profil/liste/suggestion/debat';
    const ROUTE_MODAL_SUGGESTION_REACTION_LIST = 'profil/liste/suggestion/reaction';
    const ROUTE_MODAL_SUGGESTION_USER_LIST = 'profil/liste/suggestion/utilisateur';
    const ROUTE_MODAL_TAG_DEBATE_LIST = 'profil/liste/tag/debat';
    const ROUTE_MODAL_TAG_REACTION_LIST = 'profil/liste/tag/reaction';
    const ROUTE_MODAL_TAG_USER_LIST = 'profil/liste/tag/utilisateur';
    const ROUTE_MODAL_FOLLOWED_DEBATE_LIST = 'profil/liste/follow/debat';
    const ROUTE_MODAL_FOLLOWED_USER_LIST = 'profil/liste/follow/utilisateur';
    const ROUTE_MODAL_ORGANIZATION_USER_LIST = 'profil/liste/org/utilisateur';
    const ROUTE_MODAL_FOLLOWERS_LIST = 'profil/abonnes';
    const ROUTE_MODAL_SEARCH_DEBATE_BY_TAGS_LIST = 'search/liste/tags/debat';
    const ROUTE_MODAL_SEARCH_REACTION_BY_TAGS_LIST = 'search/liste/tags/reaction';
    const ROUTE_MODAL_SEARCH_USER_BY_TAGS_LIST = 'search/liste/tags/utilisateur';
    const ROUTE_MODAL_REPUTATION = 'profil/ma-reputation';
    const ROUTE_MODAL_REPUTATION_EVOLUTION = 'profil/ma-reputation/evolution';

    // DASHBOARD
    const ROUTE_DASHBOARD_MAP = 'profil/carte';

    // NOTATION
    const ROUTE_NOTE = 'profil/noter';

    // RECHERCHE
    const ROUTE_SEARCH = 'recherche';
    const ROUTE_SEARCH_TAG_ADD = 'search/tag/add';
    const ROUTE_SEARCH_TAG_DELETE = 'search/tag/delete';
    const ROUTE_SEARCH_TAG_CLEAR_SESSION = 'search/tag/session/clear';

    // SECURITY
    const ROUTE_SECURITY_LOGIN = 'login';
    const ROUTE_SECURITY_LOST_PASSWORD_CHECK = 'password/init';
    const ROUTE_SECURITY_PAYMENT_PROCESS = 'v/paiement';

    // TAGS
    const ROUTE_TAG_LISTING = 'tags/listing';
    const ROUTE_TAG_SEARCH_LISTING = 'tags/search/listing';
    const ROUTE_TAG_DEBATE_CREATE = 'profil/debat/tag/create';
    const ROUTE_TAG_DEBATE_DELETE = 'profil/debat/tag/delete';
    const ROUTE_TAG_REACTION_CREATE = 'profil/reaction/tag/create';
    const ROUTE_TAG_REACTION_DELETE = 'profil/reaction/tag/delete';
    const ROUTE_TAG_USER_FOLLOW_CREATE = 'profil/utilisateur/follow/tag/create';
    const ROUTE_TAG_USER_FOLLOW_DELETE = 'profil/utilisateur/follow/tag/delete';
    const ROUTE_TAG_USER_TAGGED_CREATE = 'profil/utilisateur/tagged/tag/create';
    const ROUTE_TAG_USER_TAGGED_DELETE = 'profil/utilisateur/tagged/tag/delete';

    // USER
    const ROUTE_USER_MANDATE_CREATE = 'profil/utilisateur/mandat/create';
    const ROUTE_USER_MANDATE_UPDATE = 'profil/utilisateur/mandat/update';
    const ROUTE_USER_MANDATE_DELETE = 'profil/utilisateur/mandat/delete';
    const ROUTE_USER_PROFILE_UPDATE = 'profil/utilisateur/update';
    const ROUTE_USER_ORGA_UPDATE = 'profil/utilisateur/orga/update';
    const ROUTE_USER_AFFINITIES_UPDATE = 'profil/utilisateur/affinitees/update';
    const ROUTE_USER_PERSO_UPDATE = 'profil/utilisateur/perso/update';
    
    const ROUTE_USER_BACK_PHOTO_INFO_UPDATE = 'profil/user/infos/photoback/update';
    const ROUTE_USER_BACK_PHOTO_UPLOAD = 'profil/backphoto/upload';
    const ROUTE_USER_PHOTO_UPLOAD = 'profil/photo/upload';
    const ROUTE_USER_PHOTO_DELETE = 'profil/photo/delete';

    // TIMELINE
    const ROUTE_TIMELINE_MINE = 'profil/timeline';
    const ROUTE_TIMELINE_USER = 'user/timeline';

    // MONITORING
    const ROUTE_MONITORING_ABUSE = 'profil/signaler-un-abus';
    const ROUTE_MONITORING_ABUSE_CHECK = 'profil/signaler-un-abus/check';
    const ROUTE_MONITORING_ASK_FOR_UPDATE = 'profil/modifier-mes-donnees';
    const ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK = 'profil/modifier-mes-donnees/check';

    // ******************************************************** //
    //                  XHR URL REWRITING (ADMIN)
    // ******************************************************** //

    // UPLOAD
    const ADMIN_ROUTE_UPLOAD_IMAGE = 'admin/upload/image';
    const ADMIN_ROUTE_DELETE_IMAGE = 'admin/delete/image';

    // TAGS
    const ADMIN_ROUTE_TAG_LISTING = 'admin/tags/listing';
    const ADMIN_ROUTE_TAG_SEARCH_LISTING = 'admin/tags/search/listing';
    const ADMIN_ROUTE_TAG_DEBATE_CREATE = 'admin/debat/tag/create';
    const ADMIN_ROUTE_TAG_DEBATE_DELETE = 'admin/debat/tag/delete';
    const ADMIN_ROUTE_TAG_REACTION_CREATE = 'admin/reaction/tag/create';
    const ADMIN_ROUTE_TAG_REACTION_DELETE = 'admin/reaction/tag/delete';
    const ADMIN_ROUTE_TAG_USER_FOLLOW_CREATE = 'admin/utilisateur/follow/tag/create';
    const ADMIN_ROUTE_TAG_USER_FOLLOW_DELETE = 'admin/utilisateur/follow/tag/delete';
    const ADMIN_ROUTE_TAG_USER_TAGGED_CREATE = 'admin/utilisateur/tagged/tag/create';
    const ADMIN_ROUTE_TAG_USER_TAGGED_DELETE = 'admin/utilisateur/tagged/tag/delete';


    // REPUTATION
    const ADMIN_ROUTE_USER_REPUTATION_EVOLUTION = 'admin/utilisateur/reputation/update';

    // MODERATION
    const ADMIN_ROUTE_USER_MODERATION_ALERT_NEW = 'admin/utilisateur/moderation/alert/new';
    const ADMIN_ROUTE_USER_MODERATION_BANNED_EMAIL = 'admin/utilisateur/moderation/email/banned';
}
