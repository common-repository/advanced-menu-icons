(function ($) {
    'use strict';

    jQuery(document).on("click", ".action-svg-menu-settings #save_menu_footer", function(){
        var default_icon_pack = $('#default-icon-pack-rami_').val();
        var svg_icon_color = $('#svg-icon-color-rami_').val();
        var svg_icon_hover_color = $('#svg-icon-hover-color-rami_').val();
        var svg_icon_position = $('#svg-position-rami_').val();
        var svg_icon_size = $('#svg-icon-size-rami_').val();
        var svg_margin_top = $('#top-margin-rami_').val();
        var svg_margin_right = $('#right-margin-rami_').val();
        var svg_margin_bottom = $('#bottom-margin-rami_').val();
        var svg_margin_left = $('#left-margin-rami_').val();

        var datas = {
            'action': 'save_rami_settings',
            'rc_nonce': rami.nonce,
            'default_icon_pack': default_icon_pack,
            'svg_icon_color': svg_icon_color,
            'svg_icon_hover_color': svg_icon_hover_color,
            'svg_icon_position': svg_icon_position,
            'svg_icon_size': svg_icon_size,
            'svg_margin_top': svg_margin_top,
            'svg_margin_right': svg_margin_right,
            'svg_margin_bottom': svg_margin_bottom,
            'svg_margin_left': svg_margin_left,
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
                    $.growl({
                        title: "Settings has been saved!",
                        style: "notice",
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
