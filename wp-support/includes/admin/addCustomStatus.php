<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

global $wpdb;

$custom_status_text=sanitize_text_field($_POST['custom_status_text']);
$custom_status_color=sanitize_text_field($_POST['custom_status_color']);

$values=array(
    'name'=>$custom_status_text,
    'color'=>$custom_status_color 
);

$wpdb->insert($wpdb->prefix.'wpsp_custom_status',$values);
$last_id=$wpdb->insert_id;
$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
if(isset($advancedSettingsStatusOrder['status_order']) && is_array($advancedSettingsStatusOrder['status_order'])){
	$advancedSettingsStatusOrder['status_order']=array_merge($advancedSettingsStatusOrder['status_order'],array($last_id));
	update_option('wpsp_advanced_settings_status_order',$advancedSettingsStatusOrder);
}
?>
