<?php


namespace RecorpAdvancedMenuIcons;


class MenuItemFields
{

    /**
     * Add custom fields to menu items in the admin.
     *
     * @param int $item_id The ID of the menu item being edited.
     * @param object $item The menu item object.
     * @param int $depth The depth of the menu item.
     * @param object $args The menu item arguments.
     * @since    1.0.0
     * @access   public
     */

    public function add_menu_item_fields($item_id, $item, $depth, $args)
    {

        $settingsData = get_option('rami__settings_data', array());
        $default_svg_icon_color = isset($settingsData['svg_icon_color']) ? esc_attr($settingsData['svg_icon_color']) : "#595f68";
        $default_svg_icon_hover_color = isset($settingsData['svg_icon_hover_color']) ? esc_attr($settingsData['svg_icon_hover_color']) : "#000000";
        $default_svg_position = isset($settingsData['svg_icon_position']) ? esc_attr($settingsData['svg_icon_position']) : "Left";
        $default_svg_icon_size = isset($settingsData['svg_icon_size']) ? esc_attr($settingsData['svg_icon_size']) : "25";
        $default_svg_top_margin = isset($settingsData['svg_margin_top']) ? esc_attr($settingsData['svg_margin_top']) : "0";
        $default_svg_right_margin = isset($settingsData['svg_margin_right']) ? esc_attr($settingsData['svg_margin_right']) : "5";
        $default_svg_bottom_margin = isset($settingsData['svg_margin_bottom']) ? esc_attr($settingsData['svg_margin_bottom']) : "0";
        $default_svg_left_margin = isset($settingsData['svg_margin_left']) ? esc_attr($settingsData['svg_margin_left']) : "0";

        // Retrieve and sanitize various meta values for the menu item.
        $svg_icon = $this->get_sanitized_meta($item_id, '_svg_icon', 'html_entity_decode');
        $svg_position = $this->get_sanitized_meta($item_id, '_svg_position', 'sanitize_text_field', $default_svg_position);
        $svg_icon_size = $this->get_sanitized_meta($item_id, '_svg_icon_size', 'esc_attr', $default_svg_icon_size);
        $svg_icon_color = $this->get_sanitized_meta($item_id, '_svg_icon_color', 'esc_xml', $default_svg_icon_color);
        $svg_icon_hover_color = $this->get_sanitized_meta($item_id, '_svg_icon_hover_color', 'esc_xml', $default_svg_icon_hover_color);
        $svg_top_margin = $this->get_sanitized_meta($item_id, '_svg_top_margin', 'esc_attr', $default_svg_top_margin);
        $svg_right_margin = $this->get_sanitized_meta($item_id, '_svg_right_margin', 'esc_attr', $default_svg_right_margin);
        $svg_bottom_margin = $this->get_sanitized_meta($item_id, '_svg_bottom_margin', 'esc_attr', $default_svg_bottom_margin);
        $svg_left_margin = $this->get_sanitized_meta($item_id, '_svg_left_margin', 'esc_attr', $default_svg_left_margin);
        $custom_image = $this->get_sanitized_meta($item_id, '_custom_image', 'esc_attr', 'off');

        $svg_tag = $this->get_sanitized_meta($item_id, '_svg_tag', 'esc_attr', '');
        $svg_group = $this->get_sanitized_meta($item_id, '_svg_group', 'esc_attr', '');
        $svg_id = $this->get_sanitized_meta($item_id, '_svg_id', 'esc_attr', '');

        // Determine the SVG icon color based on its content.
        $svg_icon_color2 = '';
        if (strpos($svg_icon, 'fill="none"')) {
            $svg_icon_color2 = !empty($svg_icon_color) ? 'color:' . esc_xml($svg_icon_color) . ';fill:none;' : '';
        } else {
            $svg_icon_color2 = !empty($svg_icon_color) ? 'fill:' . esc_xml($svg_icon_color) . ';' : '';
        }

        if ($custom_image == "on") {
            $svg_icon_color2 = "";
        }

        // Create the style attribute.
        $style = 'style="width: ' . esc_attr($svg_icon_size) . 'px; height: auto; vertical-align: middle; margin: '
            . esc_attr($svg_top_margin) . 'px ' . esc_attr($svg_right_margin) . 'px '
            . esc_attr($svg_bottom_margin) . 'px ' . esc_attr($svg_left_margin) . 'px; '
            . esc_attr($svg_icon_color2) . '"';
        $svg_icon2 = str_replace('<svg', '<svg ' . $style, $svg_icon);

        $type = $item->type;

        $custom_image_id = get_post_meta($item_id, '_custom_image_id', true);
        $image_id = !empty($custom_image_id) ? esc_attr($custom_image_id) : 0;

        $hover_image_id = get_post_meta($item_id, '_hover_image_id', true);
        $hover_image_id = !empty($hover_image_id) ? esc_attr($hover_image_id) : 0;

        wp_nonce_field('save_menu_item_fields_nonce_action', '_menu_item_fields_nonce');
        ?>
        <!--Output HTML for different fields based on the menu item type.-->
        <div class="advanced-menu-icon-section description  description-wide" style="display: block">
            <h2 style="margin-top: 6px;">Menu icon settings</h2>

            <label for="upload-image-icon-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Upload image icon', 'advanced-menu-icons'); ?>
                <input class="upload-image-icon" type="checkbox"
                       id="upload-image-icon-<?php echo esc_attr($item_id); ?>"
                       name="upload-image-icon[<?php echo esc_attr($item_id); ?>]" <?php checked($custom_image, 'on'); ?>>
            </label>

            <div class="dm-custom-image-section"
                 style="display: <?php echo $custom_image == "on" ? 'block' : 'none'; ?>">
                <div class="rami-main-image-icon">
                    <h4 style="margin-bottom: 10px;"><?php esc_html_e('Image Icon', 'advanced-menu-icons'); ?></h4>
                    <?php if ($image = wp_get_attachment_image_url($image_id, 'medium')) : ?>
                        <a href="#" class="rc-custom-image-upload">
                            <img src="<?php echo esc_url($image) ?>" <?php echo wp_kses_post($style); ?>/>
                        </a>
                        <a href="#" class="rc-custom-image-remove"><?php esc_html_e('Remove image', 'advanced-menu-icons'); ?></a>
                        <input type="hidden" name="custom-image-id[<?php echo esc_attr($item_id); ?>]"
                               value="<?php echo absint($image_id) ?>">
                    <?php else : ?>
                        <a href="#" class="button rc-custom-image-upload"><?php esc_html_e('Upload image', 'advanced-menu-icons'); ?></a>
                        <a href="#" class="rc-custom-image-remove" style="display:none"><?php esc_html_e('Remove image', 'advanced-menu-icons'); ?></a>
                        <input type="hidden" name="custom-image-id[<?php echo esc_attr($item_id); ?>]" value="">
                    <?php endif; ?>
                </div>

                <div class="rami-hover-image-icon" style="margin-top: 15px;">

                    <h4 style="margin-bottom: 10px;"><?php esc_html_e('Hover Image Icon', 'advanced-menu-icons'); ?></h4>
                    <?php if ($image = wp_get_attachment_image_url($hover_image_id, 'medium')) : ?>
                        <a href="#" class="rc-hover-image-upload" style="text-decoration: none;">
                            <img src="<?php echo esc_url($image) ?>" <?php echo wp_kses_post($style); ?>/>
                        </a>
                        <a href="#" class="rc-custom-image-remove"><?php esc_html_e('Remove image', 'advanced-menu-icons'); ?></a>
                        <input type="hidden" name="hover-image-id[<?php echo esc_attr($item_id); ?>]"
                               value="<?php echo absint($hover_image_id) ?>">
                    <?php else : ?>
                        <a href="#" class="button rc-hover-image-upload"><?php esc_html_e('Upload hover image', 'advanced-menu-icons'); ?></a>
                        <a href="#" class="rc-hover-image-remove" style="display:none"><?php esc_html_e('Remove image', 'advanced-menu-icons'); ?></a>
                        <input type="hidden" name="hover-image-id[<?php echo esc_attr($item_id); ?>]" value="">
                    <?php endif; ?>
                </div>


                <div class="icons-urls" style="margin-top: 10px;">
                    <div class="url-desc">
                        <i><?php esc_html_e('Download svg icons from these url', 'advanced-menu-icons'); ?></i>
                    </div>
                    <a href="https://www.flaticon.com/icon-fonts-most-downloaded" target="_blank" style="margin-right: 5px;"><?php esc_html_e('Flaticon', 'advanced-menu-icons'); ?></a>
                    <a href="https://www.svgrepo.com/" target="_blank"><?php esc_html_e('SVGrepo', 'advanced-menu-icons'); ?></a>
                </div>

                <!--https://www.flaticon.com/icon-fonts-most-downloaded-->

            </div>


            <div class="svg-icon-section" style="display: <?php echo $custom_image !== "on" ? 'block' : 'none'; ?>">
                <label>
                    <?php esc_html_e('SVG Icon', 'advanced-menu-icons'); ?>
                </label>
<!--
                <a class="select-svg-image" data-dm-popup-open="dm-popup-1"
                   href="#"><?php /*echo !empty($svg_icon2) ? $svg_icon2 : esc_html('Select an icon', 'advanced-menu-icons'); */?></a>-->
                <!-- HTML !-->
                <button class="button-85 select-svg-image <?php echo !empty($svg_icon2) ? 'has-icon' : ''; ?>" role="button" data-dm-popup-open="dm-popup-1">
                    <?php echo !empty($svg_icon2) ? wp_kses($svg_icon2, rami_svg_alowed_html()) : esc_html__('Select an icon', 'advanced-menu-icons'); ?>
                </button>

                <a class="remove-svg-image" href="#" style="display: <?php echo !empty($svg_icon2) ? 'inline-block' : 'none'; ?>" ><?php esc_html_e('Remove icon', 'advanced-menu-icons'); ?></a>
                <textarea name="svgicon[<?php echo esc_attr($item_id); ?>]"
                          id="svgicon-<?php echo esc_attr($item_id); ?>" cols="30" rows="10"
                          style="display: none;"><?php echo wp_kses(html_entity_decode($svg_icon), rami_svg_alowed_html()); ?></textarea>


                <label for="svg-icon-color-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('SVG Icon Color', 'advanced-menu-icons'); ?>
                </label>

                <input type="text" value="<?php echo esc_xml($svg_icon_color); ?>" class="rami-color-field"
                       id="svg-icon-color-<?php echo esc_attr($item_id); ?>"
                       name="svg-icon-color[<?php echo esc_attr($item_id); ?>]" data-default-color=""/>


                <label for="svg-icon-hover-color-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('SVG Icon Hover Color', 'advanced-menu-icons'); ?>
                </label>

                <input type="text" value="<?php echo esc_xml($svg_icon_hover_color); ?>" class="rami-color-field"
                       id="svg-icon-hover-color-<?php echo esc_attr($item_id); ?>"
                       name="svg-icon-hover-color[<?php echo esc_attr($item_id); ?>]" data-default-color=""/>


            </div>
            <label for="svg-position-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Icon Position', 'advanced-menu-icons'); ?>
                <select name="svg-position[<?php echo esc_attr($item_id); ?>]"
                        id="svg-position-<?php echo esc_attr($item_id); ?>" class="widefat code" style="width: fit-content;">
                    <option value="Left" <?php selected(esc_attr($svg_position), 'Left') ?>><?php esc_html_e('Left', 'advanced-menu-icons'); ?></option>
                    <option value="Right" <?php selected(esc_attr($svg_position), 'Right') ?>><?php esc_html_e('Right', 'advanced-menu-icons'); ?></option>
                    <!--<option value="Top" <?php /*selected(esc_attr($svg_position), 'Top') */ ?>><?php /*esc_html_e('Top', 'advanced-menu-icons'); */ ?></option>
                        <option value="Bottom" <?php /*selected(esc_attr($svg_position), 'Bottom') */ ?>><?php /*esc_html_e('Bottom', 'advanced-menu-icons'); */ ?></option>
                    --></select>
            </label>

            <label for="svg-icon-size-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Icon Size (px)', 'advanced-menu-icons'); ?>
                <input type="number" placeholder="20" name="svg-icon-size[<?php echo esc_attr($item_id); ?>]"
                       id="svg-icon-size-<?php echo esc_attr($item_id); ?>"
                       value="<?php echo esc_attr($svg_icon_size); ?>" class="widefat code" style="width: fit-content;">
            </label>

            <label for="top-margin-<?php echo esc_attr($item_id); ?>" style="margin-bottom: 0;margin-top: 15px;">
                <?php esc_html_e('Margin (px):', 'advanced-menu-icons'); ?>
            </label>

            <span class="dm-margin-inputs-container">
                <span class="dm-margin-inputs">
                    <label for="top-margin-<?php echo esc_attr($item_id); ?>" class="margin-input">Top</label>

                    <input type="number" id="top-margin-<?php echo esc_attr($item_id); ?>"
                           name="top-margin[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($svg_top_margin); ?>">
                </span>
                <span class="dm-margin-inputs">
                    <label for="right-margin-<?php echo esc_attr($item_id); ?>" class="margin-input">Right</label>
                    <input type="number" id="right-margin-<?php echo esc_attr($item_id); ?>"
                           name="right-margin[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($svg_right_margin); ?>">
                </span>
                <span class="dm-margin-inputs">
                    <label for="bottom-margin-<?php echo esc_attr($item_id); ?>" class="margin-input">Bottom</label>

                    <input type="number" id="bottom-margin-<?php echo esc_attr($item_id); ?>"
                           name="bottom-margin[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($svg_bottom_margin); ?>">
                </span>
                <span class="dm-margin-inputs">
                    <label for="left-margin-<?php echo esc_attr($item_id); ?>" class="margin-input">Left</label>

                    <input type="number" id="left-margin-<?php echo esc_attr($item_id); ?>"
                           name="left-margin[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($svg_left_margin); ?>">
                </span>
            </span>

            <div id="rc-hidden-fields">
                <input type="hidden" name="svg-tag[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($svg_tag); ?>">
                <input type="hidden" name="svg-group[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($svg_group); ?>">
                <input type="hidden" name="svg-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($svg_id); ?>">
                <input type="hidden" name="rami_nonce[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr(wp_create_nonce('rami_nonce')); ?>">
            </div>
        </div>
        <?php
    }

// Helper function to retrieve and sanitize meta values with optional default value.
    private function get_sanitized_meta($item_id, $meta_key, $sanitize_callback, $default = '')
    {
        $meta_value = get_post_meta($item_id, $meta_key, true);
        $sanitized_value = !empty($meta_value) ? call_user_func($sanitize_callback, $meta_value) : $default;
        return $sanitized_value;
    }

}