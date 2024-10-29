<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class GetSvgs
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
        add_action("wp_ajax_dm_get_icon_svgs", [$this, 'ajax']);
    }

    public function ajax()
    {
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }

        $now = isset($_POST['next']) && !empty($_POST['next']) ? sanitize_key($_POST['next']) : '0';
        $nowIcons = intval($now) * 1000;
        $next = intval($now) + 1;

        $response = 0;
        $completed = false;
        $iconGroup = isset($_POST['iconGroup']) ? sanitize_file_name($_POST['iconGroup']) : "";

        global $wpdb;

        // Generate cache key based on iconGroup and pagination
        $cache_key = 'rc_icons_' . md5($iconGroup . '_' . $nowIcons);
        $icons = wp_cache_get($cache_key);

        if ($icons === false) {
            // Direct database call with prepared statement to prevent SQL injection
            $icons = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}rc_icons WHERE igroup = %s LIMIT %d, 1000",
                $iconGroup, $nowIcons
            ));

            // Store the results in cache
            wp_cache_set($cache_key, $icons, '', 3600); // Cache for 1 hour
        }

        $iconsArray = array();
        if (!empty($icons)) {
            foreach ($icons as $icon) {
                if ($icon->igroup == "country-flags") {
                    $icon->svg = $this->admin->getFlagImageUrl($icon->id);
                }
                $iconsArray[] = $icon;
            }
        }
        $response = $iconsArray;

        // Generate a cache key for the total icons count
        $total_cache_key = 'rc_icons_count_' . md5($iconGroup);
        $total_icons = wp_cache_get($total_cache_key);

        if ($total_icons === false) {
            $total_icons = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}rc_icons WHERE igroup = %s",
                $iconGroup
            ));

            // Store the total count in cache
            wp_cache_set($total_cache_key, $total_icons, '', 3600*24*7); // Cache for 7 days
        }

        if (($next * 1000) > $total_icons) {
            $completed = true;
        }

        echo wp_json_encode(array(
            'success' => true,
            'status' => 'success',
            'response' => $response,
            'nowIcons' => $nowIcons,
            'nextIcons' => $next,
            'isCompleted' => $completed,
            'total' => $total_icons
        ));

        die();
    }


}
