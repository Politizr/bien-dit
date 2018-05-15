$(function(){
    $(window)
        .scroll(function(e){
            $('#publicMenu, body.css700 #headerMenu').hide(); //hide menu au scroll
        });
});
