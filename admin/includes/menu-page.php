<?php
namespace RecorpAdvancedMenuIcons;

class AddMenuPage{
    private $admin;
    /**
     * AddMenuPage constructor.
     */
    public function __construct()
    {
            /*Adding admin menu on the admin sidebar*/
            add_action('admin_menu', array($this, 'register_advanced_menu_icons_menu_page') );
    }


    public function register_advanced_menu_icons_menu_page(){

        add_options_page(
            __( 'Advanced Menu Icons', 'advanced-menu-icons' ),
            __( 'Advanced Menu Icons', 'advanced-menu-icons' ),
            'manage_options',
            'advanced_menu_icons',
            array(
                $this,
                'load_admin_dependencies'
            )
        );

    }

    public function load_admin_dependencies(){
        require_once RAMI_DIR_PATH . '/admin/includes/options-menu-page.php';
    }
}