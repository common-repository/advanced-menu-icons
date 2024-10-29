<?php
namespace RecorpAdvancedMenuIcons;

class PluginDeactivator{

    public function deactivator()
    {
        $this->removeTable();
    }

    public function removeTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rc_icons';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        delete_option('rami_wp_plugin_activation_date');
        delete_option('rami__settings_data');

    }
}