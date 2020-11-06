jQuery(document).ready(function ($) {

    $("#license_btn").click(function () {

        var data = {
            'action': 'my_action',
            'whatever': this.form.key.value
        };

        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajaxurl, data, function (response) {
            alert('Got this from the server: ' + response);
        });
    });

});