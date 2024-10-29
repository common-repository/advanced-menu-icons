<?php
namespace RecorpAdvancedMenuIcons;

class RecorpAdvancedMenuIcons
{
    public function run()
    {
        $this->require_dependencies();
    }

    public function require_dependencies()
    {
        //This class is dependent for all admin functionalities
        require RAMI_DIR_PATH . 'includes/class-IconsGroups.php';
        require RAMI_DIR_PATH . 'includes/class-MenuItemFields.php';
        require RAMI_DIR_PATH . 'includes/class-menuIconInFrontend.php';
        require RAMI_DIR_PATH . 'includes/class-SaveMenuItems.php';
        require RAMI_DIR_PATH . 'includes/class-MenuAccordionSection.php';
        require RAMI_DIR_PATH . 'admin/class-RecorpAdvancedMenuIcons_Admin.php';
        new RecorpAdvancedMenuIcons_Admin();
    }
}