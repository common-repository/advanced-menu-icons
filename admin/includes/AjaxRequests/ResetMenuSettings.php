<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class ResetMenuSettings
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_reset_rami_settings", [$this, 'ajax']);
    }

    public function ajax()
    {

        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }

        delete_option('rami__settings_data');

        echo wp_json_encode(array('success' => true, 'status' => 'success', 'response' => ''));
        die();
    }

}
