<?php
    /**
    * Dashboard
    */
    if ( ! defined( 'ABSPATH' ) )
    exit;
    
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
        <li><a href="<?php $url = admin_url(); ?>admin.php?page=hm_settings">Settings</a>
        </li>
        <li class="active"><a href="<?php $url = admin_url(); ?>admin.php?page=hm_support">Support</a>
        </li>
    </ul>
    <div class="tab-content ">
    
        <div class="tab-pane active" id="1">
            <div class="row">
            </div>
        </div>
   
    </div>
</div>

 <?php include 'footer.php';?> 