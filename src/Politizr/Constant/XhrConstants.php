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
    const ROUTE_FOLLOW_TAG = 'profil/suivre/thematique';

    // COMMENTS
    const ROUTE_COMMENTS = 'commentaires';
    const ROUTE_COMMENT_CREATE = 'profil/commentaire/nouveau';

    // DOCUMENTS
    const ROUTE_DOCUMENT_PHOTO_UPLOAD = 'profil/photo/upload';
    const ROUTE_DOCUMENT_LISTING_MY_DRAFTS = 'profil/mes-documents/brouillons';
    const ROUTE_DOCUMENT_LISTING_MY_PUBLICATIONS = 'profil/mes-documents/publications';

    const ROUTE_DOCUMENT_LISTING_TOP = 'documents/top';
    const ROUTE_DOCUMENT_LISTING_SUGGESTION = '/profil/documents/suggestions';

    const ROUTE_DOCUMENT_LISTING_RECOMMEND = 'documents/recommend';
    const ROUTE_DOCUMENT_LISTING_RECOMMEND_NAV = 'documents/recommend/nav';

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

    const ROUTE_REACTION_LISTING_DEBATE_FIRST_CHILD = 'reactions/debat';

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

    // REPUTATION COUNTER
    const ROUTE_SCORE_COUNTER = 'profil/reputation/score';
    const ROUTE_BADGES_COUNTER = 'profil/reputation/badges';

    // MODAL
    const ROUTE_MODAL_PAGINATED_LIST = 'liste';
    const ROUTE_MODAL_FILTERS = 'filtres';
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
    const ROUTE_MODAL_REPUTATION = 'profil/reputation';
    const ROUTE_MODAL_REPUTATION_EVOLUTION = 'profil/reputation/evolution';
    const ROUTE_MODAL_DOCUMENT_STATS = 'profil/document/stats';
    const ROUTE_MODAL_DOCUMENT_STATS_NOTES_EVOLUTION = 'profil/document/stats/notes/evolution';
    const ROUTE_MODAL_DOCUMENT_STATS_REACTIONS_EVOLUTION = 'profil/document/stats/reactions/evolution';
    const ROUTE_MODAL_DOCUMENT_STATS_COMMENTS_EVOLUTION = 'profil/document/stats/comments/evolution';

    // DASHBOARD
    const ROUTE_DASHBOARD_MAP = 'profil/tableau/carte';
    const ROUTE_DASHBOARD_TOP_DEBATES = 'profil/tableau/debats';
    const ROUTE_DASHBOARD_TOP_USERS = 'profil/tableau/debats';
    const ROUTE_DASHBOARD_GEO = 'profil/tableau/geo';

    // NOTATION
    const ROUTE_NOTE = 'profil/noter';

    // SECURITY
    const ROUTE_SECURITY_LOGIN = 'login';
    const ROUTE_SECURITY_LOST_PASSWORD_CHECK = 'password/init';
    const ROUTE_SECURITY_PAYMENT_PROCESS = 'v/paiement';

    // TAGS
    const ROUTE_TAG_DEBATE_CREATE = 'profil/debat/tag/create';
    const ROUTE_TAG_DEBATE_DELETE = 'profil/debat/tag/delete';
    const ROUTE_TAG_REACTION_CREATE = 'profil/reaction/tag/create';
    const ROUTE_TAG_REACTION_DELETE = 'profil/reaction/tag/delete';
    const ROUTE_TAG_USER_CREATE = 'profil/utilisateur/tag/create';
    const ROUTE_TAG_USER_DELETE = 'profil/utilisateur/tag/delete';
    const ROUTE_TAG_USER_HIDE = 'profil/utilisateur/tag/hide';
    const ROUTE_TAG_USER_ASSOCIATE = 'profil/utilisateur/tag/associate';

    const ROUTE_TAG_LISTING = 'tags/listing';
    const ROUTE_TAG_LISTING_TOP = 'tags/top';
    const ROUTE_TAG_LISTING_USER = 'profil/tags/user';

    // ORGANIZATION
    const ROUTE_ORGANIZATION_LISTING = 'organisation/listing';

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

    const ROUTE_USER_LISTING_LAST_DEBATE_FOLLOWERS = 'profils/debat/derniers-abonnes';
    const ROUTE_USER_LISTING_DEBATE_FOLLOWERS = 'profils/debat/abonnes';

    // TIMELINE
    const ROUTE_TIMELINE_MINE = 'profil/timeline';
    const ROUTE_TIMELINE_USER = 'user/timeline';

    // MONITORING
    const ROUTE_MONITORING_ABUSE = 'profil/signaler-un-abus';
    const ROUTE_MONITORING_ABUSE_CHECK = 'profil/signaler-un-abus/check';
    const ROUTE_MONITORING_ASK_FOR_UPDATE = 'profil/modifier-mes-donnees';
    const ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK = 'profil/modifier-mes-donnees/check';

    // BUBBLES
    const ROUTE_BUBBLE_USER = 'bubble/user';
    const ROUTE_BUBBLE_TAG = 'bubble/tag';

    // ******************************************************** //
    //                  JS FUNCTION KEYS
    // ******************************************************** //
    const JS_KEY_LISTING_DOCUMENTS_BY_TAG = "JS_KEY_LISTING_DOCUMENTS_BY_TAG";
    const JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION = "JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION";
    const JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND = "JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND";
    const JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS = "JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS";

    const JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS = "JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS";


    // ******************************************************** //
    //                  XHR URL REWRITING (ADMIN)
    // ******************************************************** //

    // UPLOAD
    const ADMIN_ROUTE_UPLOAD_IMAGE = 'admin/upload/image';
    const ADMIN_ROUTE_DELETE_IMAGE = 'admin/delete/image';

    // TAGS
    const ADMIN_ROUTE_TAG_LISTING = 'admin/tags/listing';
    const ADMIN_ROUTE_TAG_DEBATE_CREATE = 'admin/debat/tag/create';
    const ADMIN_ROUTE_TAG_DEBATE_DELETE = 'admin/debat/tag/delete';
    const ADMIN_ROUTE_TAG_REACTION_CREATE = 'admin/reaction/tag/create';
    const ADMIN_ROUTE_TAG_REACTION_DELETE = 'admin/reaction/tag/delete';
    const ADMIN_ROUTE_TAG_USER_CREATE = 'admin/utilisateur/tag/create';
    const ADMIN_ROUTE_TAG_USER_DELETE = 'admin/utilisateur/tag/delete';
    const ADMIN_ROUTE_TAG_USER_HIDE = 'admin/utilisateur/tag/hide';

    // REPUTATION
    const ADMIN_ROUTE_USER_REPUTATION_EVOLUTION = 'admin/utilisateur/reputation/update';

    // MODERATION
    const ADMIN_ROUTE_USER_MODERATION_ALERT_NEW = 'admin/utilisateur/moderation/alert/new';
    const ADMIN_ROUTE_USER_MODERATION_BANNED_EMAIL = 'admin/utilisateur/moderation/email/banned';
}
