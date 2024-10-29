<?php

namespace RecorpAdvancedMenuIcons;

class ajaxRequests{

    /**
     * ajaxRequests constructor.
     */

    private $admin;
    public function __construct($admin)
    {
        $this->admin = $admin;
        $this->require_ajax_files();
        $this->initAjaxClasses();
    }


    function require_ajax_files(){
        require 'AjaxRequests/GetSvgs.php';
        require 'AjaxRequests/SaveMenuSettings.php';
        require 'AjaxRequests/ResetMenuSettings.php';
        require 'AjaxRequests/SearchSvgIcons.php';
        require 'AjaxRequests/getSvgIconsAccordions.php';
        require 'AjaxRequests/clearAllIconData.php';
        require 'AjaxRequests/saveFlagIcon.php';
        require 'AjaxRequests/getIcons.php';
    }

    function initAjaxClasses(){
        new AjaxRequests\GetSvgs($this, $this->admin);
        new AjaxRequests\SaveMenuSettings($this);
        new AjaxRequests\ResetMenuSettings($this);
        new AjaxRequests\SearchSvgIcons($this);
        new AjaxRequests\getSvgIconsAccordions($this, $this->admin);
        new AjaxRequests\clearAllIconData($this);
        new AjaxRequests\saveFlagIcon($this, $this->admin);
        new AjaxRequests\getIcons($this);
    }

    public function nonceCheck()
    {
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        require_once( ABSPATH . WPINC . '/pluggable.php' );

        if (!wp_verify_nonce($nonce, "rc-nonce") || !current_user_can('administrator') ) {
            echo wp_json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }
    }



}
