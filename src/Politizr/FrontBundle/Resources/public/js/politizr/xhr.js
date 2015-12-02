// ******************************************************** //
//                  XHR RETURN RESPONSE TYPE
// ******************************************************** //
var RETURN_BOOLEAN = 1;
var RETURN_HTML = 2;
var RETURN_URL = 3;

// ******************************************************** //
//                  XHR URL REWRITING
// ******************************************************** //

// FOLLOW
var ROUTE_FOLLOW_DEBATE = 'profil/suivre/debat';
var ROUTE_FOLLOW_USER = 'profil/suivre/utilisateur';

// COMMENTS
var ROUTE_COMMENTS = 'commentaires';
var ROUTE_COMMENT_CREATE = 'profil/commentaire/nouveau';

// DEBATES + REACTIONS
var ROUTE_DOCUMENT_PHOTO_UPLOAD = 'profil/photo/upload';
var ROUTE_DOCUMENT_DRAFTS = 'profil/contributions/mes-brouillons';
var ROUTE_DEBATE_MY_PUBLICATIONS = 'profil/contributions/mes-publications';

// DEBATES
var ROUTE_DEBATE_UPDATE = 'profil/debat/update';
var ROUTE_DEBATE_PUBLISH = 'profil/debat/publier';
var ROUTE_DEBATE_DELETE = 'profil/debat/supprimer';
var ROUTE_DEBATE_PHOTO_INFO_UPDATE = 'profil/debat/infos/photo/update';

// REACTIONS
var ROUTE_REACTION_UPDATE = 'profil/reaction/update';
var ROUTE_REACTION_PUBLISH = 'profil/reaction/publier';
var ROUTE_REACTION_DELETE = 'profil/reaction/supprimer';
var ROUTE_REACTION_PHOTO_INFO_UPDATE = 'profil/reaction/infos/photo/update';

// NOTIFICATIONS
var ROUTE_NOTIF_LOADING = 'profil/notif/chargement';
var ROUTE_NOTIF_CHECK = 'profil/notif/marque-vu';
var ROUTE_NOTIF_CHECK_ALL = 'profil/notif/tout-marque-vu';
var ROUTE_NOTIF_EMAIL_SUBSCRIBE = 'profil/notif/email/activer';
var ROUTE_NOTIF_EMAIL_UNSUBSCRIBE = 'profil/notif/email/desactiver';
var ROUTE_NOTIF_CONTEXT_DEBATE_SUBSCRIBE = 'profil/notif/debat/contexte/activer';
var ROUTE_NOTIF_CONTEXT_DEBATE_UNSUBSCRIBE = 'profil/notif/debat/contexte/desactiver';
var ROUTE_NOTIF_CONTEXT_USER_SUBSCRIBE = 'profil/notif/utilisateur/contexte/activer';
var ROUTE_NOTIF_CONTEXT_USER_UNSUBSCRIBE = 'profil/notif/utilisateur/contexte/desactiver';

// MODAL
var ROUTE_MODAL_PAGINATED_LIST = 'liste';
var ROUTE_MODAL_FILTERS = 'filtres';
var ROUTE_MODAL_SEARCH_INIT_FILTERS_LIST = 'filtres-liste/recherche';
var ROUTE_MODAL_LIST_ACTIONS = 'profil/liste/historique';
var ROUTE_MODAL_RANKING_DEBATE_LIST = 'profil/liste/classement/debat';
var ROUTE_MODAL_RANKING_REACTION_LIST = 'profil/liste/classement/reaction';
var ROUTE_MODAL_RANKING_USER_LIST = 'profil/liste/classement/utilisateur';
var ROUTE_MODAL_SUGGESTION_DEBATE_LIST = 'profil/liste/suggestion/debat';
var ROUTE_MODAL_SUGGESTION_REACTION_LIST = 'profil/liste/suggestion/reaction';
var ROUTE_MODAL_SUGGESTION_USER_LIST = 'profil/liste/suggestion/utilisateur';
var ROUTE_MODAL_TAG_DEBATE_LIST = 'profil/liste/tag/debat';
var ROUTE_MODAL_TAG_REACTION_LIST = 'profil/liste/tag/reaction';
var ROUTE_MODAL_TAG_USER_LIST = 'profil/liste/tag/utilisateur';
var ROUTE_MODAL_FOLLOWED_DEBATE_LIST = 'profil/liste/follow/debat';
var ROUTE_MODAL_FOLLOWED_USER_LIST = 'profil/liste/follow/utilisateur';
var ROUTE_MODAL_ORGANIZATION_USER_LIST = 'profil/liste/org/utilisateur';
var ROUTE_MODAL_FOLLOWERS_LIST = 'profil/abonnes';
var ROUTE_MODAL_SEARCH_DEBATE_BY_TAGS_LIST = 'search/liste/tags/debat';
var ROUTE_MODAL_SEARCH_REACTION_BY_TAGS_LIST = 'search/liste/tags/reaction';
var ROUTE_MODAL_SEARCH_USER_BY_TAGS_LIST = 'search/liste/tags/utilisateur';
var ROUTE_MODAL_REPUTATION = 'profil/ma-reputation';
var ROUTE_MODAL_REPUTATION_EVOLUTION = 'profil/ma-reputation/evolution';

// DASHBOARD
var ROUTE_DASHBOARD_MAP = 'profil/tableau/carte';
var ROUTE_DASHBOARD_TAG = 'profil/tableau/tags';
var ROUTE_DASHBOARD_TOP_DEBATES = 'profil/tableau/debats';
var ROUTE_DASHBOARD_TOP_USERS = 'profil/tableau/debats';
var ROUTE_DASHBOARD_GEO = 'profil/tableau/geo';
var ROUTE_DASHBOARD_SUGGESTION = 'profil/tableau/suggestion';

// NOTATION
var ROUTE_NOTE = 'profil/noter';

// RECHERCHE
var ROUTE_SEARCH = 'recherche';
var ROUTE_SEARCH_TAG_ADD = 'search/tag/add';
var ROUTE_SEARCH_TAG_DELETE = 'search/tag/delete';
var ROUTE_SEARCH_TAG_CLEAR_SESSION = 'search/tag/session/clear';

// SECURITY
var ROUTE_SECURITY_LOGIN = 'login';
var ROUTE_SECURITY_LOST_PASSWORD_CHECK = 'password/init';
var ROUTE_SECURITY_PAYMENT_PROCESS = 'v/paiement';

// TAGS
var ROUTE_TAG_LISTING = 'tags/listing';
var ROUTE_TAG_SEARCH_LISTING = 'tags/search/listing';
var ROUTE_TAG_DEBATE_CREATE = 'profil/debat/tag/create';
var ROUTE_TAG_DEBATE_DELETE = 'profil/debat/tag/delete';
var ROUTE_TAG_REACTION_CREATE = 'profil/reaction/tag/create';
var ROUTE_TAG_REACTION_DELETE = 'profil/reaction/tag/delete';
var ROUTE_TAG_USER_CREATE = 'profil/utilisateur/tag/create';
var ROUTE_TAG_USER_DELETE = 'profil/utilisateur/tag/delete';
var ROUTE_TAG_USER_HIDE = 'profil/utilisateur/tag/hide';
var ROUTE_TAG_USER_ASSOCIATE = 'profil/utilisateur/tag/associate';

// USER
var ROUTE_USER_MANDATE_CREATE = 'profil/utilisateur/mandat/create';
var ROUTE_USER_MANDATE_UPDATE = 'profil/utilisateur/mandat/update';
var ROUTE_USER_MANDATE_DELETE = 'profil/utilisateur/mandat/delete';
var ROUTE_USER_PROFILE_UPDATE = 'profil/utilisateur/update';
var ROUTE_USER_ORGA_UPDATE = 'profil/utilisateur/orga/update';
var ROUTE_USER_AFFINITIES_UPDATE = 'profil/utilisateur/affinitees/update';
var ROUTE_USER_PERSO_UPDATE = 'profil/utilisateur/perso/update';

var ROUTE_USER_BACK_PHOTO_INFO_UPDATE = 'profil/user/infos/photoback/update';
var ROUTE_USER_BACK_PHOTO_UPLOAD = 'profil/backphoto/upload';
var ROUTE_USER_PHOTO_UPLOAD = 'profil/photo/upload';
var ROUTE_USER_PHOTO_DELETE = 'profil/photo/delete';

// TIMELINE
var ROUTE_TIMELINE_MINE = 'profil/timeline';
var ROUTE_TIMELINE_USER = 'user/timeline';

// MONITORING
var ROUTE_MONITORING_ABUSE = 'profil/signaler-un-abus';
var ROUTE_MONITORING_ABUSE_CHECK = 'profil/signaler-un-abus/check';
var ROUTE_MONITORING_ASK_FOR_UPDATE = 'profil/modifier-mes-donnees';
var ROUTE_MONITORING_ASK_FOR_UPDATE_CHECK = 'profil/modifier-mes-donnees/check';

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
    $('#infoBoxHolder .boxError .notifBoxText').html('Erreur inconnue: merci de recharger la page.');
    $('#infoBoxHolder .boxError').show();
    // console.log('textStatus - '+textStatus);
    // console.log('errorThrown - '+errorThrown);
}