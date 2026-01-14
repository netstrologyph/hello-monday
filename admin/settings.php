<?php
    /**
    * Settings
    */

    if ( ! defined( 'ABSPATH' ) ) exit;
    
	global $wpdb;

	if(isset($_REQUEST["save_settings"]))
	{
		$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday_settings"); 
		$update_set = "";
		foreach($dbvh as $dbvh_row)
		{
			if(isset($_REQUEST[$dbvh_row->id."_shortcode"])) $set_stat = "ACTIVE";
			else $set_stat = "INACTIVE";
			$result = $wpdb->get_results( "UPDATE ".$wpdb->prefix."hello_monday_settings SET status='".$set_stat."', setting_value='".$_REQUEST["timezone"]."' WHERE id = '".$dbvh_row->id."'");
		}
	}

	$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday_settings");      
	$counter_filter = count($dbvh) / 2;
	$counter = 0;
	$col1_data = "";
	$col2_data = "";
	foreach($dbvh as $dbvh_row)
	{
		if($dbvh_row->status == "ACTIVE") $btn_stat = " checked"; 
		else $btn_stat = ""; 

		$timezone_select_option = "";
		if($dbvh_row->description == "Timezone")
		{
			$timezone_select_option = '
			<select name="timezone" id="timezone">';

				if($dbvh_row->setting_value == "")
				{
					$timezone_select_option .= '<option value=\''.date_default_timezone_get().'\' selected>'.date_default_timezone_get().'</option>';
				}
				else
				{
					$timezone_select_option .= '<option value=\''.$dbvh_row->setting_value.'\' selected>'.$dbvh_row->setting_value.'</option>';
				}
			
                                $timezone_select_option .= "    
                                    <option value='Etc/GMT+12'>(GMT-12:00) International Date Line West</option>
                                    <option value='Pacific/Midway'>(GMT-11:00) Midway Island, Samoa</option>
                                    <option value='Pacific/Honolulu'>(GMT-10:00) Hawaii</option>
                                    <option value='US/Alaska'>(GMT-09:00) Alaska</option>
                                    <option value='America/Los_Angeles'>(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option value='America/Tijuana'>(GMT-08:00) Tijuana, Baja California</option>
                                    <option value='US/Arizona'>(GMT-07:00) Arizona</option>
                                    <option value='America/Chihuahua'>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value='US/Mountain'>(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option value='America/Managua'>(GMT-06:00) Central America</option>
                                    <option value='US/Central'>(GMT-06:00) Central Time (US & Canada)</option>
                                    <option value='America/Mexico_City'>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option value='Canada/Saskatchewan'>(GMT-06:00) Saskatchewan</option>
                                    <option value='America/Bogota'>(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option value='US/Eastern'>(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option value='US/East-Indiana'>(GMT-05:00) Indiana (East)</option>
                                    <option value='Canada/Atlantic'>(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option value='America/Caracas'>(GMT-04:00) Caracas, La Paz</option>
                                    <option value='America/Manaus'>(GMT-04:00) Manaus</option>
                                    <option value='America/Santiago'>(GMT-04:00) Santiago</option>
                                    <option value='Canada/Newfoundland'>(GMT-03:30) Newfoundland</option>
                                    <option value='America/Sao_Paulo'>(GMT-03:00) Brasilia</option>
                                    <option value='America/Argentina/Buenos_Aires'>(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option value='America/Godthab'>(GMT-03:00) Greenland</option>
                                    <option value='America/Montevideo'>(GMT-03:00) Montevideo</option>
                                    <option value='America/Noronha'>(GMT-02:00) Mid-Atlantic</option>
                                    <option value='Atlantic/Cape_Verde'>(GMT-01:00) Cape Verde Is.</option>
                                    <option value='Atlantic/Azores'>(GMT-01:00) Azores</option>
                                    <option value='Africa/Casablanca'>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option value='Etc/Greenwich'>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option value='Europe/Amsterdam'>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option value='Europe/Belgrade'>(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option value='Europe/Brussels'>(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value='Europe/Sarajevo'>(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option value='Africa/Lagos'>(GMT+01:00) West Central Africa</option>
                                    <option value='Asia/Amman'>(GMT+02:00) Amman</option>
                                    <option value='Europe/Athens'>(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option value='Asia/Beirut'>(GMT+02:00) Beirut</option>
                                    <option value='Africa/Cairo'>(GMT+02:00) Cairo</option>
                                    <option value='Africa/Harare'>(GMT+02:00) Harare, Pretoria</option>
                                    <option value='Europe/Helsinki'>(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option value='Asia/Jerusalem'>(GMT+02:00) Jerusalem</option>
                                    <option value='Europe/Minsk'>(GMT+02:00) Minsk</option>
                                    <option value='Africa/Windhoek'>(GMT+02:00) Windhoek</option>
                                    <option value='Asia/Kuwait'>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option value='Europe/Moscow'>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option value='Africa/Nairobi'>(GMT+03:00) Nairobi</option>
                                    <option value='Asia/Tbilisi'>(GMT+03:00) Tbilisi</option>
                                    <option value='Asia/Tehran'>(GMT+03:30) Tehran</option>
                                    <option value='Asia/Muscat'>(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option value='Asia/Baku'>(GMT+04:00) Baku</option>
                                    <option value='Asia/Yerevan'>(GMT+04:00) Yerevan</option>
                                    <option value='Asia/Kabul'>(GMT+04:30) Kabul</option>
                                    <option value='Asia/Yekaterinburg'>(GMT+05:00) Yekaterinburg</option>
                                    <option value='Asia/Karachi'>(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option value='Asia/Calcutta'>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option value='Asia/Calcutta'>(GMT+05:30) Sri Jayawardenapura</option>
                                    <option value='Asia/Katmandu'>(GMT+05:45) Kathmandu</option>
                                    <option value='Asia/Almaty'>(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option value='Asia/Dhaka'>(GMT+06:00) Astana, Dhaka</option>
                                    <option value='Asia/Rangoon'>(GMT+06:30) Yangon (Rangoon)</option>
                                    <option value='Asia/Bangkok'>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value='Asia/Krasnoyarsk'>(GMT+07:00) Krasnoyarsk</option>
                                    <option value='Asia/Hong_Kong'>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option value='Asia/Kuala_Lumpur'>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option value='Asia/Irkutsk'>(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option value='Australia/Perth'>(GMT+08:00) Perth</option>
                                    <option value='Asia/Taipei'>(GMT+08:00) Taipei</option>
                                    <option value='Asia/Tokyo'>(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option value='Asia/Seoul'>(GMT+09:00) Seoul</option>
                                    <option value='Asia/Yakutsk'>(GMT+09:00) Yakutsk</option>
                                    <option value='Australia/Adelaide'>(GMT+09:30) Adelaide</option>
                                    <option value='Australia/Darwin'>(GMT+09:30) Darwin</option>
                                    <option value='Australia/Brisbane'>(GMT+10:00) Brisbane</option>
                                    <option value='Australia/Canberra'>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option value='Australia/Hobart'>(GMT+10:00) Hobart</option>
                                    <option value='Pacific/Guam'>(GMT+10:00) Guam, Port Moresby</option>
                                    <option value='Asia/Vladivostok'>(GMT+10:00) Vladivostok</option>
                                    <option value='Asia/Magadan'>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option value='Pacific/Auckland'>(GMT+12:00) Auckland, Wellington</option>
                                    <option value='Pacific/Fiji'>(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option value='Pacific/Tongatapu'>(GMT+13:00) Nukualofa</option>";
			$timezone_select_option .= "</select>";
		}

		$col1_data .= 
		'
							<li class="list-group-item">
								'.$dbvh_row->description.'
								'.$timezone_select_option.'
								<label class="switch ">
								<input id="'.$dbvh_row->id.'_shortcode" name="'.$dbvh_row->id.'_shortcode" type="checkbox" class="success"'.$btn_stat.'>
								<span class="slider round"></span>
								</label>
							</li>
		';
		$counter++;
	}
 ?>
<?php include 'header.php';?>  


<div id="exTab2" class="container">
    <ul class="nav nav-tabs">
        <li><a  href="#1" data-toggle="tab" style="visibility: hidden"></a>
        </li>
        <li>
            <a  href="<?php $url = admin_url(); ?>admin.php?page=Hello+Monday">Dashboard</a>
        </li>
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_shortcodes">Shortcodes</a>
        </li>
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_posts">HM Posts</a></li>        
        <li class="active"><a href="<?php $url = admin_url(); ?>admin.php?page=hm_settings">Settings</a>
        </li>
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_support">Support</a>
        </li>
    </ul>
    <div class="tab-content ">
		
		<form action="" method="post">

        <div class="tab-pane active" id="1">

            <div class="row">
                <div class="col-md-12">
                    <h4>Shortcode</h4>
                    <p>[HELLO-MONDAY]</p>
                </div>
            </div>  

            <div class="row">
                <div class="col-md-12">
                    <h4>License</h4>
                    <input type="text" name="license_code" id="license_code" value="" placeholder="Enter License Key"> 
                    <?php if($_SESSION['license_status']) { ?>
                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/active.png'; ?>" style="width: 20px;"> <font color="green">Activated</font>
                    <?php } else { ?>   
                        <font color="red"><i class="fa fa-lock" aria-hidden="true"></i> License not activated!</font>
                    <?php } ?>
                </div>
            </div>  
          
            <div class="row">
                <div class="col-md-12">
					<h4>Timezone & Combine CSS/JS</h4>
                    <p>Here you can combine files we used for our shortcodes, great way improve loading and Google page speed reports.</p>
                </div>
			</div>
			<div class="row">
                <div class="col-md-12">
                    <div class="form-group select-all">
                        <div class="form-check">
							<input type="checkbox" class="form-check-input" name="select-all" id="select-all" />
                            <label class="form-check-label" for="gridCheck">
                            Select all/Disable
                            </label>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
						<?php echo $col1_data; ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" name="save_settings" class="btn btn-primary" style="float: right;" value="SAVE">SAVE</button>
                </div>
            </div>
        </div>
   		</form>
    </div>
</div>
<script>
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});	
</script>
 <?php include 'footer.php';?> 