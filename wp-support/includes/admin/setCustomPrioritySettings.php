<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$sql = "UPDATE " . $wpdb->prefix . "wpsp_custom_priority SET color='" . sanitize_text_field($_POST['custom_priority_color']) . "' WHERE id='" . intval(sanitize_text_field($_POST['custom_priority_id'])) . "'"; 
$wpdb->query( $sql );
?>
