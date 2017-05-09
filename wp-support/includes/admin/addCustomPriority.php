<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

global $wpdb;

$custom_priority_text=sanitize_text_field($_POST['custom_priority_text']);
$custom_priority_color=sanitize_text_field($_POST['custom_priority_color']);
if(!$custom_priority_text || !$custom_priority_color) {die();}

$values=array(
    'name'=>$custom_priority_text,
    'color'=>$custom_priority_color
);

$wpdb->insert($wpdb->prefix.'wpsp_custom_priority',$values);
$last_id=$wpdb->insert_id;
$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
$priority_order=$advancedSettingsPriorityOrder['priority_order'];
if(isset($advancedSettingsPriorityOrder['priority_order']) && is_array($advancedSettingsPriorityOrder['priority_order'])){
	$advancedSettingsPriorityOrder['priority_order']=array_merge($advancedSettingsPriorityOrder['priority_order'],array($last_id));
	update_option('wpsp_advanced_settings_priority_order',$advancedSettingsPriorityOrder);
}
?>
