<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

global $wpdb;
$menu_id=intval(sanitize_text_field($_POST['menu_id']));
if(!$menu_id) exit;
$wpdb->delete($wpdb->prefix.'wpsp_panel_custom_menu',array('id'=>$menu_id));

?>