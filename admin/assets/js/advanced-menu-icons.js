function rami_ajax(action="", additionalDatas, beforeSend=null, currentAction="") {

    var datas = {
        'action': action,
        'rc_nonce': rami.nonce,
    };
    if (Object.keys(additionalDatas).length > 0){
        Object.assign(datas, additionalDatas);
    }

    return new Promise((resolve, reject) => {
        return jQuery.ajax({
            url: rami.ajax_url,
            data: datas,
            type: 'post',
            cache: true,


            beforeSend: function () {
                if (beforeSend!==null){
                    beforeSend();
                }
            },
            success: function (e) {
                resolve(e);
            },
            error: function (e) {

            }
        })
    });

}

function setAccordionHtmlData(r, type="normal") {
    jQuery('.dm-popup-inner').animate({scrollTop: '0px'}, 0);
    r = JSON.parse(r);
    if (r.success) {
        var resp = r.response;
        if (Object.keys(resp).length > 0){
            let iconsHTML = '';
            let iconsGroups = '';

            jQuery(Object.keys(resp)).each(function (i, groupName) {
                let listItem = getIcons(resp[groupName], groupName);
                iconsGroups += `<li class="icons-group-item" igroup-id="${groupName}">${getFontName(groupName)}</li>`;

                iconsHTML += `
                                    <div class="rc_accordion-item icon-group" style="margin-top: 20px;">
                                        <div class="rc_accordion-header icon-group-name" data-icon-id="${groupName}">
                                            ${getFontName(groupName)}<span class="dashicons dashicons-arrow-down"></span>
                                        </div>
                                        <div class="rc_accordion-content icon-group-contents ${groupName}" style="display: ${type=="search"? "block": "none"}">
                                            <ul class="svg-list">${listItem}</ul>
                                        </div>
                                    </div>`;
            });

            jQuery('.icons-groups-list').html(iconsGroups )
            jQuery('.rc_accordion').html(iconsHTML)

        }
        else{
            jQuery('.rc_accordion').html("Not found!");
        }


    } else {
        console.log('Something went wrong, please try again!');
    }
}

/*Menu icons*/
jQuery(function() {
    //----- OPEN
    jQuery(document).on('click', '[data-dm-popup-open]', function(e)  {
        jQuery('.select-svg-image').removeClass('popup-activated');
        jQuery(this).addClass('popup-activated');
        var targeted_popup_class = jQuery(this).attr('data-dm-popup-open');
        jQuery('[data-dm-popup="' + targeted_popup_class + '"]').fadeIn(350);
        let rc_hidden_fields = jQuery(this).closest('.advanced-menu-icon-section').find('#rc-hidden-fields');
        let svgTitle = rc_hidden_fields.find('[name*="svg-tag"]').val();
        e.preventDefault();

        /*if (jQuery('.rc_accordion .rc_accordion-item').length){
            return;
        }*/

        if (svgTitle.length == 0){
            jQuery('.rc_accordion').html('<span class="rami-spinner"></span>');
            rami_ajax('getSvgIconsAccordions', {'type': 'on_load'}).then(function (r) {
                setAccordionHtmlData(r);


                setTimeout(function(){
                    jQuery('.rc_accordion-item:first-child .rc_accordion-header').click();
                }, 10);
            })
        }
        else{
            jQuery('.search-field').val(svgTitle).keyup();
        }

    });

    //----- CLOSE
    jQuery('[data-dm-popup-close]').on('click', function(e)  {
        jQuery('.select-svg-image').removeClass('popup-activated');
        var targeted_popup_class = jQuery(this).attr('data-dm-popup-close');
        jQuery('[data-dm-popup="' + targeted_popup_class + '"]').fadeOut(350);

        e.preventDefault();
    });
});

var timeout;

jQuery('.search-form .search-field').on('keyup',function(){
    //if you already have a timout, clear it
    if(timeout){ clearTimeout(timeout);}
    var this_ = jQuery(this);

    jQuery('.rc_accordion-content').show();

    timeout = setTimeout(function() {
        if (this_.val().length == 0){

            jQuery('.rc_accordion').html('<span class="rami-spinner"></span>');
            rami_ajax('getSvgIconsAccordions', {'type': 'search_clear'}).then(function (r) {

                setAccordionHtmlData(r, "search_clear");


                setTimeout(function(){
                    jQuery('.rc_accordion-item:first-child .rc_accordion-header').click();
                }, 10);
            })

        }
        else{
            /*jQuery('.svg-list li[title]').hide();
            jQuery('.svg-list li[title]').each(function () {
                if (jQuery(this).attr('title').indexOf(this_.val()) !== -1){
                    jQuery(this).show();
                }
            });*/
            jQuery('.rc_accordion').html('<span class="rami-spinner"></span>');
            rami_ajax('advanced_menu_icon_search', {'s': this_.val()}).then(function (r) {
                setAccordionHtmlData(r, "search");
            })
        }
    },600);
})

function getIcons(icons, groupName=""){
    if (icons==0){
        return ''
    }else {
        let html = "";
        jQuery(icons).each(function (i, item) {

            if(typeof item.tag != "undefined")
            {
                let tag = item.tag;
                let svg = item.svg;

                if (groupName=="country-flags"){
                    svg = `<img src="${svg}">`;
                }

                /**/
                if (tag.length == 0){
                    html += `<li class="icon-group" svg-id="${item.id}" svg-group="${item.igroup}">${svg}</li>`;
                }
                else{
                    html += `<li title="${tag}" svg-id="${item.id}" svg-group="${item.igroup}">${svg}</li>`;
                }
            }

        });
        return html;
    }

}

function getFontName(key) {
    let name = '';
    if (key.includes('-')) {
        const names = key.split('-');
        name = names.map(n => n.charAt(0).toUpperCase() + n.slice(1)).join(' ');
    } else if (key.includes('_')) {
        const names = key.split('_');
        name = names.map(n => n.charAt(0).toUpperCase() + n.slice(1)).join(' ');
    } else {
        name = key.charAt(0).toUpperCase() + key.slice(1);
    }
    return name;
}

// Listening for a click event on list items within the SVG list inside a DM popup
jQuery(document).on("click", ".dm-popup-inner .svg-list li", function () {
    // Getting the HTML of the clicked icon
    var clickedIconHtml = jQuery(this).html();
    // Finding the active SVG popup element
    var activeSvgPopup = jQuery('.select-svg-image.popup-activated');
    // Retrieving the SVG icon color from the closest menu item settings
    var svgIconColor = activeSvgPopup.closest('.menu-item-settings').find('[name*="svg-icon-color"]').val();
    let svgTitle = jQuery(this).attr('title');
    let svgGroup = jQuery(this).attr('svg-group');
    let svgId = jQuery(this).attr('svg-id');

    // Initialized to store the final HTML of the icon
    var finalIconHtml = "";
    // Getting the 'fill' attribute of the clicked icon
    var iconFillAttribute = jQuery(clickedIconHtml).attr('fill');
    // Checking if the icon exists and the 'fill' attribute is set to 'none'
    if (jQuery(clickedIconHtml).length && typeof iconFillAttribute !== 'undefined' && iconFillAttribute !== false && iconFillAttribute === "none"){
        // Setting the color and fill of the icon and getting the outer HTML
        finalIconHtml = jQuery(clickedIconHtml).css({'color': svgIconColor, 'fill': 'none'})[0].outerHTML;
    }
    else{
        if (svgGroup!=="country-flags"){
            // Setting the fill of the icon and getting the outer HTML
            finalIconHtml = jQuery(clickedIconHtml).css('fill', svgIconColor)[0].outerHTML;
        }
        else{
            finalIconHtml = jQuery(clickedIconHtml)[0].outerHTML;
        }

    }

    // Updating the active SVG popup with the final icon HTML and adding a class
    activeSvgPopup.html(finalIconHtml).addClass('has-icon');
    // Setting the original clicked icon HTML in the relevant element

    if (svgGroup=="country-flags"){
        let finalFlagSrc = jQuery(finalIconHtml).attr('src');
        rami_ajax('saveFlagToDb', {'svgGroup': svgGroup, 'iconId': svgId, 'url': finalFlagSrc}).then(function (r) {
            r = JSON.parse(r);
            if (typeof r.flagUrl != "undefined"){
                let flagUrl = r.flagUrl;
                finalIconHtml = jQuery(finalIconHtml).css({'width': svgIconSize+'px', 'height': 'auto'}).attr({'src': flagUrl})[0].outerHTML;
                activeSvgPopup.closest('.menu-item-settings').find('[name*="svgicon"]').html(finalIconHtml);
                activeSvgPopup.closest('.menu-item-settings').find('.select-svg-image img').attr('src', flagUrl);
            }
        })
    }
    else{
        activeSvgPopup.closest('.menu-item-settings').find('[name*="svgicon"]').html(finalIconHtml);
    }


    activeSvgPopup.closest('.menu-item-settings').find('[name*="svg-tag"]').val(svgTitle);
    activeSvgPopup.closest('.menu-item-settings').find('[name*="svg-group"]').val(svgGroup);
    activeSvgPopup.closest('.menu-item-settings').find('[name*="svg-id"]').val(svgId);


    // Getting the SVG icon size from the closest menu item settings
    var svgIconSize = activeSvgPopup.closest('.menu-item-settings').find('[id*="svg-icon-size"]').val();


    // Setting the width and height of the SVG icon
    jQuery('.select-svg-image.popup-activated svg, .select-svg-image.popup-activated img').css({'width': svgIconSize+'px', 'height': 'auto'});


    // Showing the sibling element for removing the SVG image
    activeSvgPopup.siblings('.remove-svg-image').show();
    // Triggering the click event on the popup close button
    jQuery('[data-dm-popup-close]').click();
});

// Listening for a click event on the '.remove-svg-image' elements
jQuery(document).on("click", ".remove-svg-image", function (e) {
    e.preventDefault(); // Preventing the default action of the event

    // Finding the sibling '.select-svg-image' element and updating its content and class
    jQuery(this).siblings('.select-svg-image')
        .html(rami.select_an_icon) // Setting placeholder text
        .removeClass('has-icon'); // Removing the 'has-icon' class

    jQuery(this).hide(); // Hiding the remove button itself

    jQuery(this).closest('.menu-item-settings').find('[name*="svg-tag"]').val("");
    jQuery(this).closest('.menu-item-settings').find('[name*="svg-group"]').val("");
    jQuery(this).closest('.menu-item-settings').find('[name*="svg-id"]').val("");
    jQuery('.search-field').val("");
});

// Listening for a change event on elements with ID containing "svg-icon-size"
jQuery(document).on("change", '[id*="svg-icon-size"]', function () {
    // Retrieving the SVG icon size value from the closest menu item settings
    var svgIconSize = jQuery(this).closest('.menu-item-settings').find('[id*="svg-icon-size"]').val();
    // Setting the width of the SVG icon in the select image area
    jQuery(this).closest('.menu-item-settings').find('.select-svg-image svg, .select-svg-image img').css('width', svgIconSize + 'px');
    let iconData = jQuery(this).closest('.menu-item-settings').find('[name*="svgicon"]').html();
    iconData = decodeHTMLEntities(iconData);
    let flagIconData = jQuery(iconData).css('width', svgIconSize + 'px')[0].outerHTML;

    jQuery(this).closest('.menu-item-settings').find('[name*="svgicon"]').html(flagIconData);
    // Setting the width of the custom image upload area (if applicable)
    jQuery(this).closest('.menu-item-settings').find('.rc-custom-image-upload img').css('width', svgIconSize + 'px');
});
function decodeHTMLEntities(text) {
    var entities = [
        ['amp', '&'],
        ['apos', '\''],
        ['#x27', '\''],
        ['#x2F', '/'],
        ['#39', '\''],
        ['#47', '/'],
        ['lt', '<'],
        ['gt', '>'],
        ['nbsp', ' '],
        ['quot', '"']
    ];

    for (var i = 0, max = entities.length; i < max; ++i)
        text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

    return text;
}
jQuery(document).on("change", '[name*="svg-position"]', function () {
    var position = jQuery(this).val();
    var margin_left = 0;
    var margin_right = 0;
    var margin_top = 0;
    var margin_bottom = 0;
    if (position == "Left"){
        margin_right = 5;
    }
    if (position == "Right"){
        margin_left = 5;
    }
    /*if (position == "Bottom"){
        margin_left = 0;
        margin_right = 10;
        margin_top = 0;
        margin_bottom = 0;
        jQuery(this).closest('.menu-item-settings').find('[name*="top-margin"]').val(margin_top);
        jQuery(this).closest('.menu-item-settings').find('[name*="bottom-margin"]').val(margin_bottom);
    }
    if (position == "Top"){
        margin_left = 10;
        margin_right = 0;
        margin_top = 0;
        margin_bottom = 0;
        jQuery(this).closest('.menu-item-settings').find('[name*="top-margin"]').val(margin_top);
        jQuery(this).closest('.menu-item-settings').find('[name*="bottom-margin"]').val(margin_bottom);
    }*/

    jQuery(this).closest('.menu-item-settings').find('[name*="right-margin"]').val(margin_right);
    jQuery(this).closest('.menu-item-settings').find('[name*="left-margin"]').val(margin_left);
});
jQuery(document).on("change", '#svg-position-rami_', function () {
    var position = jQuery(this).val();
    var margin_left = 0;
    var margin_right = 0;
    var margin_top = 0;
    var margin_bottom = 0;
    if (position == "Left"){
        margin_right = 5;
    }
    if (position == "Right"){
        margin_left = 5;
    }


    jQuery(this).closest('#advanced_menu_icon_section_metabox').find('[name*="right-margin"]').val(margin_right);
    jQuery(this).closest('#advanced_menu_icon_section_metabox').find('[name*="left-margin"]').val(margin_left);
});


jQuery(document).ready(function(jQuery){
    jQuery('.rami-color-field').wpColorPicker({
            /**
             * @param {Event} event - standard jQuery event, produced by whichever
             * control was changed.
             * @param {Object} ui - standard jQuery UI object, with a color member
             * containing a Color.js object.
             */
            change: function (event, ui) {
                var element = event.target;
                var color = ui.color.toString();

                var elmnt = jQuery(element).closest('.menu-item-settings').find('svg');
                var attr = elmnt.attr('fill');
                if (elmnt.length && typeof attr !== 'undefined' && attr !== false && attr == "none"){
                    elmnt.css({'color': color});
                    elmnt.css({'fill': 'none'});
                }
                else{
                    elmnt.css({'fill': color});
                }
                elmnt.parent().css({'color': color});

                // Add your code here
            },
            clear: function (event) {
                var element = event.target;
                var color = '';

                var elmnt = jQuery(element).closest('.menu-item-settings').find('svg');
                var attr = elmnt.attr('fill');
                if (elmnt.length && typeof attr !== 'undefined' && attr !== false && attr === "none"){
                    elmnt.css({'color': color});
                    elmnt.css({'fill': 'none'});
                }
                else{
                    elmnt.css({'fill': color});
                }
                // Add your code here
            },
        }

    );
});

//jQuery(document).ready(function ($) {
    //jQuery('.rc_accordion-item').each(function () {


jQuery(document).on('click','.rc_accordion-item .rc_accordion-header', function () {
    var content = jQuery(this).siblings('.rc_accordion-content');
    if (!content.is(":visible")) {
        content.slideDown(100)


        if (content.find('.svg-list li').length !== 0){
            return;
        }

        jQuery(content).find('.svg-list').html('<span class="rami-spinner"></span>');

        var iconGroup = jQuery(this).data('icon-id');
        rami_ajax('dm_get_icon_svgs', {'iconGroup': iconGroup}).then(function (r) {
            r = JSON.parse(r);
            if (r.success) {
                let completed = r.isCompleted;
                let nextIcons = r.nextIcons;
                let data = "";
                if (r.total !== "0"){
                    data = getIcons(r.response, iconGroup);
                }
                else{
                    data = '<div id="wrap">\n' +
                        '<a href="#" class="btn-slide2 icons-download-btn">\n' +
                        '  <span class="circle2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="fill: #ffffff;width: 19px;vertical-align: middle;"><path d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path></svg></span>\n' +
                        '  <span class="title2">Download Icons</span>\n' +
                        '  <span class="title-hover2">Click here</span>\n' +
                        '</a>\n' +
                        '</div>'; //todo
                }
                setTimeout(function(){
                    content.find('.svg-list').html(data);
                }, 500);

                if(!completed){
                    rami_get_accordion_items(content, iconGroup, nextIcons);
                }

            } else {
                console.log('Something went wrong, please try again!');
            }
        })
    } else {
        content.slideUp(300)
    }
});

function rami_get_accordion_items(content, iconGroup, next) {

    rami_ajax('dm_get_icon_svgs', {'iconGroup': iconGroup, 'next': next}).then(function (r) {
        r = JSON.parse(r);
        if (r.success) {
            let completed = r.isCompleted;
            let nextIcons = r.nextIcons;
            setTimeout(function(){
                content.find('.svg-list').append(getIcons(r.response));
            }, 500);

            if(!completed){
                rami_get_accordion_items(content, iconGroup, nextIcons);
            }

        } else {
            console.log('Something went wrong, please try again!');
        }
    })

}
    //});
//});


jQuery(document).on("change", ".dm-margin-inputs input", function () {
    var margin = '';
    jQuery(this).closest('.dm-margin-inputs-container').find('input').each(function () {
        margin += jQuery(this).val() + 'px ';
    });
    jQuery(this).closest('.menu-item-settings').find('.select-svg-image svg, .select-svg-image img').css({'margin': margin})
    jQuery(this).closest('.menu-item-settings').find('.rc-custom-image-upload img').css({'margin': margin})
});

jQuery(document).on("change", ".upload-image-icon", function () {
    if (jQuery(this).is(':checked')){
        jQuery(this).closest('.menu-item-settings').find('.svg-icon-section').slideUp(300);
        jQuery(this).closest('.menu-item-settings').find('.dm-custom-image-section').slideDown(300);
    }else{
        jQuery(this).closest('.menu-item-settings').find('.dm-custom-image-section').slideUp(300);
        jQuery(this).closest('.menu-item-settings').find('.svg-icon-section').slideDown(300);
    }
});

jQuery(document).on("click", "#search-data-clear", function (e) {
    e.preventDefault();
    jQuery('.icons-groups-list, .rc_accordion').html("");
    jQuery('.search-field').val("").keyup();
});

/*Media uploader scripts*/
jQuery( function($){
    function rami_wp_media() {
        const button = $(this)
        const imageId = button.next().next().val();
        button.addClass('media-activated');
        var imageWidth = button.closest('.menu-item-settings').find('[name*="svg-icon-size"]').val();

        const customUploader = wp.media({
            title: 'Insert image', // modal window title
            library: {
                // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                type: 'image'
            },
            button: {
                text: 'Use this image' // button label text
            },
            multiple: false
        }).on('select', function () { // it also has "open" and "close" events
            const attachment = customUploader.state().get('selection').first().toJSON();
            button.removeClass('button').html('<img width="' + imageWidth + '" src="' + attachment.url + '">'); // add image instead of "Upload Image"
            button.next().show(); // show "Remove image" link
            button.next().next().val(attachment.id); // Populate the hidden field with image ID
            button.removeClass('media-activated');
        })

        // Bind a function to execute when the media library modal is closed
        customUploader.on('close', function () {

            button.removeClass('media-activated');
        });

        // already selected images
        customUploader.on('open', function () {

            if (imageId) {
                const selection = customUploader.state().get('selection')
                attachment = wp.media.attachment(imageId);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            }

        })

        customUploader.open()
    }

// on upload button click
    $( 'body' ).on( 'click', '.rc-custom-image-upload, .rc-hover-image-upload', function( event ){
        event.preventDefault(); // prevent default link click and page refresh
        rami_wp_media.call(this, 'custom-image');

    });
    // on remove button click
    $( 'body' ).on( 'click', '.rc-custom-image-remove', function( event ){
        event.preventDefault();
        const button = $(this);
        button.next().val( '' ); // emptying the hidden field
        button.hide().prev().removeClass('media-activated').addClass( 'button' ).html( 'Upload image' ); // replace the image with text
    });

    $(document).ready(function () {
        $('#nav-menu-footer .major-publishing-actions').append('<span class="clear-menu-icon-action"><a class="submitClearIcon menu-icon-clear" href="#">Clear Icons Data</a></span>')
    });
    $(document).on("click", ".submitClearIcon", function () {
        let text;
        if (confirm("Are you sure! you want to clear all menu icons data?") == true) {
            rami_ajax('clearAllIconData', {}).then(function (r) {
                r = JSON.parse(r);

                console.log(r)

                $.growl({
                    title: "Settings has been reset! Page will be reloaded in 2 seconds.",
                    style: "error",
                    fixed: false,
                    location: "bc",
                    message: "" });

                setTimeout(function(){
                    location.reload();
                }, 2000);

            })
        } else {
            text = "You canceled!";
        }
    });

    $(document).on("click", ".dm-popup", function (e) {
        var container = $(".dm-popup-inner, .dm-popup-close");
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            $('.dm-popup-close').click();
        }
    });

    $(document).on("click", ".icons-group-item", function () {
        let igroupName = $(this).attr('igroup-id');
        let groupTab = $('[data-icon-id="'+igroupName+'"]');
        if(groupTab.length){
            let top = groupTab.position().top-100;
            $('.dm-popup-inner').animate({scrollTop: '+='+top+'px'}, 800);
            setTimeout(function () {
                if (!$(groupTab).parent().find('.rc_accordion-content').is(":visible")){
                    groupTab.click()
                }
            }, 800)
        }
    });
});
