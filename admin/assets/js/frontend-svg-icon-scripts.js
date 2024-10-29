document.addEventListener('DOMContentLoaded', function () {
    var menuIcons = document.querySelectorAll('.dm-menu-icon');

    function advancedMenuIconsOnMenuItemActive(dm, hoverColor) {

        var svgElement = dm.querySelector('svg');
        var imageElement = dm.querySelector('img');

        if (imageElement) {
            var hoverImageAttr = imageElement.getAttribute('hover-image');
            if (imageElement && typeof hoverImageAttr != 'undefined' && hoverImageAttr !== null && hoverImageAttr !== false && !imageElement.classList.contains('no-hover')) {
                imageElement.setAttribute('src', hoverImageAttr);
            }
        }

        if (hoverColor !== undefined) {
            var fillAttr = svgElement.getAttribute('fill');
            if (svgElement && typeof fillAttr != 'undefined' && fillAttr !== false && fillAttr === 'none') {
                svgElement.style.color = hoverColor;
                svgElement.style.fill = 'none';
            } else {
                svgElement.style.fill = hoverColor;
            }
        }
    }

    menuIcons.forEach(function (icon) {
        var closestLink = icon.closest('a');
        var dm = closestLink.querySelector('.dm-menu-icon');
        var hoverColor = dm.dataset.svgHoverColor;
        var color = dm.dataset.svgColor;

        if (dm.closest('.current-menu-item') != null || dm.closest('.current_page_item') != null) {
            advancedMenuIconsOnMenuItemActive(dm, hoverColor);
        }
        if (dm.parentNode.tagName === 'SPAN'){
            console.log("tests")
            if (dm.classList.contains('icon-on-left')){
                //document.getElementById('to_be_moved').appendChild(dm)
            }
            else{

                dm.closest('a').appendChild(dm)
            }
        }

        closestLink.addEventListener('mouseenter', function (e) {


            var svgElement = dm.querySelector('svg');
            var imageElement = dm.querySelector('img');

            if (imageElement){
                var hoverImageAttr = imageElement.getAttribute('hover-image');
                if (imageElement && typeof hoverImageAttr != 'undefined' && hoverImageAttr !== null && hoverImageAttr !== false && !imageElement.classList.contains('no-hover')) {
                    imageElement.setAttribute('src', hoverImageAttr);
                }
            }

            if (hoverColor !== undefined) {
                var fillAttr = svgElement.getAttribute('fill');
                if (svgElement && typeof fillAttr != 'undefined' && fillAttr !== false && fillAttr === 'none') {
                    svgElement.style.color = hoverColor;
                    svgElement.style.fill = 'none';
                } else {
                    svgElement.style.fill = hoverColor;
                }
            }

            e.target.addEventListener('mouseleave', function () {
                if (dm.closest('.current-menu-item') != null || dm.closest('.current_page_item') != null) {
                    advancedMenuIconsOnMenuItemActive(dm, hoverColor);
                }
                else{
                    var imageElement = dm.querySelector('img');
                    if (imageElement){
                        var imageAttr = imageElement.getAttribute('main-svg-image');
                        if (imageElement && typeof imageAttr != 'undefined' && imageAttr !== false && imageAttr !== null && !imageElement.classList.contains('no-hover')) {
                            imageElement.setAttribute('src', imageAttr);
                        }
                    }

                    if (color !== undefined) {
                        var svgElement = dm.querySelector('svg');
                        var fillAttr = svgElement.getAttribute('fill');


                        if (svgElement && typeof fillAttr != 'undefined' && fillAttr !== false && fillAttr !== null && fillAttr === 'none') {
                            svgElement.style.color = color;
                            svgElement.style.fill = 'none';
                        } else {
                            svgElement.style.fill = color;
                        }
                    }
                }

            });
        });
    });
});

/*
jQuery(document).ready(function () {
    jQuery('.dm-menu-icon').each(function () {
        jQuery(this).closest('a').mouseenter(function (e) {
            var dm = jQuery(this).find('.dm-menu-icon');
            var hover_color = dm.data('svg-hover-color');
            var color = dm.data('svg-color');
            if (hover_color !== undefined){
                var elmnt = dm.find('svg');

                var attr = elmnt.attr('fill');
                if (elmnt.length && typeof attr !== 'undefined' && attr !== false && attr === "none"){
                    elmnt.css({'color': hover_color});
                    elmnt.css({'fill': 'none'});
                }
                else{
                    elmnt.css({'fill': hover_color});
                }
            }
            jQuery(e.target).mouseleave(function () {
                if (color !== undefined){
                    var elmnt = dm.find('svg');

                    var attr = elmnt.attr('fill');
                    if (elmnt.length && typeof attr !== 'undefined' && attr !== false && attr === "none"){
                        elmnt.css({'color': color});
                        elmnt.css({'fill': 'none'});
                    }
                    else{
                        elmnt.css({'fill': color});
                    }
                }
            });
        });

    });
});*/
