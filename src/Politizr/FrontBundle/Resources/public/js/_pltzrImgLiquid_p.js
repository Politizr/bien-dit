$(function(){	
	// full size image on public homepage
    $("#homeClaim, #homeOfficial").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
    // full size image in avatars
    $(".avatar15, .avatar40, .avatar60").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    }); 
    // full size image in profile summary 
    $("#profileHeaderIllustration").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    }); 
    // full size image in debate summary 
    $(".postSummary").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    }); 
});