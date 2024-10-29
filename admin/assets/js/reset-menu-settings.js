(function ($) {
    'use strict';

    jQuery(document).on("click", ".action-svg-menu-settings #reset_menu_settings", function(){

        var datas = {
            'action': 'reset_rami_settings',
            'rc_nonce': rami.nonce,
        };

        $.ajax({
            url: rami.ajax_url,
            data: datas,
            type: 'post',
            dataType: 'json',

            beforeSend: function(){

            },
            success: function(r){
                if(r.success){

                    $('#svg-icon-color-rami_').val('#333333').closest('.wp-picker-container').find('.wp-color-result').css({'background-color': '#333333'});
                    $('#svg-icon-hover-color-rami_').val('#000000').closest('.wp-picker-container').find('.wp-color-result').css({'background-color': '#000000'});
                    $('#svg-position-rami_').val('Left').change();
                    $('#svg-icon-size-rami_').val('25');
                    $('#top-margin-rami_').val('0');
                    $('#right-margin-rami_').val('5');
                    $('#bottom-margin-rami_').val('0');
                    $('#left-margin-rami_').val('0');

                    $.growl({
                        title: "Settings has been reset!",
                        style: "error",
                        fixed: false,
                        location: "bc",
                        message: "" });

                } else {
                    console.log('Something went wrong, please try again!');
                }

            }, error: function(){

            }
        });

    });
})(jQuery);
