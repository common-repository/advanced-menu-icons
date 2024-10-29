<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class getSvgIconsAccordions
{

    private $ajax;
    private $admin;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a, $admin)
    {
        $this->ajax = $a;
        $this->admin = $admin;
        add_action("wp_ajax_getSvgIconsAccordions", [$this, 'ajax']);
    }

    public function ajax()
    {
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }

        $iconGroups = $this->admin->getIconsGroups();
        $type = isset($_POST['type']) && !empty($_POST['type']) ? sanitize_key($_POST['type']) : '';
        global $wpdb;
        $iconGroupsData = array();

        $x = 0;
        foreach ($iconGroups as $iconGroup) {
            $x++;
            if ($x == 1 && $type !== "search_clear" && $type !== "on_load") {
                // Generate cache key based on iconGroup
                $cache_key = 'rc_icons_group_' . md5($iconGroup);
                $icons = wp_cache_get($cache_key);

                if ($icons === false) {
                    // Direct database call with prepared statement to prevent SQL injection
                    $icons = $wpdb->get_results($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}rc_icons WHERE igroup = %s LIMIT 1000",
                        $iconGroup
                    ));

                    // Store the results in cache
                    wp_cache_set($cache_key, $icons, '', 3600*24*7); // Cache for 1 hour
                }

                if (!empty($icons)) {
                    foreach ($icons as $icon) {
                        $iconGroupsData[$iconGroup][] = $icon;
                    }
                }
            } else {
                $iconGroupsData[$iconGroup] = 0;
            }
        }

        echo wp_json_encode(array(
            'success' => true,
            'status' => 'success',
            'response' => $iconGroupsData,
            'type' => $type === "on_load"
        ));
        die();
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
