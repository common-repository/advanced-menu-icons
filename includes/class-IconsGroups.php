<?php


namespace RecorpAdvancedMenuIcons;


class IconsGroups
{

    private $admin;
    /**
     * AMI_IconsGroups constructor.
     */
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    public function menu_item_svg_icons()
    {
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . sanitize_text_field($_SERVER['HTTP_HOST']) . sanitize_url($_SERVER['REQUEST_URI']);

        $sanitized_link = esc_url_raw($actual_link);

        if (strpos($sanitized_link, 'nav-menus.php') == false) {
            return;
        }
/*
        $directory = RAMI_DIR_PATH . 'admin/includes/icons/svg-icons/';

        $iconGroup = $this->admin->getIconsGroups();

        $freeIcons = array(
            '77_essential_icons',
            'font-awesome',
        );*/


        ?>


        <div class="dm-popup" data-dm-popup="dm-popup-1" style="display: none">
            <a class="dm-popup-close" data-dm-popup-close="dm-popup-1" href="#">x</a>
            <div class="dm-popup-inner">
                <div class="search-bar">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <label>
                            <input type="search" class="search-field"
                                   placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'advanced-menu-icons'); ?>"
                                   value="" name="s">
                        </label>
                        <button type="submit" class="search-submit">
                            <span class="dashicons dashicons-search"></span>
                        </button>

                        <?php echo '<a id="search-data-clear" href="#">' . esc_html__( 'Clear', 'advanced-menu-icons' ) . '</a>'; ?>

                    </form>
                </div>


                <div class="accordion_groups_list accordion-column">
                    <ul class="icons-groups-list"></ul>
                </div>

                <div class="rc_accordion accordion-column"></div>


            </div>
        </div>

        <?php

    }
}