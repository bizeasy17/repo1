<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$thread_id = sanitize_text_field($_POST['thread_id']);
//code to insert into db
$values=array(
        'body'=>htmlspecialchars($_POST['thread_body'],ENT_QUOTES)
);
$wpdb->update($wpdb->prefix.'wpsp_ticket_thread',$values,array('id'=>  intval($thread_id)));
?>