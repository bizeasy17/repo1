<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) exit; // Exit if current user is not admin

global $wpdb;
$sql = "UPDATE " . $wpdb->prefix . "wpsp_custom_priority SET color='" . sanitize_text_field($_POST['custom_priority_color']) . "' WHERE id='" . intval(sanitize_text_field($_POST['custom_priority_id'])) . "'"; 
$wpdb->query( $sql );
?>
