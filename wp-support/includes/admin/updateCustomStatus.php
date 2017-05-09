<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

$status_id=intval(sanitize_text_field($_POST['status_id']));
if(!$status_id) die();

$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array('name'=>sanitize_text_field($_POST['name']));
        $sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id." ";
        $status=$wpdb->get_row($sql);
	if($_POST['is_default']){
            $default_status_priority=get_option( 'wpsp_default_status_priority_names' );

            foreach($default_status_priority['status_names'] as $key=>$value)
            {
                    if($value==$status->name){
                            $default_status_priority['status_names'][$key]=sanitize_text_field($_POST['name']);
                    }
            }
		
            update_option('wpsp_default_status_priority_names',$default_status_priority);
		
	}
        
        $wpdb->update($wpdb->prefix.'wpsp_ticket',array('status'=>sanitize_text_field($_POST['name'])),array('status'=>$status->name));
        $wpdb->update($wpdb->prefix.'wpsp_custom_status',$values,array('id'=>$status_id));
	
}
?>
