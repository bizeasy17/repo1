<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$ticket_id=intval(sanitize_text_field($_POST['ticket_id']));
if(!$ticket_id){ die(); }

if($current_user->has_cap('manage_options')){
    $wpdb->update($wpdb->prefix.'wpsp_ticket',array('active'=>1),array('id'=>$ticket_id));
}
?>