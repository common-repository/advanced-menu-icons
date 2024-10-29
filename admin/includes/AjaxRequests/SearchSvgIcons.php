<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class SearchSvgIcons
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_advanced_menu_icon_search", [$this, 'ajax']);
    }

    public function ajax()
    {
        // Verify nonce and user capability
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once(ABSPATH . WPINC . '/pluggable.php');

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator')) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));
            wp_die();
        }

        global $wpdb;

        // Sanitize search input
        $search = isset($_POST['s']) && !empty($_POST['s']) ? sanitize_text_field($_POST['s']) : '';

        // Handle multiple words in search
        if (!empty($search)) {
            $searchWords = explode(' ', $search);
            $searchConditions = array();

            foreach ($searchWords as $word) {
                // Use esc_like to escape special SQL characters
                $searchConditions[] = "tag LIKE '%" . $wpdb->esc_like($word) . "%'";
            }

            // Construct the final search query condition
            $searchQuery = implode(' OR ', $searchConditions);
        } else {
            $searchQuery = "1=1"; // Default to a condition that will always return true (no search term)
        }

        // Global wpdb object for database access
        global $wpdb;

        // Cache key based on search query
        $cache_key = 'rc_icons_search_' . md5($searchQuery);
        $icons = wp_cache_get($cache_key);

        if ($icons === false) {
            // Prepare the SQL query with the dynamically built WHERE condition
            $icons = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rc_icons WHERE {$searchQuery} LIMIT 1000");

            // Cache the results for 7 days
            wp_cache_set($cache_key, $icons, '', 3600 * 24 * 7); // Cache for 1 week
        }

        // Process the retrieved icons
        $iconGroups = array();
        if (!empty($icons)) {
            foreach ($icons as $icon) {
                $iconGroups[$icon->igroup][] = $icon;
            }
        }

        // Fetch settings and determine default icon pack
        $settingsData = get_option('rami__settings_data', array());
        $default_icon_pack = isset($settingsData['default_icon_pack']) ? esc_attr($settingsData['default_icon_pack']) : "font-awesome";

        // Move default icon pack to the beginning of the list
        if (array_key_exists($default_icon_pack, $iconGroups)) {
            $default_icon_pack_icons = $iconGroups[$default_icon_pack];
            unset($iconGroups[$default_icon_pack]);

            $iconGroups = array_merge(array($default_icon_pack => $default_icon_pack_icons), $iconGroups);
        }

        // Send the response back as JSON
        echo wp_json_encode(array(
            'success' => true,
            'status' => 'success',
            'default_icon_pack' => $default_icon_pack,
            'response' => $iconGroups,
            'search' => $search,
            'default-pack' => array_key_exists($default_icon_pack, $iconGroups)
        ));

        wp_die();
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
