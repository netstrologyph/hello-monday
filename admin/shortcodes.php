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


	$post_options = "";
	$dbvh = $wpdb->get_results("SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE post_type='hm_post'");
	foreach($dbvh as $dbvh_row)
	{
		$post_options .= '<option value=\''.$dbvh_row->ID.'\'>'.$dbvh_row->post_title.'</option>';		
	}


	$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."hello_monday WHERE description='Monday' OR description='Tuesday' OR description='Wednesday' OR description='Thursday' OR description='Friday' OR description='Saturday' OR description='Sunday'");
	$counter = 0;
	$col1_data = "";
	$col2_data = "";
	foreach($dbvh as $dbvh_row)
	{
		if($dbvh_row->status == "ACTIVE") $btn_stat = " checked"; 
		else $btn_stat = ""; 
		
			$col1_data .= 
			'
							<li class="list-group-item">
								'.$dbvh_row->description.'
								<select id="'.$dbvh_row->id.'_post_id" name="'.$dbvh_row->id.'_post_id">
									<option value=\''.$dbvh_row->post_id.'\' selected>'.get_the_title($dbvh_row->post_id).'</option>
									'.$post_options.'
								</select>
								<label class="switch ">
								<input id="'.$dbvh_row->id.'_shortcode" name="'.$dbvh_row->id.'_shortcode" type="checkbox" class="success"'.$btn_stat.'>
								<span class="slider round"></span>
								</label>
							</li>
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

			$col2_data .= 
			'
							<li class="list-group-item">
								'.$dbvh_row->description.'
								<select id="'.$dbvh_row->id.'_post_id" name="'.$dbvh_row->id.'_post_id">
									<option value=\''.$dbvh_row->post_id.'\' selected>'.get_the_title($dbvh_row->post_id).'</option>
									'.$post_options.'
								</select>
								<input type="date" name="'.$dbvh_row->id.'_custom_date" id="'.$dbvh_row->id.'_custom_date" value="'.$dbvh_row->custom_date.'" placeholder="Custom Date"> 
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
<style> 
	.wp-core-ui select, input[type=color], input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea {
	border-radius:100px;	
	}
        select#\31 _post_id, select#\32 _post_id, select#\36 _post_id, select#\33 _post_id, select#\34 _post_id, select#\35 _post_id, select#\37 _post_id{
        border-radius: 100px;
	}
     	select#\33 _post_id {
	    left: 11.5rem;
		position: relative;
	}
	    select#\31 _post_id, select#\32 _post_id, select#\36 _post_id{
	    left: 12.8rem;
    position: relative;
}
        select#\34 _post_id {
	    left: 12.4rem;
		position: relative;
	}

	    select#\37 _post_id {
	    left: 13.4rem;
		position: relative;
	}
	    select#\35 _post_id {
	    left: 13.8rem;
		position: relative;

	}
	.button_shortcode_add {
    background-color: #8bc34a;
    border: none;
    color: white;
    padding: 1rem;
    width: 4rem;
    margin: 1vw;
	}
	
	.list-group-item {
	letter-spacing: 1px;
    font-weight: 700;
	}
	
	.btn-primary {
    border-radius: 10px;
    padding: 2rem;
    width: 7rem;
    height:3rem;
	}
</style>
 <div id="exTab2" class="container">
    <ul class="nav nav-tabs">
        <li><a  href="#1" data-toggle="tab" style="visibility: hidden"></a>
        </li>
        <li>
            <a  href="<?php $url = admin_url(); ?>admin.php?page=Hello+Monday">Dashboard</a>
        </li>
        <li class="active"><a href="<?php $url = admin_url(); ?>admin.php?page=hm_shortcodes">Shortcodes</a>
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
                	<br clear="all" /><br clear="all" />
                    <div class="form-group select-all">
                        <div class="form-check">
							<input type="checkbox" class="form-check-input" name="select-all" id="select-all" />
                            <!--<input class="form-check-input" type="checkbox" id="gridCheck">-->
                            <label class="form-check-label" for="gridCheck">
                            Select all/Disable
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                	<h2>Monday ShortCodes</h2>
                    <ul class="list-group list-group-flush">
						<?php echo $col1_data; ?>
                    </ul>
                </div>
            </div>
		
            <div class="row">
                <div class="col-md-12">
                	<h2>Custom ShortCodes</h2>
                	Add New Custom Shortcode: 
                	<input type="text" name="new_custom_shortcode" id="new_custom_shortcode" value="" placeholder="Enter Description"> 
					<select name="new_custom_post_id" id="new_custom_post_id">
						<option>Select Post</option>
						<?php echo $post_options; ?>
					</select>	
					<input type="date" name="new_custom_date" id="new_custom_date" value="" placeholder="Custom Date"> 
					
                    <?php if(!$_SESSION['license_status']) { ?>
                    	<button type="button" name="new_custom_shortcode_submit" value="new" class="button_shortcode_add" onclick="alert('License not activated!'); ">ADD</button>
                    <?php } else { ?>
                    	<button type="submit" name="new_custom_shortcode_submit" value="new" class="button_shortcode_add">ADD</button>
                	<?php } ?>
                	
                    <ul class="list-group list-group-flush">
						<?php echo $col2_data; ?>
                    </ul>
                    <?php if(!$_SESSION['license_status']) { ?>
                    	<button type="button" name="save_settings" class="btn btn-primary" style="float: right;" onclick="alert('License not activated!'); ">UPDATE</button>
                    <?php } else { ?>	
                    	<button type="submit" name="save_settings" class="btn btn-primary" style="float: right;">UPDATE</button>
                	<?php } ?>
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