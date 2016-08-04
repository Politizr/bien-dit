// send admin notif
$("body").on("click", "[action='createAdminNotif']", function(e) {
    console.log('*** click createAdminNotif');
    
    if (confirm('Êtes-vous sûr?')) {
        return createAdminNotification();
    }
});

