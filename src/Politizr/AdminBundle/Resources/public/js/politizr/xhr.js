// ******************************************************** //
//                  XHR RETURN RESPONSE TYPE
// ******************************************************** //
var RETURN_BOOLEAN = 1;
var RETURN_HTML = 2;
var RETURN_URL = 3;

// ******************************************************** //
//                  XHR URL REWRITING (ADMIN)
// ******************************************************** //

// DASHBOARD
var ADMIN_ROUTE_NOTIF_CREATE = 'admin/notification/create';
var ADMIN_ROUTE_NOTIF_FILTER_USERS = 'admin/notification/filter/users';


// UPLOAD
var ADMIN_ROUTE_UPLOAD_IMAGE = 'admin/upload/image';
var ADMIN_ROUTE_DELETE_IMAGE = 'admin/delete/image';

// TAGS
var ADMIN_ROUTE_TAG_LISTING = 'admin/tags/listing';
var ADMIN_ROUTE_TAG_SEARCH_LISTING = 'admin/tags/search/listing';
var ADMIN_ROUTE_TAG_DEBATE_CREATE = 'admin/debat/tag/create';
var ADMIN_ROUTE_TAG_DEBATE_DELETE = 'admin/debat/tag/delete';
var ADMIN_ROUTE_TAG_REACTION_CREATE = 'admin/reaction/tag/create';
var ADMIN_ROUTE_TAG_REACTION_DELETE = 'admin/reaction/tag/delete';
var ADMIN_ROUTE_TAG_USER_CREATE = 'admin/utilisateur/tag/create';
var ADMIN_ROUTE_TAG_USER_DELETE = 'admin/utilisateur/tag/delete';
var ADMIN_ROUTE_TAG_USER_HIDE = 'admin/utilisateur/tag/hide';
var ADMIN_ROUTE_TAG_OPERATION_CREATE = 'admin/operation/tag/create';
var ADMIN_ROUTE_TAG_OPERATION_DELETE = 'admin/operation/tag/delete';

// REPUTATION
var ADMIN_ROUTE_USER_REPUTATION_EVOLUTION = 'admin/utilisateur/reputation/update';

// MODERATION
var ADMIN_ROUTE_USER_MODERATION_ALERT_NEW = 'admin/utilisateur/moderation/alert/new';
var ADMIN_ROUTE_USER_MODERATION_BANNED_EMAIL = 'admin/utilisateur/moderation/banned/email';

// MANDATES
var ROUTE_USER_MANDATE_CREATE = 'admin/utilisateur/mandat/create';
var ROUTE_USER_MANDATE_UPDATE = 'admin/utilisateur/mandat/update';
var ROUTE_USER_MANDATE_DELETE = 'admin/utilisateur/mandat/delete';

// ID CHECK
var ADMIN_ROUTE_USER_VALIDATE_ID = 'admin/utilisateur/validation/carte-identite';
var ADMIN_ROUTE_USER_VALIDATE_PHOTO_UPLOAD = 'admin/validation/photo-id-upload';

// LOCALIZATION
var ADMIN_ROUTE_CITY_LISTING = 'admin/villes/listing';
var ADMIN_ROUTE_USER_CITY = 'admin/utilisateur/ville/update';
var ADMIN_ROUTE_DOC_LOC = 'admin/document/localization/update';

// DOCUMENT
var ADMIN_ROUTE_REACTION_CREATE_FORM_INIT = 'admin/reaction/create/form/init';

// OPERATION
var ADMIN_ROUTE_OPERATION_CITIES = 'admin/operation/villes';
var ADMIN_ROUTE_OPERATION_CITIES_SEARCH_LISTING = 'admin/operation/villes/recherche';
var ADMIN_ROUTE_ADD_OPERATION_CITY = 'admin/operation/ville/create';
var ADMIN_ROUTE_DELETE_OPERATION_CITY = 'admin/operation/ville/delete';

// CIRCLE
var ADMIN_ROUTE_CIRCLE_FILTER_USERS = 'admin/circle/filter/users';

// Generic function to make an AJAX call
var xhrCall = function(context, data, url, type) {
    type = (typeof type === "undefined") ? 'GET' : type;
    
    // Return the $.ajax promise
    return $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: url,
        context: context,
        beforeSend: function ( xhr ) { xhrBeforeSend(); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },        
    });
}

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
 */
function xhrBeforeSend( ) {
    $('#infoBoxHolder .boxSuccess').hide();
    $('#infoBoxHolder .boxError').hide();
    $('#infoBoxHolder.boxAlert').hide();

    $('#ajaxGlobalLoader').show();    
}

/**
 *
 */
function xhr404( ) {
    $('#ajaxGlobalLoader').hide();
    $('#infoBoxHolder .boxError .notifBoxText').html('Erreur 404: cette action n\'existe pas.');
    $('#infoBoxHolder .boxError').show();
}

/**
 *
 */
function xhr500( ) {
    $('#ajaxGlobalLoader').hide();
    $('#infoBoxHolder .boxError .notifBoxText').html('Erreur 500: merci de recharger la page.');
    $('#infoBoxHolder .boxError').show();
}

/**
 *
 */
function xhrError( jqXHR, textStatus, errorThrown ) {
    $('#ajaxGlobalLoader').hide();
    $('#infoBoxHolder .boxError .notifBoxText').html('Erreur inconnue: merci de recharger la page.');
    $('#infoBoxHolder .boxError').show();
    // console.log('textStatus - '+textStatus);
    // console.log('errorThrown - '+errorThrown);
}