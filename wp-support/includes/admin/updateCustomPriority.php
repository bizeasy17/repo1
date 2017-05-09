<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

$priority_id=intval(sanitize_text_field($_POST['priority_id']));
if(!$priority_id) die();

$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array('name'=>sanitize_text_field($_POST['name']));
	if($_POST['is_default']){
		$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
		$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$priority_id." ";
		$priority_data=$wpdb->get_results($sql);
		foreach($priority_data as $priority)
		{
			foreach($default_status_priority['priority_names'] as $key=>$value)
			{
				if($value==$priority->name){
					$default_status_priority['priority_names'][$key]=sanitize_text_field($_POST['name']);
					$wpdb->update($wpdb->prefix.'wpsp_ticket',array('priority'=>sanitize_text_field($_POST['name'])),array('priority'=>$priority->name));
				}
			}
		}
		update_option('wpsp_default_status_priority_names',$default_status_priority);
		
	}
	$wpdb->update($wpdb->prefix.'wpsp_custom_priority',$values,array('id'=>$priority_id));
	
}
?>
