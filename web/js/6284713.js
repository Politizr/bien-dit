$("body").on("click","[action='timelinePaginatedNext']",function(e,waypoint){if(waypoint){waypoint.destroy();console.log("destroy waypoint instance")}timelineList(false,$(this).attr("offset"))});function initTimelinePaginateNextWaypoint(){var waypoints=$("#moreResults").waypoint({handler:function(direction){if(direction=="down"){$("[action='timelinePaginatedNext']").trigger("click",this)}},offset:"bottom-in-view"})}function timelineList(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;var xhrPath=getXhrPath(ROUTE_TIMELINE_MINE,"user","timelinePaginated",RETURN_HTML);localLoader=$(".myfeed").find(".ajaxLoader").first();xhrCall(document,{offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#timelineScrollNav").remove();if(init){$("#listContent").html(data["html"])}else{$("#listContent").append(data["html"])}initTimelinePaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}
function topTagListing(targetElement,localLoader){var datas=$("#tagFilter").serializeArray();if($.isEmptyObject(datas)){datas.push({name:"tagFilterDate[]",value:"lastMonth"})}var xhrPath=getXhrPath(ROUTE_TAG_LISTING_TOP,"tag","topTags",RETURN_HTML);return xhrCall(document,datas,xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"])}localLoader.hide()})}function userTagListing(targetElement,localLoader){var uuid=targetElement.attr("uuid");var xhrPath=getXhrPath(ROUTE_TAG_LISTING_USER,"tag","userTags",RETURN_HTML);return xhrCall(document,{uuid:uuid},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"])}localLoader.hide()})}
paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_TAG]=documentsByTagListing;paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_ORGANIZATION]=documentsByOrganizationListing;paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_RECOMMEND]=documentsByRecommendListing;paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_USER_DRAFTS]=myDraftsByUserListing;paginatedFunctions[JS_KEY_LISTING_DOCUMENTS_BY_USER_PUBLICATIONS]=documentsByUserListing;function documentsByUserListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#documentListing .listTop");localLoader=$("#documentListing").find(".ajaxLoader").first();uuid=$(".pseudoTabs").attr("uuid");orderBy=$(".pseudoTabs .currentPage").attr("filter");var xhrPath=getXhrPath(ROUTE_DOCUMENT_LISTING_USER_PUBLICATIONS,"document","documentsByUser",RETURN_HTML);return xhrCall(document,{uuid:uuid,orderBy:orderBy,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}function myDraftsByUserListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#documentListing .listTop");localLoader=$("#documentListing").find(".ajaxLoader").first();var xhrPath=getXhrPath(ROUTE_DOCUMENT_LISTING_MY_DRAFTS,"document","myDraftsPaginated",RETURN_HTML);return xhrCall(document,{offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}$("body").on("click","[action='prevNextLink']",function(e,waypoint){e.preventDefault();documentsByRecommendListingNav($(this).attr("month"),$(this).attr("year")).then(function(){documentsByRecommendListing()})});function documentsByRecommendListingNav(month,year){targetElement=$(".listTopHeader");localLoader=$(".listTopHeader").find(".ajaxLoader").first();var xhrPath=getXhrPath(ROUTE_DOCUMENT_LISTING_RECOMMEND_NAV,"document","documentsByRecommendNav",RETURN_HTML);return xhrCall(document,{month:month,year:year},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);$("#documentListing").attr("month",data["numMonth"]);$("#documentListing").attr("year",data["year"]);updateUrl(data["month"]+"-"+data["year"])}localLoader.hide()})}function documentsByRecommendListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#documentListing .listTop");localLoader=$("#documentListing").find(".ajaxLoader").first();month=$("#documentListing").attr("month");year=$("#documentListing").attr("year");var xhrPath=getXhrPath(ROUTE_DOCUMENT_LISTING_RECOMMEND,"document","documentsByRecommend",RETURN_HTML);return xhrCall(document,{month:month,year:year,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}function documentsByTagListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#documentListing .listTop");localLoader=$("#documentListing").find(".ajaxLoader").first();uuid=$(".pseudoTabs").attr("uuid");filterDate=$(".pseudoTabs .currentPage").attr("filter");var xhrPath=getXhrPath(ROUTE_TAG_LISTING,"document","documentsByTag",RETURN_HTML);return xhrCall(document,{uuid:uuid,filterDate:filterDate,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}function documentsByOrganizationListing(init,offset){init=typeof init==="undefined"?true:init;offset=typeof offset==="undefined"?0:offset;targetElement=$("#documentListing .listTop");localLoader=$("#documentListing").find(".ajaxLoader").first();uuid=$(".pseudoTabs").attr("uuid");filterDate=$(".pseudoTabs .currentPage").attr("filter");var xhrPath=getXhrPath(ROUTE_ORGANIZATION_LISTING,"document","documentsByOrganization",RETURN_HTML);return xhrCall(document,{uuid:uuid,filterDate:filterDate,offset:offset},xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{$("#listingScrollNav").remove();if(init){targetElement.html(data["html"])}else{targetElement.append(data["html"])}initPaginateNextWaypoint();fullImgLiquid()}localLoader.hide()})}function topDocumentListing(targetElement,localLoader){var datas=$("#documentFilter").serializeArray();if($.isEmptyObject(datas)){datas.push({name:"documentFilterDate[]",value:"lastMonth"})}var xhrPath=getXhrPath(ROUTE_DOCUMENT_LISTING_TOP,"document","topDocuments",RETURN_HTML);return xhrCall(document,datas,xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"])}localLoader.hide()})}function suggestionDocumentListing(targetElement,localLoader){var xhrPath=getXhrPath(ROUTE_DOCUMENT_LISTING_SUGGESTION,"document","suggestionDocuments",RETURN_HTML);return xhrCall(document,null,xhrPath,localLoader).done(function(data){if(data["error"]){$("#infoBoxHolder .boxError .notifBoxText").html(data["error"]);$("#infoBoxHolder .boxError").show()}else{targetElement.html(data["html"]);$(".cycle-slideshow").cycle();fullImgLiquid()}localLoader.hide()})}
$(function(){suggestionDocumentListing($("#suggestions").find(".documentList").first(),$("#suggestions").find(".ajaxLoader").first()).then(function(){timelineList();$.when(topTagListing($(".sidebarTopTags").find(".tagList").first(),$(".sidebarTopTags").find(".ajaxLoader").first()),userTagListing($(".sidebarFollowedTags").find(".tagList").first(),$(".sidebarFollowedTags").find(".ajaxLoader").first()),topDocumentListing($(".sidebarTopPosts").find(".documentList").first(),$(".sidebarTopPosts").find(".ajaxLoader").first())).done(function(r1,r2,r3){stickySidebar()})})});