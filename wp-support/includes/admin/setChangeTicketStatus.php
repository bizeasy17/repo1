<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb,$current_user;
$current_user=wp_get_current_user();

if(!wp_verify_nonce( sanitize_text_field($_POST['wpsp_nonce']), sanitize_text_field($_POST['ticket_id']) )) die();

$ticket_id   = intval(sanitize_text_field($_POST['ticket_id'])); if(!$ticket_id) die();
$category_id = intval(sanitize_text_field($_POST['category'])); if(!$category_id) die();
$status      = sanitize_text_field($_POST['status']);
$priority    = sanitize_text_field($_POST['priority']);
$ticket_type = sanitize_text_field($_POST['ticket_type']);

$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
$ticket = $wpdb->get_row( $sql );

include( WCE_PLUGIN_DIR.'includes/admin/sendChangeTicketStatusMail.php' );
?>
