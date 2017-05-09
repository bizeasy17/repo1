<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_agent')) exit; // Exit if current user is not admin

global $wpdb;
$faq_id=  intval(sanitize_text_field($_REQUEST['id']));
if(!$faq_id) die();

$wpdb->delete( $wpdb->prefix.'wpsp_faq', array( 'id' => $faq_id ) );

wp_redirect(admin_url('admin.php?page=wp-support-plus-faq'));
?>
