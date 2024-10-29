<?php
namespace RecorpAdvancedMenuIcons\AjaxRequests;
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
class getIcons
{

    private $ajax;

    /**
     * PreloadCaches constructor.
     */
    public function __construct($a)
    {
        $this->ajax = $a;
        add_action("wp_ajax_getIcons", [$this, 'ajax']);
    }

    public function ajax()
    {

        /*$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";
        if (!wp_verify_nonce($nonce, "rc-nonce")) {
            echo json_encode(array('success' => false, 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }*/
        $this->ajax->nonceCheck();
        $fileId = isset($_POST['fileId']) && !empty($_POST['fileId']) ? sanitize_key($_POST['fileId']) : '1';

        global $wpdb;
        if ($fileId=="1"){
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rc_icons");
        }

        $args = array(
            'timeout'     => 300,
            'sslverify' => false
        );
        $response = wp_remote_get('http://api.myrecorp.com/resources/ami-icons/wp_rc_icons-'.$fileId.'.json', $args);

        if (wp_remote_retrieve_response_code($response) == 200){
            $iconsData = wp_remote_retrieve_body($response);
            $iconsData = json_decode($iconsData);

            if (isset($iconsData[2]->data) && $iconsData[2]->data !== null){
                foreach ($iconsData[2]->data as $icon) {

                    $wpdb->insert(
                        $wpdb->prefix . 'rc_icons',
                        array(
                            'igroup' => $icon->igroup,
                            'tag' => $icon->tag,
                            'svg' => $icon->svg,
                        ),
                        array(
                            '%s',
                            '%s',
                            '%s',
                        )
                    );
                }

                if ($fileId=="4"){
                    $total = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}rc_icons");
                    if ($total>18000){
                        update_option('ami_icons_downloaded', true);
                    }
                }

                echo json_encode(array('success' => true, 'status' => 'success', 'response' => 'done'));
            }
            else{
                echo json_encode(array('success' => false, 'status' => 'success', 'response' => 'not done'));
            }
        }
        else{
            echo json_encode(array('success' => false, 'status' => 'something_wrong', 'response' => 'not done'));
        }


        die();
    }

}
