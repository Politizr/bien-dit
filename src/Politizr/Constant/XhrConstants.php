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

    // GENERAL
    const ROUTE_SHOW_HIDE_SUGGESTION = 'profil/suggestion';

    // FOLLOW
    const ROUTE_FOLLOW_DEBATE = 'profil/suivre/debat';
    const ROUTE_FOLLOW_USER = 'profil/suivre/utilisateur';
    const ROUTE_FOLLOW_TAG = 'profil/suivre/thematique';
    const ROUTE_FOLLOW_RELATIVE_DEBATE = 'profil/suivre/debat/relatif';

    // COMMENTS
    const ROUTE_COMMENTS = 'commentaires';
    const ROUTE_COMMENT_CREATE = 'profil/commentaire/nouveau';

    // DOCUMENTS
    const ROUTE_DOCUMENT_PHOTO_UPLOAD = 'profil/document/photo/upload';
    const ROUTE_DOCUMENT_PHOTO_DELETE = 'profil/document/photo/delete';

    const ROUTE_DOCUMENT_LISTING_MY_DRAFTS = 'profil/mes-documents/brouillons';
    const ROUTE_DOCUMENT_LISTING_MY_BOOKMARKS = 'profil/mes-favoris';
    const ROUTE_DOCUMENT_LISTING_USER_PUBLICATIONS = 'profil/publications';

    const ROUTE_DOCUMENT_LISTING_TOP = 'documents/top';
    const ROUTE_DOCUMENT_LISTING_SUGGESTION = '/espace/profil/documents/suggestions';

    const ROUTE_DOCUMENT_LISTING_RECOMMEND = 'documents/recommend';
    const ROUTE_DOCUMENT_LISTING_RECOMMEND_NAV = 'documents/recommend/nav';

    const ROUTE_DOCUMENT_LISTING_FILTERS = 'documents/filters';
    const ROUTE_USER_LISTING_FILTERS = 'utilisateurs/filters';
    const ROUTE_LISTING_FILTERS_CATEGORY = 'filters/category';
    const ROUTE_MAP_MENU = 'carte/menu';
    const ROUTE_MAP_BREADCRUMB = 'carte/breadcrumb';
    const ROUTE_MAP_SCHEMA = 'carte/schema';

    const ROUTE_DOCUMENT_BOOKMARK = 'profil/document/bookmark';

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
    const ROUTE_MODAL_HELP_US = 'politizr-needs-you';
    const ROUTE_MODAL_CREATE_ACCOUNT_TO_COMMNET = 'commentaires/creer-un-compte';
    const ROUTE_MODAL_CGU = 'conditions-generales-d-utilisation';
    const ROUTE_MODAL_CGV = 'conditions-generales-de-vente';
    const ROUTE_MODAL_CHARTE = 'charte-politizr';

    // OP
    const ROUTE_OP_CLOSE = 'op/close';

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
    const ROUTE_TAG_LISTING_TYPE = 'tags/type';
    const ROUTE_TAG_LISTING_USER = 'profil/tags/user';

    // ORGANIZATION
    const ROUTE_ORGANIZATION_DOCUMENT_TABS = 'organisation/documents/tabs';
    const ROUTE_ORGANIZATION_USER_TABS = 'organisation/profils/tabs';
    const ROUTE_ORGANIZATION_LISTING = 'organisation/listing';
    const ROUTE_ORGANIZATION_DOCUMENT_LISTING = 'organisation/documents/listing';
    const ROUTE_ORGANIZATION_USER_LISTING = 'organisation/profils/listing';

    // USER
    const ROUTE_USER_MANDATE_CREATE = 'profil/utilisateur/mandat/create';
    const ROUTE_USER_MANDATE_UPDATE = 'profil/utilisateur/mandat/update';
    const ROUTE_USER_MANDATE_DELETE = 'profil/utilisateur/mandat/delete';
    const ROUTE_USER_PROFILE_UPDATE = 'profil/utilisateur/update';
    const ROUTE_USER_ORGA_UPDATE = 'profil/utilisateur/orga/update';
    const ROUTE_USER_AFFINITIES_UPDATE = 'profil/utilisateur/affinitees/update';
    const ROUTE_USER_PERSO_UPDATE = 'profil/utilisateur/perso/update';

    const ROUTE_USER_VALIDATE_ID = 'utilisateur/validation/carte-identite';
    const ROUTE_USER_VALIDATE_PHOTO_UPLOAD = 'utilisateur/validation/photo-id-upload';
    
    const ROUTE_USER_PHOTO_UPLOAD = 'profil/photo/upload';
    const ROUTE_USER_PHOTO_DELETE = 'profil/photo/delete';

    const ROUTE_USER_LISTING_LAST_DEBATE_FOLLOWERS = 'debat/derniers-abonnes';
    const ROUTE_USER_LISTING_DEBATE_FOLLOWERS = 'debat/abonnes';

    const ROUTE_USER_LISTING_LAST_USER_FOLLOWERS = 'auteur/derniers-abonnes';
    const ROUTE_USER_LISTING_LAST_USER_SUBSCRIBERS = 'auteur/derniers-abonnements';

    const ROUTE_USER_LISTING_USER_FOLLOWERS = 'auteur/abonnes';
    const ROUTE_USER_LISTING_USER_SUBSCRIBERS = 'auteur/abonnements';

    const ROUTE_USER_DETAIL_CONTENT = 'auteur/content/detail';
    const ROUTE_USER_LISTING_USER_FOLLOWERS_CONTENT = 'auteur/content/derniers-abonnes';
    const ROUTE_USER_LISTING_USER_SUBSCRIBERS_CONTENT = 'auteur/content/derniers-abonnements';

    const ROUTE_USER_LISTING_BADGES = 'auteur/badges';

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

    // LOCALIZATION
    const ROUTE_CITY_LISTING = 'villes/listing';

    // PUBLIC
    const ROUTE_PUBLIC_DIRECT_MESSAGE = 'direct/message';

    // ******************************************************** //
    //                  JS FUNCTION KEYS
    // ******************************************************** //
    const JS_KEY_LISTING_DOCUMENTS_BY_TAG = "JS_KEY_LISTING_DOCUMENTS_BY_TAG";
    const JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION = "JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION";
    const JS_KEY_LISTING_USERS_BY_ORGANIZATION = "JS_KEY_LISTING_USERS_BY_ORGANIZATION";
    const JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND = "JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND";
    const JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS = "JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS";
    const JS_KEY_LISTING_DOCUMENTS_BY_USER_BOOKMARKS = "JS_KEY_LISTING_DOCUMENTS_BY_USER_BOOKMARKS";
    const JS_KEY_LISTING_PUBLICATIONS_BY_USER_PUBLICATIONS = "JS_KEY_LISTING_PUBLICATIONS_BY_USER_PUBLICATIONS";
    const JS_KEY_LISTING_PUBLICATIONS_BY_FILTERS = "JS_KEY_LISTING_PUBLICATIONS_BY_FILTERS";
    const JS_KEY_LISTING_USERS_BY_FILTERS = "JS_KEY_LISTING_USERS_BY_FILTERS";

    const JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS = "JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS";
    const JS_KEY_LISTING_USERS_USER_FOLLOWERS = "JS_KEY_LISTING_USERS_USER_FOLLOWERS";
    const JS_KEY_LISTING_USERS_USER_SUBSCRIBERS = "JS_KEY_LISTING_USERS_USER_SUBSCRIBERS";


    // ******************************************************** //
    //                  XHR URL REWRITING (ADMIN)
    // ******************************************************** //

    // DASHBOARD
    const ADMIN_ROUTE_NOTIF_CREATE = 'admin/notification/create';

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

    // ID CHECK
    const ADMIN_ROUTE_USER_VALIDATE_ID = 'admin/utilisateur/validation/carte-identite';
    const ADMIN_ROUTE_USER_VALIDATE_PHOTO_UPLOAD = 'admin/validation/photo-id-upload';

    // LOCALIZATION
    const ADMIN_ROUTE_CITY_LISTING = 'admin/villes/listing';
    const ADMIN_ROUTE_USER_CITY = 'admin/utilisateur/ville/update';
}
