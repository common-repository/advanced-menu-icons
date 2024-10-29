<?php


namespace RecorpAdvancedMenuIcons;


class IconInFrontend
{

    /**
     * Hide menu items based on user roles.
     *
     * @param array $items The array of menu items.
     * @param object $menu The menu object.
     * @param array $args The menu arguments.
     * @return   array                The filtered array of menu items.
     * @since    2.3.0
     * @access   public
     */
    public function icon_in_frontend($items, $menu, $args)
    {
        foreach ($items as $key => $item) {
            $svg_icon = get_post_meta($item->ID, '_svg_icon', true);
            $svg_icon = !empty($svg_icon) ? html_entity_decode($svg_icon) : '';


            $custom_image = get_post_meta($item->ID, '_custom_image', true);
            $custom_image = !empty($custom_image) ? esc_attr($custom_image) : 'off';

            $custom_image_id = get_post_meta($item->ID, '_custom_image_id', true);
            $image_id        = !empty($custom_image_id) ? esc_attr($custom_image_id) : 0;

            $image = wp_get_attachment_image_url($image_id, 'medium');


            $hover_image_id = get_post_meta($item->ID, '_hover_image_id', true);
            $hover_image_id        = !empty($hover_image_id) ? esc_attr($hover_image_id) : 0;
            $hover_image = wp_get_attachment_image_url($hover_image_id, 'medium');

            if (!empty($svg_icon) || ($custom_image == "on" && $image_id !== 0 && $image)) {

                $svg_position = get_post_meta($item->ID, '_svg_position', true);
                $svg_position = !empty($svg_position) ? $svg_position : 'Left';

                $svg_icon_size = get_post_meta($item->ID, '_svg_icon_size', true);
                $svg_icon_size = !empty($svg_icon_size) ? esc_attr($svg_icon_size) : '20';


                $svg_top_margin = get_post_meta($item->ID, '_svg_top_margin', true);
                $svg_top_margin = !empty($svg_top_margin) ? esc_attr($svg_top_margin) : '0';

                $igroup = get_post_meta($item->ID, '_svg_group', true);
                $igroup = !empty($igroup) ? esc_attr($igroup) : 'font-awesome';

                $svg_right_margin = get_post_meta($item->ID, '_svg_right_margin', true);
                $svg_right_margin = !empty($svg_right_margin) ? esc_attr($svg_right_margin) : '5';

                $svg_bottom_margin = get_post_meta($item->ID, '_svg_bottom_margin', true);
                $svg_bottom_margin = !empty($svg_bottom_margin) ? esc_attr($svg_bottom_margin) : '0';

                $svg_left_margin = get_post_meta($item->ID, '_svg_left_margin', true);
                $svg_left_margin = !empty($svg_left_margin) ? esc_attr($svg_left_margin) : '0';


                $svg_icon_color       = get_post_meta($item->ID, '_svg_icon_color', true);
                $svg_icon_hover_color = get_post_meta($item->ID, '_svg_icon_hover_color', true);
                $hoverColorAttr       = 'data-svg-hover-color="currentColor"';
                if (!empty($svg_icon_hover_color)) {
                    $hoverColorAttr = 'data-svg-hover-color="' . $svg_icon_hover_color . '"';
                }
                $colorAttr = 'data-svg-color="currentColor"';
                if (!empty($svg_icon_color)) {
                    $colorAttr = 'data-svg-color="' . $svg_icon_color . '"';
                }

                if (strpos($svg_icon, 'fill="none"')) {
                    $svg_icon_color = !empty($svg_icon_color) ? 'color:' . esc_xml($svg_icon_color) . ';fill:none;' : 'color: currentColor';
                } else {
                    $svg_icon_color = !empty($svg_icon_color) ? 'fill:' . esc_xml($svg_icon_color) . ';' : 'fill: currentColor';
                }

                if ($custom_image == "on") {
                    $svg_icon_color = "";
                }
                if($svg_top_margin=="0"){
                    $position = ' position: relative; bottom: ' . $svg_bottom_margin . 'px; ';
                    $margin = ' margin: 0 ' . $svg_right_margin . 'px 0 '. $svg_left_margin . 'px; ';
                }
                else{
                    $position = ' position: relative; top: ' . $svg_top_margin . 'px; ';
                    $margin = ' margin: 0 ' . $svg_right_margin . 'px ' . $svg_bottom_margin  . 'px '. $svg_left_margin . 'px; ';
                }


                $style = 'style="width: ' . $svg_icon_size . 'px; height: auto; vertical-align: middle;' . $position . $margin . $svg_icon_color . '"';

                if ($custom_image == "on") {
                    $colorAttr = $hoverColorAttr = "";

                    if (!empty($image)){
                        $hoverData = "";
                        if (!empty($hover_image)){
                            $hoverData = 'hover-image="'.$hover_image.'" main-svg-image="'.$image.'"';
                        }
                        $svg_icon = '<img src="' . $image . '" ' . $style . ' '.$hoverData.'/>';
                    }
                    else{
                        $svg_icon = '';
                    }

                } else {
                    if(strpos($svg_icon, '<svg') !== false){
                        $svg_icon = str_replace('<svg', '<svg ' . $style, $svg_icon);
                    }
                    else{
                        $class = "";
                        if ($igroup =="country-flags"){
                            $class = 'no-hover';
                        }
                        $svg_icon = str_replace('<img', '<img class="'.$class.'" ' . $style, $svg_icon);
                    }
                }

                //$title = '<span class="dm-menu-item-title">' . $item->title . '</span>';
                $title = $item->title;
                if ($svg_position == "Left") {
                    $title = '<span class="dm-menu-icon icon-on-left" ' . $colorAttr . ' ' . $hoverColorAttr . '>' . $svg_icon . '</span>' . $title;
                } elseif ($svg_position == "Right") {
                    $title = $title . '<span class="dm-menu-icon icon-on-right">' . $svg_icon . '</span>';
                }
                /*elseif ($svg_position=="Top"){
                    $title = '<span class="dm-menu-icon icon-on-top">'.$svg_icon.'</span>' . $title;
                }
                elseif ($svg_position=="Bottom"){
                    $title = $title . '<span class="dm-menu-icon icon-on-bottom">'.$svg_icon.'</span>';
                }*/
                $item->title = $title;
            }

        }
        return $items;
    }
}