<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class saveFlagIcon
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
        add_action("wp_ajax_saveFlagToDb", [$this, 'ajax']);
    }
    public function ajax()
    {
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }
        $svgGroup = isset($_POST['svgGroup']) && !empty($_POST['svgGroup']) ? sanitize_key($_POST['svgGroup']) : '';
        $iconId = isset($_POST['iconId']) && !empty($_POST['iconId']) ? sanitize_key($_POST['iconId']) : '';
        $url = isset($_POST['url']) && !empty($_POST['url']) ? esc_url_raw($_POST['url']) : '';

        $wp_upload_dir = wp_get_upload_dir()['basedir'];
        $upload_path = $wp_upload_dir . '/advanced-menu-icons';
        $iconData = "";
        if (file_exists($upload_path . '/'. basename($url))){
            $response = wp_json_encode(array('success' => true, 'flagUrl' => esc_url($this->admin->getFlagImageUrl($iconId))));
        }
        else{
            $response = wp_remote_get($url);
            if (wp_remote_retrieve_response_code($response) == 200) {
                $iconData = wp_remote_retrieve_body($response);

                // Use WordPress Filesystem API to create directory if it doesn't exist
                WP_Filesystem();
                global $wp_filesystem;
                if (!$wp_filesystem->is_dir($upload_path)) {
                    $wp_filesystem->mkdir($upload_path, 0777, true);
                }

                // Use WordPress Filesystem API to write file contents
                $wp_filesystem->put_contents($upload_path . '/' . basename($url), $iconData);

                $response = wp_json_encode(array('success' => true, 'flagUrl' => esc_url($this->admin->getFlagImageUrl($iconId))));
            }

            else {
                $response = wp_json_encode(array('success' => false, 'error' => 'Failed to retrieve icon from the provided URL.'));
            }
        }

        echo esc_html($response);

        die();
    }


}
