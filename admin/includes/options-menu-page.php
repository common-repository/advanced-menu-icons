<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="rami-settings-page-section rami-row">
    <h1 style="margin-top: 5px;display: block;width: 100%;"><?php esc_html_e('Advanced Menu Icons Settings', 'advanced-menu-icons'); ?> <span class="badge badge-dark version"><?php echo esc_html(RAMI_VERSION); ?></span></h1>

    <div class="rami-col-7">
        <?php
        $MenuAccordionSection = new RecorpAdvancedMenuIcons\MenuAccordionSection();
        $menu_settings = $MenuAccordionSection->nav_menu_settings();
        if (!is_null($menu_settings)) {
            echo wp_kses_post($menu_settings);
        }

        ?>
        <a style="float: right;margin-top: -32px;" class="button button-secondary button-large" href="nav-menus.php"><?php esc_html_e('Menu Page', 'advanced-menu-icons'); ?></a>
    </div>
    <div class="rami-col-3 p-10 dev_section">

        <div class="created_by py-2 mt-1 border-bottom"> <?php esc_html_e('Created by', 'advanced-menu-icons'); ?> <a href="https://myrecorp.com"><img src="<?php echo esc_url(RAMI_DIR_URL); ?>admin/assets/images/recorp-logo.png" alt="ReCorp" width="100"></a></div>


        <div class="documentation my-2">
            <a href="https://myrecorp.com/documentation/advanced-menu-icons"><span><?php esc_html_e('Documentation', 'advanced-menu-icons'); ?> </span></a>
        </div>

        <div class="documentation my-2">
            <a href="https://myrecorp.com/support"><span><?php esc_html_e('Support', 'advanced-menu-icons'); ?> </span></a>
        </div>



        <div class="right_side_notice mt-4">
            <?php echo esc_html(do_action('rami_right_side_notice')); ?>
        </div>
    </div>
</div>
