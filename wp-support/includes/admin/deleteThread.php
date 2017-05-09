<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$cu = wp_get_current_user(); 
$thread_id=intval(sanitize_text_field($_POST['thread_id']));
if ($cu->has_cap('manage_options') && $thread_id) {     
    $wpdb->delete( $wpdb->prefix.'wpsp_ticket_thread', array( 'id' => $thread_id ) ); 
}
?>