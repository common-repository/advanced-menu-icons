<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class SaveMenuSettings
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_save_rami_settings", [$this, 'ajax']);
    }

    public function ajax()
    {

        /*$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        if (!wp_verify_nonce($nonce, "rc-nonce")) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }*/
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }

        $default_icon_pack = isset($_POST['default_icon_pack']) ? sanitize_text_field($_POST['default_icon_pack']) : "font-awesome";
        $svg_icon_color = isset($_POST['svg_icon_color']) ? sanitize_text_field($_POST['svg_icon_color']) : "#595f68";
        $svg_icon_hover_color = isset($_POST['svg_icon_hover_color']) ? sanitize_text_field($_POST['svg_icon_hover_color']) : "#000000";
        $svg_icon_position = isset($_POST['svg_icon_position']) ? sanitize_key($_POST['svg_icon_position']) : "Left";
        $svg_icon_size = isset($_POST['svg_icon_size']) ? sanitize_key($_POST['svg_icon_size']) : "25";
        $svg_margin_top = isset($_POST['svg_margin_top']) ? sanitize_key($_POST['svg_margin_top']) : "0";
        $svg_margin_right = isset($_POST['svg_margin_right']) ? sanitize_key($_POST['svg_margin_right']) : "0";
        $svg_margin_bottom = isset($_POST['svg_margin_bottom']) ? sanitize_key($_POST['svg_margin_bottom']) : "0";
        $svg_margin_left = isset($_POST['svg_margin_left']) ? sanitize_key($_POST['svg_margin_left']) : "5";

        $settingsData = array(
            'default_icon_pack' => $default_icon_pack,
            'svg_icon_color' => $svg_icon_color,
            'svg_icon_hover_color' => $svg_icon_hover_color,
            'svg_icon_position' => $svg_icon_position,
            'svg_icon_size' => $svg_icon_size,
            'svg_margin_top' => $svg_margin_top,
            'svg_margin_right' => $svg_margin_right,
            'svg_margin_bottom' => $svg_margin_bottom,
            'svg_margin_left' => $svg_margin_left
        );

        update_option('rami__settings_data', $settingsData);

        echo wp_json_encode(array('success' => true, 'status' => 'success', 'response' => ''));

        die();
    }

}
