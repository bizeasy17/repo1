<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

global $wpdb;

$custom_menu_text=sanitize_text_field($_POST['custom_menu_text']);
$custom_menu_url=sanitize_text_field($_POST['custom_menu_url']);
$custom_menu_icon=sanitize_text_field($_POST['custom_menu_icon']);

$values=array(
    'menu_text'=>$custom_menu_text,
    'redirect_url'=>$custom_menu_url,
    'menu_icon'=>$custom_menu_icon
);
$wpdb->insert($wpdb->prefix.'wpsp_panel_custom_menu',$values);
?>