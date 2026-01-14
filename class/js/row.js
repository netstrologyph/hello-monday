jQuery(document).ready(function($) {

	   $(".vc_shortcode-param").addClass("212111111111111111111");

 	if ( $('#vc_ui-panel-edit-element').attr('data-vc-shortcode') == 'base_td_header_basic' ) { 
	
	
     $(".pisot").addClass("Fancy-header-basic");
	 
} else if ( $('#vc_ui-panel-edit-element').attr('data-vc-shortcode') != 'base_td_header_basic' ) {
	
	 $(".pisot").removeClass("Fancy-header-basic");
	  $(".vc_active").removeClass("pisot");
}





 else {

 $(".pisot").removeClass("Fancy-header-basic");
}




});

