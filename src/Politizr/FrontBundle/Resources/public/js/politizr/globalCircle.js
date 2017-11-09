// toggle menu grp
$("body").on("mousedown touchstart", "[action='toggleGrpMenuLinks']", function() {
    $('#grpMenuLinks').toggle();
});

// toggle grp brief
$("body").on("mousedown touchstart", "[action='showBrief']", function() {
    $('#grpBrief').stop().animate({
        height: $('#grpBrief')[0].scrollHeight+'px',
        overflow:'visible'
    }, 600);
    $('.showBriefLink').hide();
    $('#grpBriefHeader .hideBriefLink').show();
});
$("body").on("mousedown touchstart", "[action='hideBrief']", function() {
    $('body #grpBrief').stop().animate({
        height:'500px',
        overflow:'hidden'
    }, 600);
    $('body.css700 #grpBrief').stop().animate({
        height:'360px',
        overflow:'hidden'
    }, 600);
    $('.showBriefLink').show();
    $('#grpBriefHeader .hideBriefLink').hide();
});


// reduce menu grp on scroll
$(function(){
    $('body.css #grpMenu, body.css1060 #grpMenu').data('size','big');
});

$(window).scroll(function(){
    if($(document).scrollTop() > 0)
    {
        if($('body.css #grpMenu, body.css1060 #grpMenu').data('size') == 'big')
        {
            $('body.css #grpMenu, body.css1060 #grpMenu').data('size','small');
            $('#grpMenu').stop().animate({
                padding:'7px 0 5px 0'
            },600);
            $('body.css #grpMenu .center').stop().animate({
                paddingBottom:'7px'
            },600);
            $('#grpMenu img').stop().animate({
                height:'40px'
            },600);
            $('#grpMenuPseudoTabs').stop().animate({
                marginTop:'0'
            },600);
            $('body.css1060 #grpMenuPseudoTabs').stop().animate({
                height:'toggle',
            },600);
            $('#grpMenuLinks').stop().animate({
                marginTop:'7px'
            },600);
            $('#grpMenuLinks span').stop().animate({
                paddingBottom:'6px'
            },600);
            $('body.grp #headerTrigger').stop().animate({
                height:'140px'
            },600);
            $('#grpMenu').addClass("smallGrpMenu");
        }
    }
    else
    {
        if($('body.css #grpMenu, body.css1060 #grpMenu').data('size') == 'small')
        {
            $('#grpMenu').data('size','big');
            $('#grpMenu').stop().animate({
                padding:'20px 0'
            },200);
            $('body.css #grpMenu .center').stop().animate({
                paddingBottom:'20px'
            },200);
            $('#grpMenu img').stop().animate({
                height:'80px'
            },200);
            $('body.css #grpMenuPseudoTabs, #grpMenuLinks').stop().animate({
                marginTop:'20px'
            },200);
            $('body.css1060 #grpMenuPseudoTabs').stop().animate({
                height:'toggle',
            },200);
            $('#grpMenuLinks span').stop().animate({
                paddingBottom:'26px'
            },200);
            $('body.grp #headerTrigger').stop().animate({
                height:'200px'
            },200);  
            $('#grpMenu').removeClass("smallGrpMenu");
        }  
    }
});
