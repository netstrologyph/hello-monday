<?php
    /**
    * Shortcodes
    */

    if ( ! defined( 'ABSPATH' ) ) exit;
    
	global $wpdb;

	if(isset($_REQUEST["save_settings"]))
	{
		$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday"); 
		$update_set = "";
		foreach($dbvh as $dbvh_row)
		{
			if(isset($_REQUEST[$dbvh_row->id."_shortcode"])) $set_stat = "ACTIVE";
			else $set_stat = "INACTIVE";

			if(isset($_REQUEST[$dbvh_row->id."_post_id"])) $set_post_id = $_REQUEST[$dbvh_row->id."_post_id"];
			else $set_post_id = 0;

			$result = $wpdb->get_results( "UPDATE ".$wpdb->prefix."hello_monday SET status='".$set_stat."', post_id='".$set_post_id."', custom_date='".@$_REQUEST[$dbvh_row->id."_custom_date"]."' WHERE id = '".$dbvh_row->id."'");
		}
	}

	if(isset($_REQUEST["new_custom_shortcode_submit"]))
	{
		if($_REQUEST["new_custom_shortcode"] <> "Monday" && $_REQUEST["new_custom_shortcode"] <> "Tuesday" && $_REQUEST["new_custom_shortcode"] <> "Wednesday" && $_REQUEST["new_custom_shortcode"] <> "Wednesday" && $_REQUEST["new_custom_shortcode"] <> "Thursday" && $_REQUEST["new_custom_shortcode"] <> "Friday" && $_REQUEST["new_custom_shortcode"] <> "Saturday" && $_REQUEST["new_custom_shortcode"] <> "Sunday")
		{
			$result = $wpdb->get_results( "INSERT INTO ".$wpdb->prefix."hello_monday SET description='".$_REQUEST["new_custom_shortcode"]."', status='INACTIVE', post_id='".$_REQUEST["new_custom_post_id"]."', custom_date='".$_REQUEST["new_custom_date"]."'");	
		}
		
	}	

        // Get the posts
        $myposts = get_posts();

        // If there are posts
        if($myposts):
          // Loop the posts
            $i = 0;
          foreach ($myposts as $mypost):
                if($i == 0)
                {
                	$post_options = '';
                    $i++;
                }
                $title_temp = get_the_title($mypost->ID);
                $post_options .= '<option value=\''.$mypost->ID.'\'>'.$title_temp.'</option>';
                $cars[$i] = get_the_title($mypost->ID);
                $i++;
          endforeach; wp_reset_postdata(); 
         endif; 

    $current_day = date('l');
	$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday WHERE description='Monday' OR description='Tuesday' OR description='Wednesday' OR description='Thursday' OR description='Friday' OR description='Saturday' OR description='Sunday'");
	$counter = 0;
	$col1_data = "";
	$col2_data = "";
	foreach($dbvh as $dbvh_row)
	{
		if($dbvh_row->status == "ACTIVE") $btn_stat = " checked"; 
		else $btn_stat = ""; 
			
			$page_activity = "OFFLINE";
			if($dbvh_row->description==$current_day)
			{
				$page_activity = "<font color=green><strong>ONLINE</strong></font>";
			}

			$status = $dbvh_row->status;
			if($dbvh_row->status == "ACTIVE")
			{
				$status = "<font color=green><strong>ACTIVE</strong></font>";
			}

			$col1_data .= 
			'
					<tr>
					  <td>'.$dbvh_row->description.'</td>
					  <td>'.$dbvh_row->v_counter.'</td>
					  <td>'.$page_activity.'</td>
					  <td>'.$status.'</td>
					</tr>			
			';
		$counter++;
	}

	$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday WHERE description <> 'Monday' AND description <> 'Tuesday' AND description <> 'Wednesday' AND description <> 'Thursday' AND description <> 'Friday' AND description <> 'Saturday' AND description <> 'Sunday'");      
	$counter = 0;
	$col2_data = "";
	foreach($dbvh as $dbvh_row)
	{
		if($dbvh_row->status == "ACTIVE") $btn_stat = " checked"; 
		else $btn_stat = ""; 

			$page_activity = "OFFLINE";
			$current_day_new_format = date("Y-m-d");
			if($dbvh_row->custom_date==$current_day_new_format)
			{
				$page_activity = "<font color=green><strong>ONLINE</strong></font>";
			}

			$status = $dbvh_row->status;
			if($dbvh_row->status == "ACTIVE")
			{
				$status = "<font color=green><strong>ACTIVE</strong></font>";
			}

			$col2_data .= 
			'
					<tr>
					  <td>'.$dbvh_row->description.'</td>
					  <td>'.$dbvh_row->v_counter.'</td>
					  <td>'.$page_activity.'</td>
					  <td>'.$status.'</td>
					</tr>			
			';	

		$counter++;
	}	
 ?>
 <?php include 'header.php';?>  
 <div id="exTab2" class="container">
    <ul class="nav nav-tabs">
        <li><a  href="#1" data-toggle="tab" style="visibility: hidden"></a>
        </li>
        <li class="active">
            <a  href="<?php $url = admin_url(); ?>admin.php?page=Hello+Monday">Dashboard</a>
        </li>
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_shortcodes">Shortcodes</a>
        </li>
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_posts">HM Posts</a></li>        
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_settings">Settings</a>
        </li>
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_support">Support</a>
        </li>
    </ul>
    <div class="tab-content ">
    	<form action="" method="post">
        <div class="tab-pane active" id="1">


            <div class="row">
                <div class="col-md-12">
                	<h2>Monday ShortCodes</h2>
					<table class="table">
					<tr class="active">
					  <td>NAME</td>
					  <td>VIEWS</td>
					  <td>PAGE ACTIVITY</td>
					  <td>STATUS</td>
					</tr>
					<?php echo $col1_data; ?>
					</table>
                </div>
            </div>
		
            <div class="row">
                <div class="col-md-12">
                	<h2>Custom ShortCodes</h2>
					<table class="table">
					<tr class="active">
					  <td>NAME</td>
					  <td>VIEWS</td>
					  <td>PAGE ACTIVITY</td>
					  <td>STATUS</td>
					</tr>
					<?php echo $col2_data; ?>
					</table>
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