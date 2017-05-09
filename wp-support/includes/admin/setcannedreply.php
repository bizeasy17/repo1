<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_ticket')) exit;     
//code to insert into db 
global $wpdb;

$values=array(                 
    'title'=>sanitize_text_field($_POST['title']),                 
    'reply'=>htmlspecialchars($_POST['wpsp_canned_reply']),                 
    'uID'=> $cu->ID,                 
    'visibility'=>sanitize_text_field($_POST['visibility']) 
); 

$values=apply_filters('wpsp_insert_field_in_canned_reply_table',$values); 
$wpdb->insert($wpdb->prefix.'wpsp_canned_reply',$values); 
?>
