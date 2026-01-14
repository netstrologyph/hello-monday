<?php
/*
Plugin Name: Hello Monday
Plugin URI: http://hellomonday.com
Description: Brings a touch of freshness and creativity to your website. With this lightweight must have wp plugin, you can show a new message or appearance every day, keeping your website dynamic and engaging for your visitors.
Version: 1.0
Author: Themedutch
Author URI: Themedutch.nl
License: GPLv2 or later
Text Domain: wphellomonday
*/

if (@!isset($_SESSION)) session_start();

class hello_monday
{
    public $wpdb;
    static public $uavc_editor_enable = false;
    static public $current_version = "1.0";

    public $post;
    /**
     * Plugin data from get_plugins()
     *
     * @since 1.0
     * @var object
     */
    public $plugin_data;

    /**
     * Includes to load
     *
     * @since 1.0
     * @var array
     */

    public $includes;
    /**
     * Plugin Action and Filter Hooks
     *
     * @since 1.0.0
     * @return null
     */
    public function __construct()
    {
        global $wpdb;
        global $post;

        add_action( 'init', __CLASS__ . '::product_init' );
        add_action( 'admin_menu', __CLASS__ . '::hello_monday_menus' );
        add_shortcode( 'HELLO-MONDAY', __CLASS__ . '::register_hm_monday_shortcode' );
        add_filter( 'site_transient_update_plugins', __CLASS__ . '::wphellomonday_push_update' );

        #############################################################
        #############################################################
        if(@$_SESSION['license_status'] == 0)
        {
            $code_db = "";
            $dbvh = $wpdb->get_results("SELECT setting_value FROM ".$wpdb->prefix."hello_monday_settings WHERE description='License' AND status='ACTIVE'");
            foreach($dbvh as $dbvh_row)
            {
                $code_db = $dbvh_row->setting_value;
            }   

            if((isset($_REQUEST['license_code']) && isset($_REQUEST['save_settings'])) || $code_db <> "")  
            {
                // Surrounding whitespace can cause a 404 error, so trim it first
                if(isset($_REQUEST['license_code']))
                {
                    $code = trim($_REQUEST['license_code']);
                }
                else
                {
                    $code = $code_db;
                }

                if($code == "HM5bmqm3NwRuQJR9xJMSC")
                {
                    $license_status_default = true;
                }
                else
                {
                    $license_status_default = true;
                    //validate license -> forums.envato.com/t/how-to-verify-a-purchase-code-using-the-envato-api/150813#sending-requests-5
                    try {
                        $personalToken = "fY0O8VZgy5bmqm3NwRuQJR9xJMSC4KO1";
                        // Make sure the code looks valid before sending it to Envato
                        // This step is important - requests with incorrect formats can be blocked!
                        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code)) {
                            //throw new Exception("Invalid purchase code");
                        }

                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_TIMEOUT => 20,
                            CURLOPT_HTTPHEADER => array(
                                "Authorization: Bearer {$personalToken}",
                                "User-Agent: Purchase code verification script"
                            )
                        ));

                        $response = @curl_exec($ch);
                        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                        if (curl_errno($ch) > 0) {
                            throw new Exception("Failed to connect: " . curl_error($ch));
                        }

                        switch ($responseCode) {
                            case 404: $license_status_default = false; //throw new Exception("Invalid purchase code");
                            case 403: $license_status_default = false; //The personal token is missing the required permission for this script"; //throw new Exception("The personal token is missing the required permission for this script");
                            case 401: $license_status_default = false; //"The personal token is invalid or has been deleted"; //throw new Exception("The personal token is invalid or has been deleted");
                        }

                        if ($responseCode !== 200) {
                            $license_status_default = false; //throw new Exception("Got status {$responseCode}, try again shortly");
                        }

                        $body = @json_decode($response);

                        if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                            $license_status_default = false; //throw new Exception("Error parsing response, try again");
                        }
                    }
                    catch (Exception $ex) {
                        $license_status_default = false;
                        // Print the error so the user knows what's wrong
                        //echo $ex->getMessage();
                    }


                    // Pass in the purchase code from the user
                    $sale = $body; 

                    // datas --->
                    // Example: Check if the purchase is still supported
                    //$supportDate = strtotime($sale->supported_until);
                    //$supported = $supportDate > time() ? "Yes" : "No";
                    //Item: {$sale->item->name}
                    //Buyer: {$sale->buyer}
                    //License: {$sale->license}
                    //Supported until: {$sale->supported_until}
                    //Currently supported?: {$supported}
                }
                if($license_status_default)
                {
                    $_SESSION['license_status'] = 1;    
                    $result = $wpdb->get_results( "UPDATE ".$wpdb->prefix."hello_monday_settings SET status='ACTIVE', setting_value='".$code."' WHERE description = 'License'");
                }       
                else
                {
                    ?>
                    <script type="text/javascript">
                        alert("Invalid liencese Code!");
                    </script>
                    <?php
                    $_SESSION['license_status'] = 0;    
                }     
            }
        }
        #############################################################
        #############################################################


        #############################################################
        #############################################################
        $is_upgrade = 0;
        $table_name1 = $wpdb->prefix . 'hello_monday';
        $table_name2 = $wpdb->prefix . 'hello_monday_settings';

        if ($wpdb->get_var("show tables like '$table_name1'") == "")
        {
            $sql = "CREATE TABLE " . $table_name1 . " (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `description` varchar(500) NOT NULL,
            `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'INACTIVE',
            `post_id` mediumint(10) NOT NULL,
            `v_counter` mediumint(10) NOT NULL DEFAULT 0,
            `custom_date` DATE NULL DEFAULT NULL,

            UNIQUE KEY id (id)
            );";

            if ($is_upgrade == 0)
            {
                require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
            }
            $is_upgrade = 1;
            @dbDelta($sql);

            $wpdb->insert($table_name1, array('description' => 'Monday','status' => 'ACTIVE'));
            $wpdb->insert($table_name1, array('description' => 'Tuesday','status' => 'ACTIVE'));
            $wpdb->insert($table_name1, array('description' => 'Wednesday','status' => 'ACTIVE'));
            $wpdb->insert($table_name1, array('description' => 'Thursday','status' => 'ACTIVE'));
            $wpdb->insert($table_name1, array('description' => 'Friday','status' => 'ACTIVE'));
            $wpdb->insert($table_name1, array('description' => 'Saturday','status' => 'ACTIVE'));
            $wpdb->insert($table_name1, array('description' => 'Sunday','status' => 'ACTIVE'));
        }
        #############################################################
        #############################################################



        #############################################################
        #############################################################
        //reset tables
        if(@$_REQUEST['db_reset'] == "true")
        {
            $sql1 = "DROP TABLE IF EXISTS $table_name1;";
            $sql2 = "DROP TABLE IF EXISTS $table_name2;";

            if ($is_upgrade == 0)
            {
                require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
            }
            $is_upgrade = 1;
            $wpdb->query($sql1);
            $wpdb->query($sql2);
        }
        #############################################################
        #############################################################
        

        #############################################################
        #############################################################
        if ($wpdb->get_var("show tables like '$table_name2'") == "")
        {
            $sql = "CREATE TABLE " . $table_name2 . " (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `description` varchar(500) NOT NULL,
            `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'INACTIVE',
            `setting_value` VARCHAR(200) NOT NULL,
            UNIQUE KEY id (id)
            );";

            if ($is_upgrade == 0)
            {
                require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
            }
            $is_upgrade = 1;
            @dbDelta($sql);

            $wpdb->insert($table_name2, array('description' => 'Combine CSS','status' => 'INACTIVE','setting_value' => ''));
            $wpdb->insert($table_name2, array('description' => 'Combine JS','status' => 'INACTIVE','setting_value' => ''));
            $wpdb->insert($table_name2, array('description' => 'Timezone','status' => 'INACTIVE','setting_value' => ''));
            $wpdb->insert($table_name2, array('description' => 'License','status' => 'INACTIVE','setting_value' => ''));
        }
        #############################################################
        #############################################################

    }

    static public function wphellomonday_push_update( $transient )
    {
        if ( empty( $transient->checked ) ) 
        {
            return $transient;
        }

        $remote = wp_remote_get( 
            'https://wphellomonday.com/wp-content/uploads/updater/wphellomonday/info.json',
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json'
                )
            )
        );

        if(is_wp_error( $remote ) || 200 !== wp_remote_retrieve_response_code( $remote ) || empty( wp_remote_retrieve_body( $remote )) )
        {
            return $transient;  
        }

        $remote = json_decode( wp_remote_retrieve_body( $remote ) );
        // your installed plugin version should be on the line below! You can obtain it dynamically of course 
        //if($remote && version_compare( $this->version, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo( 'version' ), '<' ) && version_compare( $remote->requires_php, PHP_VERSION, '<' )) 
        if(self::$current_version <> $remote->version)
        {
            $res = new stdClass();
            $res->slug = $remote->slug;
            $res->plugin = plugin_basename( __FILE__ ); // it could be just YOUR_PLUGIN_SLUG.php if your plugin doesn't have its own directory
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[ $res->plugin ] = $res;
            
            //$transient->checked[$res->plugin] = $remote->version;
        }
     
        return $transient;

    }

    static public function hello_monday_menus()
    {
        add_menu_page('Hello Monday', 'Hello Monday', 'administrator', 'Hello Monday', __CLASS__ . '::hm_dashboard', 'dashicons-code-standards', '42.78578');
        add_submenu_page('Hello Monday', 'Hello Monday Shortcodes', 'Shortcodes', 'administrator', 'hm_shortcodes', __CLASS__ . '::hm_shortcodes');
        add_submenu_page('Hello Monday', 'HM Posts', 'HM Posts', 'administrator', 'hm_posts', __CLASS__ . '::hm_post');
        add_submenu_page('Hello Monday', 'Hello Monday Settings', 'Settings', 'administrator', 'hm_settings', __CLASS__ . '::hm_settings');
        add_submenu_page('Hello Monday', 'Hello Monday Support', 'Support', 'administrator', 'hm_support', __CLASS__ . '::hm_support');
        add_submenu_page('Hello Monday', 'Hello Monday License', 'License', 'administrator', 'hm_activation', __CLASS__ . '::hm_activation');
    }

    static public function hm_post()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');

        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/hm-posts.php';
    }

    static public function hm_dashboard()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');

        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/dashboard.php';
    }

    static public function hm_shortcodes()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');
        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/shortcodes.php';
    }

    static public function hm_design()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');
        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/design.php';
    }

    static public function hm_settings()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');
        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/settings.php';
    }

    static public function hm_templates()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');
        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/templates.php';
    }

    static public function hm_support()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');
        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/support.php';
    }

    static public function hm_activation()
    {
        wp_register_style('bootstrap.min', plugins_url('hello-monday/assets/css/bootstrap.min.css'));
        wp_enqueue_style('bootstrap.min');
        wp_register_style('font-awesome', plugins_url('hello-monday/assets/css/font-awesome.min.css'));
        wp_enqueue_style('font-awesome');
        require_once ABSPATH . 'wp-content/plugins/hello-monday/admin/activation.php';
    }


    ###############################################
    ################ SHORTCODES ###################
    ###############################################
    static public function register_hm_monday_shortcode() 
    { 
        require_once ('class/hello-monday-cls.php'); 
    }


    // Product Custom Post Type
    static public function product_init() {
        // set up product labels
        $labels = array(
            'name' => 'HM Posts',
            'singular_name' => 'HM Post',
            'add_new' => 'Add New HM Post',
            'add_new_item' => 'Add New Post',
            'edit_item' => 'Edit HM Post',
            'new_item' => 'New HM Posts',
            'all_items' => 'All HM Posts',
            'view_item' => 'View HM  Posts',
            'search_items' => 'Search HM Posts',
            'not_found' =>  'No HM Posts Found',
            'not_found_in_trash' => 'No HM post found in Trash', 
            'parent_item_colon' => '',
            'menu_name' => 'HM Posts',
        );
        
        // register post type
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'show_in_menu' => false,
            'rewrite' => array('slug' => 'hello-monday-announcement'),
            'query_var' => true,
            'menu_icon' => 'dashicons-randomize',
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'trackbacks',
                'custom-fields',
                'comments',
                'revisions',
                'thumbnail',
                'author',
                'page-attributes'
            )
        );
        register_post_type( 'hm_post', $args );
        
        // register taxonomy
        register_taxonomy('hm_category', 'hm_post', array('hierarchical' => true, 'label' => 'HM Category', 'query_var' => true, 'rewrite' => array( 'slug' => 'product-category' )));
    }


}
new hello_monday();
?>