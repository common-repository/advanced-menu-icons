<?php


namespace RecorpAdvancedMenuIcons;


class SaveMenuItems
{

    /**
     * Save custom fields for menu items.
     *
     * @param int $menu_id The ID of the menu.
     * @param int $menu_item_db_id The ID of the menu item being edited.
     * @param array $args The menu item arguments.
     * @since    1.0.0
     * @access   public
     */
    public function save_menu_item_fields($menu_id, $menu_item_db_id, $args)
    {
        if (wp_verify_nonce(sanitize_key($_POST['_menu_item_fields_nonce']), 'save_menu_item_fields_nonce_action')){

            $fields = [
                'svgicon' => '_svg_icon',
                'svg-position' => '_svg_position',
                'svg-icon-size' => '_svg_icon_size',
                'top-margin' => '_svg_top_margin',
                'right-margin' => '_svg_right_margin',
                'bottom-margin' => '_svg_bottom_margin',
                'left-margin' => '_svg_left_margin',
                'svg-icon-color' => '_svg_icon_color',
                'svg-icon-hover-color' => '_svg_icon_hover_color',
                'upload-image-icon' => '_custom_image',
                'custom-image-id'   => '_custom_image_id',
                'hover-image-id'   => '_hover_image_id',
                'svg-tag'           => '_svg_tag',
                'svg-group'         => '_svg_group',
                'svg-id'            => '_svg_id',
            ];


            foreach ($fields as $key => $field) {
                if (isset($_POST[$key][$menu_item_db_id])) {
                    if ($field === '_svg_icon') {
                        update_post_meta($menu_item_db_id, $field, wp_kses( $_POST[$key][$menu_item_db_id], rami_svg_alowed_html() ));
                    } else {
                        update_post_meta($menu_item_db_id, $field, sanitize_text_field($_POST[$key][$menu_item_db_id]));
                    }
                } else {
                    delete_post_meta($menu_item_db_id, $field);
                }
            }
        }

    }

    /**
     * Sanitize SVG markup for front-end display.
     *
     * @param  string $svg SVG markup to sanitize.
     * @return string 	  Sanitized markup.
     */
    function prefix_sanitize_svg( $svg = '' ) {

        return wp_kses( $svg, rami_svg_alowed_html() );
    }


}