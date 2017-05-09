<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb; 
global $current_user; 
$current_user=wp_get_current_user(); 
if (!$current_user->has_cap('manage_support_plus_ticket')) exit;

$canned_id = sanitize_text_field($_POST['can_id']);
	
$values=array( 			
    'title'=>sanitize_text_field($_POST['title']), 			
    'reply'=>htmlspecialchars($_POST['wpsp_canned_reply']),                         
    'visibility'=>sanitize_text_field($_POST['visibility']) 	
);        
$values=apply_filters('wpsp_update_field_value_for_canned_reply',$values); 	
$wpdb->update($wpdb->prefix.'wpsp_canned_reply',$values,array('id'=>intval($canned_id))); 
 
?>