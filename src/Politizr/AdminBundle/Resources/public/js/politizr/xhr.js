// ******************************************************** //
//                  XHR RETURN RESPONSE TYPE
// ******************************************************** //
var RETURN_BOOLEAN = 1;
var RETURN_HTML = 2;
var RETURN_URL = 3;

// ******************************************************** //
//                  XHR URL REWRITING (ADMIN)
// ******************************************************** //

// UPLOAD
var ADMIN_ROUTE_UPLOAD_IMAGE = 'admin/upload/image';
var ADMIN_ROUTE_DELETE_IMAGE = 'admin/delete/image';

// TAGS
var ADMIN_ROUTE_TAG_LISTING = 'admin/tags/listing';
var ADMIN_ROUTE_TAG_SEARCH_LISTING = 'admin/tags/search/listing';
var ADMIN_ROUTE_TAG_DEBATE_CREATE = 'admin/debat/tag/create';
var ADMIN_ROUTE_TAG_DEBATE_DELETE = 'admin/debat/tag/delete';
var ADMIN_ROUTE_TAG_USER_FOLLOW_CREATE = 'admin/utilisateur/follow/tag/create';
var ADMIN_ROUTE_TAG_USER_FOLLOW_DELETE = 'admin/utilisateur/follow/tag/delete';
var ADMIN_ROUTE_TAG_USER_TAGGED_CREATE = 'admin/utilisateur/tagged/tag/create';
var ADMIN_ROUTE_TAG_USER_TAGGED_DELETE = 'admin/utilisateur/tagged/tag/delete';

// REPUTATION
var ADMIN_ROUTE_USER_REPUTATION_EVOLUTION = 'admin/utilisateur/reputation/update';

/**
 *
 */
function getXhrPath( xhrRoute, xhrService, xhrMethod, xhrType ) {
    var routeXhrGeneric = baseUrl + '/adminxhr/%xhrRoute%/%xhrService%/%xhrMethod%.%xhrType%.json';

    routeXhrGeneric = routeXhrGeneric.replace('%xhrRoute%', xhrRoute);
    routeXhrGeneric = routeXhrGeneric.replace('%xhrService%', xhrService);
    routeXhrGeneric = routeXhrGeneric.replace('%xhrMethod%', xhrMethod);
    routeXhrGeneric = routeXhrGeneric.replace('%xhrType%', xhrType);

    return routeXhrGeneric;
}


/**
 *
 * @param xhr
 */
function xhrBeforeSend( xhr ) {
}

/**
 *
 */
function xhr404( ) {
    $('.alert-error').html('Erreur 404: cette action n\'existe pas.');
    $('.alert-error').show();
}

/**
 *
 */
function xhr500( ) {
    $('.alert-error').html('Erreur 500: merci de recharger la page.');
    $('.alert-error').show();
}

/**
 *
 */
function xhrError(jqXHR, textStatus, errorThrown ) {
    $('.alert-error').html('Erreur inconnue: merci de recharger la page.');
    $('.alert-error').show();
}