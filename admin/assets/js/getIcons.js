(function ($) {
    'use strict';

    var $ = jQuery;
    $(document).on("click", ".icons-download-btn", function () {
        $('body').append('<div class="downloadPopup"><div id="pwcontainer" class="pwcontainer"><h1 class="ctr">'+rami.iconsDownloading+'</h1><div class="pwfillme"><div class="pwfillme-line"></div></div></div></div>');
        get1stIconFile();
    });


    function get1stIconFile(){
        rami_ajax('getIcons', {'fileId': '1'}).then(function (resp) {
            resp = JSON.parse(resp)
            if (resp.success){
                get2ndIconFile();
            }
            else if (!resp.success && resp.status == "something_wrong"){
                somethingWentWrong()
            }

        })
    }
    function get2ndIconFile(){
        rami_ajax('getIcons', {'fileId': '2'}).then(function (resp) {
            resp = JSON.parse(resp)
            if (resp.success){
                get3rdIconFile();
            }
            else if (!resp.success && resp.status == "something_wrong"){
                somethingWentWrong()
            }
        })
    }
    function get3rdIconFile(){
        rami_ajax('getIcons', {'fileId': '3'}).then(function (resp) {
            resp = JSON.parse(resp)
            if (resp.success){
                get4thIconFile()
            }
            else if (!resp.success && resp.status == "something_wrong"){
                somethingWentWrong()
            }
        })
    }
    function get4thIconFile(){
        rami_ajax('getIcons', {'fileId': '4'}).then(function (resp) {
            resp = JSON.parse(resp)
            if (resp.success){
                $('#pwcontainer, .downloadPopup').remove()
                $.growl({
                    title: rami.iconsDownloaded,
                    style: "notice",
                    fixed: false,
                    location: "bc",
                    message: "" });

                $('#search-data-clear').click();
            }
            else if (!resp.success && resp.status == "something_wrong"){
                somethingWentWrong()
            }
        })
    }
    function somethingWentWrong() {
        alert(rami.something_wrong);
        $('#pwcontainer, .downloadPopup').remove()
        $.growl({
            title: rami.something_wrong,
            style: "error",
            fixed: false,
            location: "bc",
            message: "" });

    }


})(jQuery);