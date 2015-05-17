$(function(){	
	// full size illustration image
    $("#illustration, #illustration_l, #illustration_r").imgLiquid({
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
    // full size image in avatars
    $(".avatar15, .avatar40, .avatar60").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });  
    // full size image in timeline item illustration
    $(".timelineItemIllustration").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    }); 
    // full size image in reaction to post illustration
    $(".reactionsToPostItemIllustration").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });   
});