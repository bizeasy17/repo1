<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array('name' => sanitize_text_field($_POST['faq_cat_name']));
	$wpdb->insert($wpdb->prefix.'wpsp_faq_catagories',$values);
}
?>
