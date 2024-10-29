<?php

// Declare a namespace for the class
namespace RecorpAdvancedMenuIcons;

class RecorpAdvancedMenuIcons_Admin{

    // Declare a protected property to store the settings key
    protected $settingsKey = "recorp_advanced_menu_icons__";

    // Define the constructor method
    private IconsGroups $IconsGroups;
    private MenuItemFields $menuItemFields;
    private IconInFrontend $iconInFrontend;
    private SaveMenuItems $saveMenuItems;

    //public $iconGroups;

    /**
     * @return $iconGroups
     */
    public function getIconsGroups()
    {
        $iconGroups = array(
            'font-awesome',
            'heroicons',
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
            'material-design',
            'country-flags',
        );

        $settingsData = get_option('rami__settings_data', array());
        $default_icon_pack = isset($settingsData['default_icon_pack']) ? esc_attr($settingsData['default_icon_pack']) : "font-awesome";

        if (array_key_exists($default_icon_pack, $iconGroups)){
            foreach ($iconGroups as $key => $value){
                if ($value == $default_icon_pack) {
                    unset($iconGroups[$key]);
                }
            }

            $iconGroups = array_merge(array($default_icon_pack), $iconGroups);
        }

        return $iconGroups;
    }

    public function __construct()
    {
        // Call the require_admin_dependencies method
        $this->require_admin_dependencies();
        // Call the initWpActions method
        $this->initWpActions();
        // Call the initRequiredClasses method
        $this->initRequiredClasses();

    }
    
    // Define the require_admin_dependencies method
    public function require_admin_dependencies()
    {
        // Require cdata
        require_once 'includes/cdata.php';

        // Require ajax functionalities
        require_once 'includes/ajax-requests.php';

        // Require for adding menu page
        require_once 'includes/menu-page.php';

        // Require all global functions
        require_once 'includes/global_functions.php';
    }

    // Define the initRequiredClasses method
    public function initRequiredClasses()
    {
        // Initialize IconsGroups
        $this->IconsGroups = new IconsGroups($this);

        // Initialize MenuItemFields
        $this->menuItemFields = new MenuItemFields();

        // Initialize IconInFrontend
        $this->iconInFrontend = new IconInFrontend();

        // Initialize SaveMenuItems
        $this->saveMenuItems = new SaveMenuItems();

        // Initialize ajaxRequests
        new ajaxRequests($this);

        // Initialize options menu page
        new AddMenuPage();

    }

    // Define the initWpActions method
    public function initWpActions()
    {
        // Add SVG icons to the admin footer
        add_action('admin_footer', [$this, 'menu_item_svg_icons']);

        // Add custom file types to uploads
        add_filter('upload_mimes', [$this, 'add_file_types_to_uploads']);

        // Enqueue scripts on admin pages
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Enqueue public scripts
        add_action('wp_enqueue_scripts', array($this, 'public_enqueue_scripts'));

        // Add custom fields to WordPress menu items
        add_action('wp_nav_menu_item_custom_fields', array($this, 'add_menu_item_fields'), 2, 4);

        // Add filter to show icons in menu items
        add_filter('wp_get_nav_menu_items', [$this, 'icon_in_frontend'], 10, 3);

        // Add action to save menu item fields on menu item update
        add_action('wp_update_nav_menu_item', array($this, 'save_menu_item_fields'), 10, 3);

    }

    function add_file_types_to_uploads($file_types){
        $new_filetypes = array();
        $new_filetypes['svg'] = 'image/svg+xml';
        $file_types = array_merge($file_types, $new_filetypes );
        return $file_types;
    }

    /**
     * Enqueue necessary scripts for the frontend pages.
     *
     * @since    1.0.1
     * @access   public
     * @param    string    $hook    The current admin page.
     */

    public function public_enqueue_scripts($hook) {
        wp_enqueue_script('svg-icon-scripts', RAMI_DIR_URL . 'admin/assets/js/frontend-svg-icon-scripts.js', array('jquery'), '1.0.0', true);
    }

    public function menu_item_svg_icons()
    {
        $this->IconsGroups->menu_item_svg_icons();
    }

    /**
     * Add custom fields to menu items in the admin.
     *
     * @since    1.0.0
     * @access   public
     * @param    int       $item_id    The ID of the menu item being edited.
     * @param    object    $item       The menu item object.
     * @param    int       $depth      The depth of the menu item.
     * @param    object    $args       The menu item arguments.
     */

    public function add_menu_item_fields($item_id, $item, $depth, $args)
    {
        $this->menuItemFields->add_menu_item_fields($item_id, $item, $depth, $args);
    }

    /**
     * Show menu icond in menu item.
     *
     * @since    1.0.0
     * @access   public
     * @param    array     $items     The array of menu items.
     * @param    object    $menu      The menu object.
     * @param    array     $args      The menu arguments.
     * @return   array                The filtered array of menu items.
     */
    public function icon_in_frontend($items, $menu, $args)
    {
        return $this->iconInFrontend->icon_in_frontend($items, $menu, $args);
    }

    /**
     * Save custom fields for menu items.
     *
     * @since    1.0.0
     * @access   public
     * @param    int       $menu_id         The ID of the menu.
     * @param    int       $menu_item_db_id The ID of the menu item being edited.
     * @param    array     $args            The menu item arguments.
     */

    public function save_menu_item_fields($menu_id, $menu_item_db_id, $args)
    {
        $this->saveMenuItems->save_menu_item_fields($menu_id, $menu_item_db_id, $args);
    }


    /**
     * Set Advanced Menu Icons's settings
     * @since 1.0.0
     *
     * @return bool
     */
    public function setSettings( $settings_name="", $value ="")
    {
        if(!empty($settings_name)){
            $settings_name = $this->settingsKey . $settings_name;
            update_option($settings_name, $value);
        }
        return true;
    }


    /**
     * Retrieves the formatted font name from a given admin page key.
     *
     * This function enqueues necessary scripts for the admin page.
     *
     * @since 1.0.0
     * @access public
     * @param string $key The current admin page key.
     * @return string Formatted font name.
     */
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

    /**
     * Enqueue necessary scripts for the admin page.
     *
     * @since    1.0.0
     * @access   public
     * @param    string    $hook    The current admin page.
     */

    public function enqueue_scripts($hook)
    {
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . sanitize_text_field($_SERVER['HTTP_HOST']) . sanitize_url($_SERVER['REQUEST_URI']);

        $sanitized_link = esc_url_raw($actual_link);

        if ($hook === 'nav-menus.php' || strpos($sanitized_link, 'page=advanced_menu_icons') !== false) {
            if ( ! did_action( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }
            wp_enqueue_style(RAMI_NAME, RAMI_DIR_URL . 'admin/assets/css/advanced-menu-icons.css', array(), RAMI_VERSION);
            wp_enqueue_style('jquery-growl', RAMI_DIR_URL . 'admin/assets/css/jquery.growl.css', array(), RAMI_VERSION);

            /*enqueue js*/
            wp_enqueue_script('growl', plugin_dir_url(__DIR__) . 'admin/assets/js/jquery.growl.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script(RAMI_NAME, RAMI_DIR_URL . 'admin/assets/js/advanced-menu-icons.js', array('jquery', 'wp-color-picker'), RAMI_VERSION, true);

            wp_enqueue_script('getIcons', plugin_dir_url(__DIR__) . 'admin/assets/js/getIcons.js', array('jquery'), '1.0.0', true);

            wp_enqueue_script('get-icon-svgs', plugin_dir_url(__DIR__) . 'admin/assets/js/get-svgs.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('save-menu-settings', plugin_dir_url(__DIR__) . 'admin/assets/js/save-menu-settings.js', array(RAMI_NAME), '1.0.0', true);
            wp_enqueue_script('reset-menu-settings', plugin_dir_url(__DIR__) . 'admin/assets/js/reset-menu-settings.js', array(RAMI_NAME), '1.0.0', true);


            // first check that $hook_suffix is appropriate for your admin page
            wp_enqueue_style( 'wp-color-picker' );

        }
    }

    /**
     * Get Advanced Menu Icons setting
     * @since 1.0.0
     *
     * @return string | array
     */
    public function getSettings( $settings_name="", $default = "")
    {
        $settings_name = $this->settingsKey . $settings_name;
        $rc_sc_settings = get_option($settings_name);

        if(empty($rc_sc_settings) && !empty($default)){
            return $default;
        }

        return $rc_sc_settings;
    }


    /**
     * Remove Advanced Menu Icons setting
     * @since 1.0.0
     *
     * @return bool
     */
    public function removeSettings( $settings_name="")
    {
        $settings_name = $this->settingsKey . $settings_name;
        $rc_sc_settings = delete_option($settings_name);

        if ($rc_sc_settings) {
            return true;
        }
        return false;
    }


    /**
     * Remove all Advanced Menu Icons settings
     * @since 1.0.0
     *
     * @return bool
     */

    public function removeAllSettings()
    {
        global $wpdb;
        $query = "UPDATE {$wpdb->prefix}options SET option_value = '' WHERE option_name LIKE %s";
        $removefromdb = $wpdb->query(
            $wpdb->prepare($query, $this->settingsKey . '%')
        );



        if ($removefromdb) {
            return true;
        }
        return false;
    }

    public function getFlagImageUrl($id)
    {
        global $wpdb;

        // Generate a cache key based on the icon ID
        $cache_key = 'flag_image_url_' . $id;
        $cached_url = wp_cache_get($cache_key);

        if ($cached_url !== false) {
            return $cached_url;
        }

        // Proceed with the database query if the cache is not found
        $icons = $wpdb->get_results($wpdb->prepare("SELECT svg FROM {$wpdb->prefix}rc_icons WHERE id = %d", $id));
        if (!empty($icons)) {
            $url = $icons[0]->svg;
            $wp_upload_dir = wp_get_upload_dir();
            $local_flag_path = $wp_upload_dir['basedir'] . '/advanced-menu-icons/' . basename($url);

            if (file_exists($local_flag_path)) {
                $local_flag_url = $wp_upload_dir['baseurl'] . '/advanced-menu-icons/' . basename($url);
                // Cache the local flag URL
                wp_cache_set($cache_key, $local_flag_url, '', 3600*24*7); // Cache for 1 hour
                return $local_flag_url;
            } else {
                // Cache the remote URL
                wp_cache_set($cache_key, $url, '', 3600*24*7); // Cache for 1 hour
                return $url;
            }
        }

        return ''; // Return an empty string if no icon is found
    }


}



