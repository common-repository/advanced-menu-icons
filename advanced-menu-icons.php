<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/fl/rayhan1
 * @since             1.0.0
 * @package           Advanced_Menu_Icons
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Menu Icons
 * Plugin URI:        https://myrecorp.com/
 * Description:       Transform your WordPress menus with SVG icons and effortless icon uploads.
 * Version:           1.0.1
 * Author:            RAYHAN KABIR
 * Author URI:        https://www.upwork.com/fl/rayhan1
 * License:           GPL-2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-menu-icons
 * Domain Path:       /languages
 */

use RecorpAdvancedMenuIcons\PluginActivator;
use RecorpAdvancedMenuIcons\PluginDeactivator;

if (!defined('WPINC')) {
    die;
}


/**
 * Activation hook function that runs when the plugin is activated.
 *
 * @since 1.0.0
 */
function recorp_advanced_menu_icons_plugin_activator(){
    // Include the file containing the activation class
    require 'includes/plugin-activator.php';

    // Create an instance of the activation class and call its activator method
    $plugin = new PluginActivator;
    $plugin->activator();
}
// Register the activation hook
register_activation_hook( __FILE__, 'recorp_advanced_menu_icons_plugin_activator' );


if (!function_exists('recorp_advanced_menu_icons_pro')) {
    // Define the version and name of the plugin
    define('RAMI_VERSION', '1.0.1');
    define('RAMI_DIR_PATH', plugin_dir_path(__FILE__));
    define('RAMI_DIR_URL', plugin_dir_url(__FILE__));
    define('RAMI_TEXTDOMAINL', 'advanced-menu-icons');
    define('RAMI_NAME', 'advanced_menu_icons');

    //register_activation_hook(__FILE__, 'rami_save_redirect_option');
    //add_action('admin_init', 'rami_redirect_to_menu');

    /*Redirect to plugin's settings page when plugin will active*/
    function rami_save_redirect_option() {
        add_option('rami_activation_check', true);
    }


    function rami_redirect_to_menu() {
        if (get_option('rami_activation_check', false)) {
            delete_option('rami_activation_check');
            wp_safe_redirect( esc_url_raw("admin.php?page=advanced-menu-icons&welcome=true") );
            exit;

        }
    }


    // Include the file containing the plugin class
    require 'includes/class-RecorpAdvancedMenuIcons.php';


    /**
     * Deactivation hook function that runs when the plugin is deactivated.
     *
     * @since 1.0.0
     */
    function recorp_advanced_menu_icons_plugin_deactivator()
    {
        // Include the file containing the deactivation class
        require 'includes/plugin-deactivator.php';

        // Create an instance of the deactivation class and call its deactivator method
        $plugin = new PluginDeactivator();
        $plugin->deactivator();
    }

    // Register the deactivation hook
    register_deactivation_hook(__FILE__, 'recorp_advanced_menu_icons_plugin_deactivator');

    /**
     * Function that runs when the plugin is loaded.
     *
     * @since 1.0.0
     */
    function recorp_advanced_menu_icons()
    {
        // Create an instance of the plugin class and call its run method
        $plugin = new RecorpAdvancedMenuIcons\RecorpAdvancedMenuIcons();
        $plugin->run();
    }

    // Run the plugin
    recorp_advanced_menu_icons();
}