jQuery(document).ready(function () {
    var datas = {
        'action': 'dm_get_icon_svgs',
        'rc_nonce': rami.nonce,
    };

    jQuery.ajax({
        url: rami.ajax_url,
        data: datas,
        type: 'post',
        dataType: 'json',

        beforeSend: function () {

        },
        success: function (r) {
            if (r.success) {
                //console.log(r.response);


            } else {
                console.log('Something went wrong, please try again!');
            }

        }, error: function () {

        }
    });
});