<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu=wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_ticket')) exit;

global $wpdb;
global $current_user;
$current_user=wp_get_current_user();

$skype_id           = sanitize_text_field($_POST['skype_id']);
$chat_availability  = sanitize_text_field($_POST['chat_availability']);
$call_availability  = sanitize_text_field($_POST['call_availability']);
$record_id          = sanitize_text_field($_POST['id']);

$values=array(
    'signature'                 => htmlspecialchars($_POST['signature'],ENT_QUOTES),
    'skype_id'                  => $skype_id,
    'skype_chat_availability'   => $chat_availability,
    'skype_call_availability'   => $call_availability
);
$wpdb->update($wpdb->prefix.'wpsp_agent_settings',$values,array('id'=>$record_id));
?>