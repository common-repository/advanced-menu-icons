<?php


namespace RecorpAdvancedMenuIcons;


class MenuAccordionSection
{
    /**
     * MenuAccordionSection constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'custom_nav_menu_metabox']);
    }


    /**
     * Add a custom meta box to the nav-menus.php page.
     *
     * @since    1.0.0
     * @access   public
     */
    function custom_nav_menu_metabox() {
        add_meta_box(
            'advanced_menu_icon_section_metabox', // Unique ID for the metabox
            'Advanced Menu Icon Settings',       // Title of the metabox
            [$this, 'nav_menu_settings'], // Callback function to display content
            'nav-menus',             // Admin page where the metabox should be displayed
            'side',                  // Context (side, normal, advanced)
            'low'                   // Priority (high, core, default, low)
        );
    }

    /**
     * Add a custom meta box to the nav-menus.php page.
     *
     * @since    1.0.0
     * @access   public
     */
    function nav_menu_settings()
    {
        $settings_key = "rami_";

        $settingsData = get_option('rami__settings_data', array());
        $default_icon_pack = isset($settingsData['default_icon_pack']) ? esc_attr($settingsData['default_icon_pack']) : "77_essential_icons";
        $svg_icon_color = isset($settingsData['svg_icon_color']) ? esc_attr($settingsData['svg_icon_color']) : "#595f68";
        $svg_icon_hover_color = isset($settingsData['svg_icon_hover_color']) ? esc_attr($settingsData['svg_icon_hover_color']) : "#000000";
        $svg_position = isset($settingsData['svg_icon_position']) ? esc_attr($settingsData['svg_icon_position']) : "Left";
        $svg_icon_size = isset($settingsData['svg_icon_size']) ? esc_attr($settingsData['svg_icon_size']) : "25";
        $svg_top_margin = isset($settingsData['svg_margin_top']) ? esc_attr($settingsData['svg_margin_top']) : "0";
        $svg_right_margin = isset($settingsData['svg_margin_right']) ? esc_attr($settingsData['svg_margin_right']) : "0";
        $svg_bottom_margin = isset($settingsData['svg_margin_bottom']) ? esc_attr($settingsData['svg_margin_bottom']) : "5";
        $svg_left_margin = isset($settingsData['svg_margin_left']) ? esc_attr($settingsData['svg_margin_left']) : "0";

        $iconGroups = array(
            'font-awesome',
            '77_essential_icons',
            'css.gg',
            'iconoir',
            'tabler-icons',
            'system-uicons',
            '150-outlined-icons',
            'ant-design-icons',
            'boxicons',
            'clarity-icons',
            'coreui-icons',
            'elegant-font',
            'heroicons',
            'material-design',
            'country-flags',
        );

        ?>
        <!--Output HTML for different fields based on the menu item type.-->
        <div class="advanced-menu-icon-section description  description-wide" style="display: block">
            <div class="svg-icon-section">

                <label for="default-icon-pack-<?php echo esc_attr($settings_key); ?>">
                    <?php esc_html_e('Defualt Icon Pack', 'advanced-menu-icons'); ?>
                </label>

                <select class="default-icon-pack" id="default-icon-pack-<?php echo esc_attr($settings_key); ?>" name="default-icon-pack[<?php echo esc_attr($settings_key); ?>]">
                    <?php
                        foreach ($iconGroups as $iconGroup) {
                            echo '<option value="' . esc_attr($iconGroup) . '" ' . selected(esc_attr($default_icon_pack), esc_attr($iconGroup), false) . '>' . esc_html($this->getFontName($iconGroup)) . '</option>';
                        }
                    ?>
                </select>


                <label for="svg-icon-color-<?php echo esc_attr($settings_key); ?>">
                    <?php esc_html_e('Defualt SVG Icon Color', 'advanced-menu-icons'); ?>
                </label>

                <input type="text" value="<?php echo esc_xml($svg_icon_color); ?>" class="rami-color-field"
                       id="svg-icon-color-<?php echo esc_attr($settings_key); ?>"
                       name="svg-icon-color[<?php echo esc_attr($settings_key); ?>]" data-default-color=""/>


                <label for="svg-icon-hover-color-<?php echo esc_attr($settings_key); ?>">
                    <?php esc_html_e('Default SVG Icon Hover Color', 'advanced-menu-icons'); ?>
                </label>

                <input type="text" value="<?php echo esc_xml($svg_icon_hover_color); ?>" class="rami-color-field"
                       id="svg-icon-hover-color-<?php echo esc_attr($settings_key); ?>"
                       name="svg-icon-hover-color[<?php echo esc_attr($settings_key); ?>]" data-default-color=""/>
            </div>
            <label for="svg-position-<?php echo esc_attr($settings_key); ?>">
                <?php esc_html_e('Default Icon Position', 'advanced-menu-icons'); ?>
                <select name="svg-position[<?php echo esc_attr($settings_key); ?>]"
                        id="svg-position-<?php echo esc_attr($settings_key); ?>" class="widefat code" style="width: fit-content;">
                    <option value="Left" <?php selected(ucfirst(esc_attr($svg_position)), 'Left') ?>><?php esc_html_e('Left', 'advanced-menu-icons'); ?></option>
                    <option value="Right" <?php selected(ucfirst(esc_attr($svg_position)), 'Right') ?>><?php esc_html_e('Right', 'advanced-menu-icons'); ?></option>
                    <!--<option value="Top" <?php /*selected(esc_attr($svg_position), 'Top') */ ?>><?php /*esc_html_e('Top', 'advanced-menu-icons'); */ ?></option>
                        <option value="Bottom" <?php /*selected(esc_attr($svg_position), 'Bottom') */ ?>><?php /*esc_html_e('Bottom', 'advanced-menu-icons'); */ ?></option>
                    --></select>
            </label>

            <label for="svg-icon-size-<?php echo esc_attr($settings_key); ?>">
                <?php esc_html_e('Default Icon Size (px)', 'advanced-menu-icons'); ?>
                <input type="number" placeholder="20" name="svg-icon-size[<?php echo esc_attr($settings_key); ?>]"
                       id="svg-icon-size-<?php echo esc_attr($settings_key); ?>"
                       value="<?php echo esc_attr($svg_icon_size); ?>" class="widefat code" style="width: fit-content;">
            </label>

            <label for="top-margin-<?php echo esc_attr($settings_key); ?>" style="margin-bottom: 0;margin-top: 15px;">
                <?php esc_html_e('Default Margin (px):', 'advanced-menu-icons'); ?>
            </label>

            <span class="dm-margin-inputs-container">
                <span class="dm-margin-inputs">
                    <label for="top-margin-<?php echo esc_attr($settings_key); ?>" class="margin-input">Top</label>

                    <input type="number" id="top-margin-<?php echo esc_attr($settings_key); ?>"
                           name="top-margin[<?php echo esc_attr($settings_key); ?>]" value="<?php echo esc_attr($svg_top_margin); ?>">
                </span>
                <span class="dm-margin-inputs">
                    <label for="right-margin-<?php echo esc_attr($settings_key); ?>" class="margin-input">Right</label>

                    <input type="number" id="right-margin-<?php echo esc_attr($settings_key); ?>"
                           name="right-margin[<?php echo esc_attr($settings_key); ?>]"
                           value="<?php echo esc_attr($svg_right_margin); ?>">
                </span>
                <span class="dm-margin-inputs">
                    <label for="bottom-margin-<?php echo esc_attr($settings_key); ?>" class="margin-input">Bottom</label>

                    <input type="number" id="bottom-margin-<?php echo esc_attr($settings_key); ?>"
                           name="bottom-margin[<?php echo esc_attr($settings_key); ?>]"
                           value="<?php echo esc_attr($svg_bottom_margin); ?>">
                </span>
                <span class="dm-margin-inputs">
                    <label for="left-margin-<?php echo esc_attr($settings_key); ?>" class="margin-input">Left</label>

                    <input type="number" id="left-margin-<?php echo esc_attr($settings_key); ?>"
                           name="left-margin[<?php echo esc_attr($settings_key); ?>]"
                           value="<?php echo esc_attr($svg_left_margin); ?>">
                </span>
            </span>

            <div class="action-svg-menu-settings" style="margin-top: 25px;">
                <input type="submit" name="save_menu" id="save_menu_footer" class="button button-primary button-large menu-save" value="Save Icon Settings">
                <input type="submit" name="reset_menu_settings" id="reset_menu_settings" class="button button-danger button-large reset-icon-settings" value="Reset" style="color: #b32d2e;border: none;background: none;text-decoration: underline;">
            </div>


        </div>
        <?php
    }

    public function getFontName($key)
    {
        $name = '';
        if (strpos($key, '-') !== false){
            $names = explode('-', $key);
            $name = ucwords(implode(' ', $names));
        }
        else if (strpos($key, '_') !== false){
            $names = explode('_', $key);
            $name = ucwords(implode(' ', $names));
        }
        else{
            $name = ucwords($key);
        }
        return $name;
    }
}

// Initialize MenuAccordionSection
new MenuAccordionSection();