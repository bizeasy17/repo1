<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	
	$cat_assignee='0';
	if ($_POST['cat_assignee']!=NULL) {
		$cat_assignee=sanitize_text_field(implode(',', $_POST['cat_assignee']));
	}
	
        $values=array(
            'name'=>sanitize_text_field($_POST['cat_name']),
            'default_assignee'=>$cat_assignee
        );
         
        $values=apply_filters('wpsp_set_create_cat_value',$values);
        
	$wpdb->insert($wpdb->prefix.'wpsp_catagories',$values);
}
?>
