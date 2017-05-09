<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_ticket')) exit;

global $wpdb;
$can_id=  intval(sanitize_text_field($_POST['can_id']));
if(!$can_id) die();

$wpdb->delete( $wpdb->prefix.'wpsp_canned_reply', array( 'id' => $can_id ) );

?>