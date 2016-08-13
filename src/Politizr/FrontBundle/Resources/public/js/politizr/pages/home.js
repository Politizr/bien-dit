$(function(){
    // video en home page
    $('#video').YTPlayer({
        videoId: '7WHZpebc9q4',
        fitToBackground: true,
        repeat: true,
        mute: true,
        pauseOnScroll: false,
        playerVars: {
            modestbranding: 1,
            autoplay: 1,
            controls: 0,
            showinfo: 0,
            wmode: 'transparent',
            rel: 0,
            disablekb: 1,
            autohide: 0
          }
    });
    
    // img liquid
    $(".publicCard a.publicCardImg").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
    $("#ifNoVideo").imgLiquid({
        fill: true,
        horizontalAlign: "right",
        verticalAlign: "center"
    });

    $(document)
        .ready(function(e){
            triggerTransparentHeader();
        });
    
    $(window)
        .scroll(function(e){
            triggerTransparentHeader();   
            $('#publicMenu, body.css700 #headerMenu').hide(); //hide menu au scroll
        });
});
