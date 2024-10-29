<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function rami_advanced_menu_icons_enqueue_scripts() {
    // Register and enqueue an empty JavaScript file to use with inline script
    wp_register_script('advanced-menu-icons-script', '');
    wp_enqueue_script('advanced-menu-icons-script');
}
add_action('admin_enqueue_scripts', 'rami_advanced_menu_icons_enqueue_scripts');

function rami_advanced_menu_icons_cdata() {
    $script = '
        var rami = {
            "ajax_url": "' . esc_url(admin_url('admin-ajax.php')) . '",
            "nonce": "' . esc_attr(wp_create_nonce('rc-nonce')) . '",
            "home_url": "' . esc_url(home_url()) . '",
            "iconsDownloaded": "' . esc_html__( "Icons are successfully downloaded!", "advanced-menu-icons" ) . '",
            "iconsDownloading": "' . esc_html__( "Icons are downloading, please wait...", "advanced-menu-icons" ) . '",
            "select_an_icon": "' . esc_html__( "Select an icon", "advanced-menu-icons" ) . '",
            "something_wrong": "' . esc_html__( "Something went wrong! Please try again later.", "advanced-menu-icons" ) . '"
        };
    ';
    wp_add_inline_script('advanced-menu-icons-script', $script);
}
add_action('admin_enqueue_scripts', 'rami_advanced_menu_icons_cdata');
