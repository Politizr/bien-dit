$(function(){
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
