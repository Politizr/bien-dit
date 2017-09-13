// beta
// ******************************************************** //
//                  XHR RETURN RESPONSE TYPE
// ******************************************************** //
var RETURN_BOOLEAN = 1;
var RETURN_HTML = 2;
var RETURN_URL = 3;

// ******************************************************** //
//                  XHR URL REWRITING
// ******************************************************** //

// GENERAL
var ROUTE_SHOW_HIDE_SUGGESTION = '$w/suggestion';

// FOLLOW
var ROUTE_FOLLOW_DEBATE = '$w/suivre/debat';
var ROUTE_FOLLOW_USER = '$w/suivre/utilisateur';
var ROUTE_FOLLOW_TAG = '$w/suivre/thematique';
var ROUTE_FOLLOW_RELATIVE_DEBATE = '$w/suivre/debat/relatif';

// COMMENTS
var ROUTE_COMMENTS = 'commentaires';
var ROUTE_COMMENT_CREATE = '$w/commentaire/nouveau';

// DOCUMENTS
var ROUTE_DOCUMENT_PHOTO_UPLOAD = '$w/document/photo/upload';
var ROUTE_DOCUMENT_PHOTO_DELETE = '$w/document/photo/delete';

var ROUTE_DOCUMENT_LISTING_MY_DRAFTS = '$w/mes-documents/brouillons';
var ROUTE_DOCUMENT_LISTING_MY_BOOKMARKS = '$w/mes-favoris';
var ROUTE_DOCUMENT_LISTING_USER_PUBLICATIONS = '$w/publications';

var ROUTE_DOCUMENT_LISTING_TOP = 'documents/top';
var ROUTE_DOCUMENT_LISTING_SUGGESTION = '/$w/documents/suggestions';

var ROUTE_DOCUMENT_LISTING_RECOMMEND = 'documents/recommend';
var ROUTE_DOCUMENT_LISTING_RECOMMEND_NAV = 'documents/recommend/nav';

var ROUTE_DOCUMENT_LISTING_FILTERS = 'documents/filters';
var ROUTE_USER_LISTING_FILTERS = 'utilisateurs/filters';
var ROUTE_LISTING_FILTERS_CATEGORY = 'filters/category';
var ROUTE_MAP_MENU = 'carte/menu';
var ROUTE_MAP_BREADCRUMB = 'carte/breadcrumb';
var ROUTE_MAP_SCHEMA = 'carte/schema';

var ROUTE_DOCUMENT_BOOKMARK = '$w/document/bookmark';

var ROUTE_FB_COMMENTS = 'fb/commentaires';
var ROUTE_FB_INSIGHTS= 'fb/insights';

// DEBATES
var ROUTE_DEBATE_UPDATE = '$w/debat/update';
var ROUTE_DEBATE_PUBLISH = '$w/debat/publier';
var ROUTE_DEBATE_DELETE = '$w/debat/supprimer';
var ROUTE_DEBATE_PHOTO_INFO_UPDATE = '$w/debat/infos/photo/update';
var ROUTE_DEBATE_DOC_TAGS = '$w/debate/tags/update';

// REACTIONS
var ROUTE_REACTION_UPDATE = '$w/reaction/update';
var ROUTE_REACTION_PUBLISH = '$w/reaction/publier';
var ROUTE_REACTION_DELETE = '$w/reaction/supprimer';
var ROUTE_REACTION_PHOTO_INFO_UPDATE = '$w/reaction/infos/photo/update';
var ROUTE_REACTION_DOC_TAGS = '$w/reaction/tags/update';

var ROUTE_REACTION_LISTING_DEBATE_FIRST_CHILD = 'reactions/debat';

// NOTIFICATIONS
var ROUTE_NOTIF_LOADING = '$w/notif/chargement';
var ROUTE_NOTIF_CHECK = '$w/notif/marque-vu';
var ROUTE_NOTIF_CHECK_ALL = '$w/notif/tout-marque-vu';
var ROUTE_NOTIF_EMAIL_SUBSCRIBE = '$w/notif/email/activer';
var ROUTE_NOTIF_EMAIL_UNSUBSCRIBE = '$w/notif/email/desactiver';
var ROUTE_NOTIF_CONTEXT_DEBATE_SUBSCRIBE = '$w/notif/debat/contexte/activer';
var ROUTE_NOTIF_CONTEXT_DEBATE_UNSUBSCRIBE = '$w/notif/debat/contexte/desactiver';
var ROUTE_NOTIF_CONTEXT_USER_SUBSCRIBE = '$w/notif/utilisateur/contexte/activer';
var ROUTE_NOTIF_CONTEXT_USER_UNSUBSCRIBE = '$w/notif/utilisateur/contexte/desactiver';

// REPUTATION COUNTER
var ROUTE_SCORE_COUNTER = '$w/reputation/score';
var ROUTE_BADGES_COUNTER = '$w/reputation/badges';

// MODAL
var ROUTE_MODAL_HELP_US = 'politizr-needs-you';
var ROUTE_MODAL_CREATE_ACCOUNT_TO_COMMNET = 'commentaires/creer-un-compte';
var ROUTE_MODAL_CGU = 'conditions-generales-d-utilisation';
var ROUTE_MODAL_CGV = 'conditions-generales-de-vente';
var ROUTE_MODAL_CHARTE = 'charte-politizr';

// OP
var ROUTE_OP_CLOSE = 'op/close';

// NOTATION
var ROUTE_NOTE = '$w/noter';

// SECURITY
var ROUTE_SECURITY_LOGIN = 'login';
var ROUTE_SECURITY_LOST_PASSWORD_CHECK = 'password/init';
var ROUTE_SECURITY_PAYMENT_PROCESS = '$v/paiement';

// TAGS
var ROUTE_TAG_DEBATE_CREATE = '$w/debat/tag/create';
var ROUTE_TAG_DEBATE_DELETE = '$w/debat/tag/delete';
var ROUTE_TAG_REACTION_CREATE = '$w/reaction/tag/create';
var ROUTE_TAG_REACTION_DELETE = '$w/reaction/tag/delete';
var ROUTE_TAG_USER_CREATE = '$w/utilisateur/tag/create';
var ROUTE_TAG_USER_DELETE = '$w/utilisateur/tag/delete';
var ROUTE_TAG_USER_HIDE = '$w/utilisateur/tag/hide';
var ROUTE_TAG_USER_ASSOCIATE = '$w/utilisateur/tag/associate';

var ROUTE_TAG_LISTING_TOP = 'tags/top';
var ROUTE_TAG_LISTING_TYPE = 'tags/type';
var ROUTE_TAG_LISTING_FAMILY = 'tags/family';
var ROUTE_TAG_LISTING_USER = '$w/tags/user';
var ROUTE_TAG_LISTING = 'tags/listing';

// ORGANIZATION
var ROUTE_ORGANIZATION_DOCUMENT_TABS = 'organisation/documents/tabs';
var ROUTE_ORGANIZATION_USER_TABS = 'organisation/profils/tabs';
var ROUTE_ORGANIZATION_LISTING = 'organisation/listing';
var ROUTE_ORGANIZATION_DOCUMENT_LISTING = 'organisation/documents/listing';
var ROUTE_ORGANIZATION_USER_LISTING = 'organisation/profils/listing';

// USER
var ROUTE_USER_MANDATE_CREATE = '$w/utilisateur/mandat/create';
var ROUTE_USER_MANDATE_UPDATE = '$w/utilisateur/mandat/update';
var ROUTE_USER_MANDATE_DELETE = '$w/utilisateur/mandat/delete';
var ROUTE_USER_PROFILE_UPDATE = '$w/utilisateur/update';
var ROUTE_USER_ORGA_UPDATE = '$w/utilisateur/orga/update';
var ROUTE_USER_AFFINITIES_UPDATE = '$w/utilisateur/affinitees/update';
var ROUTE_USER_PERSO_UPDATE = '$w/utilisateur/perso/update';

var ROUTE_USER_VALIDATE_ID = 'utilisateur/validation/carte-identite';
var ROUTE_USER_VALIDATE_PHOTO_UPLOAD = 'utilisateur/validation/photo-id-upload';

var ROUTE_USER_PHOTO_UPLOAD = '$w/photo/upload';
var ROUTE_USER_PHOTO_DELETE = '$w/photo/delete';


var ROUTE_USER_LISTING_LAST_DEBATE_FOLLOWERS = 'debat/derniers-abonnes';
var ROUTE_USER_LISTING_DEBATE_FOLLOWERS = 'debat/abonnes';

var ROUTE_USER_LISTING_LAST_USER_FOLLOWERS = 'auteur/derniers-abonnes';
var ROUTE_USER_LISTING_LAST_USER_SUBSCRIBERS = 'auteur/derniers-abonnements';

var ROUTE_USER_LISTING_USER_FOLLOWERS = 'auteur/abonnes';
var ROUTE_USER_LISTING_USER_SUBSCRIBERS = 'auteur/abonnements';

var ROUTE_USER_DETAIL_CONTENT = 'auteur/content/detail';
var ROUTE_USER_LISTING_USER_FOLLOWERS_CONTENT = 'auteur/content/derniers-abonnes';
var ROUTE_USER_LISTING_USER_SUBSCRIBERS_CONTENT = 'auteur/content/derniers-abonnements';

var ROUTE_USER_LISTING_BADGES = 'auteur/badges';

// TIMELINE
var ROUTE_TIMELINE_MINE = '$w/timeline';
var ROUTE_TIMELINE_USER = 'user/timeline';

// MONITORING
var ROUTE_MONITORING_ABUSE = '$w/signaler-un-abus';
var ROUTE_MONITORING_ABUSE_CHECK = '$w/signaler-un-abus/check';
var ROUTE_MONITORING_ASK_FOR_UPDATE = '$w/modifier-mes-donnees';
var ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK = '$w/modifier-mes-donnees/check';

// BUBBLES
var ROUTE_BUBBLE_USER = 'bubble/user';
var ROUTE_BUBBLE_TAG = 'bubble/tag';

// LOCALIZATION
var ROUTE_CITY_LISTING = 'villes/listing';

// PUBLIC
var ROUTE_PUBLIC_DIRECT_MESSAGE = 'direct/message';


// ******************************************************** //
//                  JS FUNCTION KEYS
// ******************************************************** //
var JS_KEY_LISTING_DOCUMENTS_BY_TAG = "JS_KEY_LISTING_DOCUMENTS_BY_TAG";
var JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION = "JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION";
var JS_KEY_LISTING_USERS_BY_ORGANIZATION = "JS_KEY_LISTING_USERS_BY_ORGANIZATION";
var JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND = "JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND";
var JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS = "JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS";
var JS_KEY_LISTING_DOCUMENTS_BY_USER_BOOKMARKS = "JS_KEY_LISTING_DOCUMENTS_BY_USER_BOOKMARKS";
var JS_KEY_LISTING_PUBLICATIONS_BY_USER_PUBLICATIONS = "JS_KEY_LISTING_PUBLICATIONS_BY_USER_PUBLICATIONS";
var JS_KEY_LISTING_PUBLICATIONS_BY_FILTERS = "JS_KEY_LISTING_PUBLICATIONS_BY_FILTERS";
var JS_KEY_LISTING_USERS_BY_FILTERS = "JS_KEY_LISTING_USERS_BY_FILTERS";

var JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS = "JS_KEY_LISTING_USERS_DEBATE_FOLLOWERS";
var JS_KEY_LISTING_USERS_USER_FOLLOWERS = "JS_KEY_LISTING_USERS_USER_FOLLOWERS";
var JS_KEY_LISTING_USERS_USER_SUBSCRIBERS = "JS_KEY_LISTING_USERS_USER_SUBSCRIBERS";

var JS_KEY_LISTING_DOCUMENTS_BY_TOPIC = "JS_KEY_LISTING_DOCUMENTS_BY_TOPIC";


// Generic function to make an AJAX call
var xhrCall = function(context, data, url, localLoader, type) {
    type = (typeof type === "undefined") ? 'GET' : type;

    // Return the $.ajax promise
    return $.ajax({
        type: type,
        data: data,
        dataType: 'json',
        url: url,
        context: context,
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },        
    });
}


// ******************************************************** //
//                  XHR URL REWRITING (ADMIN)
// ******************************************************** //

/**
 *
 */
function getXhrPath( xhrRoute, xhrService, xhrMethod, xhrType ) {
    var routeXhrGeneric = baseUrl + '/xhr/%xhrRoute%/%xhrService%/%xhrMethod%.%xhrType%.json';

    routeXhrGeneric = routeXhrGeneric.replace('%xhrRoute%', xhrRoute);
    routeXhrGeneric = routeXhrGeneric.replace('%xhrService%', xhrService);
    routeXhrGeneric = routeXhrGeneric.replace('%xhrMethod%', xhrMethod);
    routeXhrGeneric = routeXhrGeneric.replace('%xhrType%', xhrType);

    return routeXhrGeneric;
}


/**
 *
 * @param xhr
 * @param int mode 1 => global loader, (element) => local loader, undefined => no loader
 */
function xhrBeforeSend( xhr, mode ) {
    $('#infoBoxHolder .boxSuccess').hide();
    $('#infoBoxHolder .boxError').hide();
    $('#infoBoxHolder.boxAlert').hide();

    mode = (typeof mode === "undefined") ? null : mode;
    if (1 == mode) {
        $('#ajaxGlobalLoader').show();
    } else if (mode) {
        mode.show();
    }
}

/**
 *
 * @param int mode 1 => global loader, 2 => local loader, 3 => no loader
 */
function xhr404( localLoader ) {
    localLoader = (typeof localLoader === "undefined") ? null : localLoader;
    $('#ajaxGlobalLoader').hide();
    if (localLoader) {
        localLoader.hide();
    }
    $('#infoBoxHolder .boxError .notifBoxText').html('Erreur 404: cette action n\'existe pas.');
    $('#infoBoxHolder .boxError').show();
}

/**
 *
 * @param int mode 1 => global loader, 2 => local loader, 3 => no loader
 */
function xhr500( localLoader ) {
    localLoader = (typeof localLoader === "undefined") ? null : localLoader;
    $('#ajaxGlobalLoader').hide();
    if (localLoader) {
        localLoader.hide();
    }
    $('#infoBoxHolder .boxError .notifBoxText').html('Erreur 500: merci de recharger la page.');
    $('#infoBoxHolder .boxError').show();
}

/**
 *
 */
function xhrError(jqXHR, textStatus, errorThrown, localLoader ) {
    localLoader = (typeof localLoader === "undefined") ? null : localLoader;
    $('#ajaxGlobalLoader').hide();
    if (localLoader) {
        localLoader.hide();
    }
    $('#ajaxGlobalLoader').hide();
    // $('#infoBoxHolder .boxError .notifBoxText').html('Erreur inconnue: merci de recharger la page.');
    // $('#infoBoxHolder .boxError').show();
    console.log(textStatus);
    console.log(errorThrown);
}