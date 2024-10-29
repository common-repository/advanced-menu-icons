<?php
namespace RecorpAdvancedMenuIcons;

class PluginActivator{

    public function activator()
    {
        /*if ( is_plugin_active( 'advanced-menu-icons-pro/advanced-menu-icons.php' ) ) {
            deactivate_plugins( 'advanced-menu-icons-pro/advanced-menu-icons.php' );
        }
        if ( is_plugin_active( 'advanced-menu-icons-pro-premium/advanced-menu-icons.php' ) ) {
            deactivate_plugins( 'advanced-menu-icons-pro-premium/advanced-menu-icons.php' );
        }*/

        update_option( 'rami_wp_plugin_activation_date', time() );
        $this->createTable();
    }

    public function createTable()
    {
        global $wpdb;

        $table_name = $wpdb->prefix.'rc_icons';
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

        if ( ! $wpdb->get_var( $query ) == $table_name ) {

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE " . $table_name ." (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              igroup text NOT NULL,
              tag text NOT NULL,
              svg text NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";


            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }

}