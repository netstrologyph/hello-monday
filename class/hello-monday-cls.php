<?php
/**
 * Monday
 * *
 * @category   Wordpress
 * @since      Class available since Release 1.0.0
 */

if (!defined('ABSPATH')) exit;

class hm_monday_shortcode
{

    public static $suffix = '';
    public static $url = '';
    public static $path = '';

    public function Tdheadersc()
    {

    }

    public function __construct()
    {
        /** Gets the plugin URL (with trailing slash). */
        self::$url = plugin_dir_url(__FILE__);

        /** Gets the plugin PATH. */
        self::$path = plugin_dir_path(__FILE__);

        add_action('wp_enqueue_scripts', array(
            $this,
            'Tdheadersc'
        ));

        $this->render_shortcode();

    }


    public function render_shortcode()
    {
        global $wpdb;
        global $post;


        $dbvh = $wpdb->get_results("SELECT setting_value FROM ".$wpdb->prefix."hello_monday_settings WHERE description='Timezone' AND status='ACTIVE' LIMIT 1");      
        $active_timezone = "";
        foreach($dbvh as $dbvh_row)
        {
            $active_timezone = $dbvh_row->setting_value; 
        }

        if($active_timezone <> "")
        {
            date_default_timezone_set($active_timezone);  
        }
        
        $current_day = date('l');
        $default_content = '
                <style>
                .fancy-banners-page {
                  position: relative;
                  height: 100vh;
                  width: 100%;

                }
                .fancy-title-banners {
                  margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                  color: white;
                  font-size: 8vh;
                   letter-spacing: 0.5vh;
                     font-family: Arial, Helvetica, sans-serif;
                } 
                .fancy-title-subtitle {
                  position: relative;
                  bottom: 50%;
                  width: 100%;
                   font-size: 3vh;
                     font-family: Arial, Helvetica, sans-serif;
                  font-weight:  300;
                      margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                  color: lightgrey;
                } 
                .fancy-title-date {
                  position: relative;
                  bottom: 40%;
                  width: 100%;
                   font-size: 1.5vh;
                     font-family: Arial, Helvetica, sans-serif;
                  font-weight:  bolder;
                      margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                  color: grey;
                } 
                .fancy-header-status-banner {
                  position: relative;
                  text-align: center;
                  color: white;
                  font-size: 10vh;
                   letter-spacing: 0.5vh;
                  bottom: -30%;
                  right: -3%;
                    font-family: Arial, Helvetica, sans-serif;
                     margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                  color: white;
                   letter-spacing: 0.5vh;
                }
                .fancy-header-text-banner {
                  position: relative;
                  bottom: -30%;
                  left: 15%;
                  color: white;
                  font-size: 2vh;
                   letter-spacing: 0.5vh;
                  font-family: Arial, Helvetica, sans-serif;
                   margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                  color: white;
                   letter-spacing: 0.5vh;
                }
                @media only screen and (max-width: 800px) {
                  .fancy-header-block-banners{
                display: none;
                }
                }
                @media only screen and (max-width: 800px) {
                .fancy-title-subtitle {
                  position: relative;
                  bottom: 50%;
                  width: 60%;
                   font-size: 3vh;
                     font-family: Arial, Helvetica, sans-serif;
                  font-weight:  300;
                      margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                  color: lightgrey;
                } 
                }
                .fancy-header-button-banner {
                  border: none;
                  color: black;
                  background-color: white;
                  border-radius: 1vh 1vh 1vh 1vh; 
                  margin-top:5%;
                  text-align: center;
                  text-decoration: none;
                  display: inline-block;
                  font-size : 2vh; 
                  padding:1.2%;
                  padding-left: 5%;
                  padding-right: 5%;
                  letter-spacing: 0.2vh;
                  font-weight: bol;
                  font-family: Arial, Helvetica, sans-serif;
                   margin-left: 20%;
                  position: relative;
                  bottom: 70%;
                } 
            </style>            
            <div class="test">
            <div class="fancy-banners-page">
              <img class="fancy-banners-page" src="'.plugins_url('../assets/img/fancy-monday-default-bg.jpg', __FILE__).'" >
               <div class="fancy-title-date">
                <div>'.strtoupper(date('l jS F Y')).' ('.date_default_timezone_get().')</div>
              </div>
              <div class="fancy-title-banners">
                <strong>HELLO IT\'S '.strtoupper($current_day).'</strong><br>
              </div>
                <div class="fancy-title-subtitle">
                    Im a shortcode that display\'s every day a custom fresh message,
                    even with a daily new background like an image or video. Or just a simple background color.
                </div>
                <a href="#" class="fancy-header-button-banner">SEE MORE</a>
             </div>
            </div>
            </div>';





            ########################################################################################
            ###################################### Output ##########################################
            $post_content = "";

            $html                  = '';
            $target                = '';
            $suffix                = '';
            $prefix                = '';
            $title_style           = '';
            $desc_style            = '';
            $inf_design_style      = '';
            $inf_design_style      = '';
            $inf_design_style      = '';
            $prefix                = '';
            $suffix                = '';




        $is_post_found = 0;   
        $post_id_keyword  = "";
        $current_day_new_format = date("Y-m-d");
        $dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday WHERE description<>'Monday' AND description<>'Tuesday' AND description<>'Wednesday' AND description<>'Thursday' AND description<>'Friday' AND description<>'Saturday' AND description<>'Sunday' AND DATE(custom_date)='".$current_day_new_format."' LIMIT 1");
        foreach($dbvh as $dbvh_row)
        {
            if($dbvh_row->status == "ACTIVE")
            {
                $is_post_found = 1;   
                $post_id_keyword = $dbvh_row->post_id;
                $update_temp = $wpdb->query("UPDATE ".$wpdb->prefix."hello_monday SET v_counter=v_counter+1 WHERE id='".$dbvh_row->id."'");
            }
        }

        if($post_id_keyword == "")
        {
            $dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday WHERE description='".$current_day."' LIMIT 1");
            foreach($dbvh as $dbvh_row)
            {
                if($dbvh_row->status == "ACTIVE")
                {
                    $is_post_found = 1;   
                    $post_id_keyword = $dbvh_row->post_id;
                    $update_temp = $wpdb->query("UPDATE ".$wpdb->prefix."hello_monday SET v_counter=v_counter+1 WHERE id='".$dbvh_row->id."'");
                }
            }
        }

        if($post_id_keyword <> "")
        {
            if($is_post_found)
            {
                            $temp = @get_post_field('post_content', $post_id_keyword); //$post->ID
                            if($temp <> "")
                            {
                                $post_content = $temp;
                                $html = $post_content;
                            }
                            else
                            {
                                $html = $default_content;   
                            }
            }
            else
            {
                 $html = $default_content;   
            }
            
        } 
        else
        {
            $html = $default_content;
        }




        $output = $prefix . $html . $suffix;
        echo $output;

        /*
            if (function_exists('do_shortcode')) {
                return do_shortcode($output);
                //return $output;;
            }
            else
            {
                return $output;
            }
        */

        ###################################### Output ##########################################
        ########################################################################################
    }

}

new hm_monday_shortcode();