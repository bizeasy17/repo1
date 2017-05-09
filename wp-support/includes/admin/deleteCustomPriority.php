<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

global $wpdb;
$priority_id=intval(sanitize_text_field($_POST['id']));
$result=$wpdb->get_results("Select name from ".$wpdb->prefix.'wpsp_custom_priority'." WHERE id=".$priority_id);
if(isset($result[0]->name))
{
	$priority_name=$result[0]->name;
}
$wpdb->delete($wpdb->prefix.'wpsp_custom_priority',array('id'=>$priority_id));
$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
if(isset($default_status_priority['priority_names']['normal'])){
	$values=array('priority'=>$default_status_priority['priority_names']['normal']);
}
elseif(isset($default_status_priority['priority_names']['Normal'])){
	$values=array('priority'=>$default_status_priority['priority_names']['Normal']);
}
else
{
	$values=array('priority'=>'normal');
}
if(isset($priority_name))
{
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('priority'=>$priority_name));
}

$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
if(isset($advancedSettingsPriorityOrder['priority_order']) && $advancedSettingsFieldOrder['priority_order']){
	array_splice($advancedSettingsPriorityOrder['priority_order'], array_search($priority_id, $advancedSettingsPriorityOrder['priority_order']), 1);
	update_option('wpsp_advanced_settings_priority_order',$advancedSettingsPriorityOrder);
}
?>
