<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;

class clearAllIconData
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_clearAllIconData", [$this, 'ajax']);
    }

    public function ajax()
    {


        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }


        $allposts = get_posts( 'numberposts=-1&post_type=nav_menu_item&post_status=any' );

        foreach( $allposts as $postinfo ) {
            delete_post_meta( $postinfo->ID, '_svg_icon' );
            delete_post_meta( $postinfo->ID, '_svg_position' );
            delete_post_meta( $postinfo->ID, '_svg_icon_size' );
            delete_post_meta( $postinfo->ID, '_svg_icon_color' );
            delete_post_meta( $postinfo->ID, '_svg_icon_hover_color' );
            delete_post_meta( $postinfo->ID, '_svg_top_margin' );
            delete_post_meta( $postinfo->ID, '_svg_right_margin' );
            delete_post_meta( $postinfo->ID, '_svg_bottom_margin' );
            delete_post_meta( $postinfo->ID, '_svg_left_margin' );
            delete_post_meta( $postinfo->ID, '_custom_image' );
            delete_post_meta( $postinfo->ID, '_svg_tag' );
            delete_post_meta( $postinfo->ID, '_svg_group' );
            wp_delete_attachment( $postinfo->ID );
        }


        echo wp_json_encode(array('success' => true, 'status' => 'success', 'response' => 'dataCleared'));
        die();
    }

}
