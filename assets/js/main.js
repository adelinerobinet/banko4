(function($) {
    // Notifications
    /* type = danger | success | primary | warning */
    $.AppNotify = function(title, content, type) {
        $.notify({
            title: "<strong>" + title + "</strong><br />",
            message: content
        },{
            type: type,
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="title">{1}</span>' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
        });
    };

    $.AppNotifySystemError = function() {
        $.AppNotify('Erreur', 'Impossible d\'effectuer l\'action.', 'danger');
    };
})(jQuery);