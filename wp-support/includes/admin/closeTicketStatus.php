<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb,$current_user;
$current_user=wp_get_current_user();
$ticket_id = intval(sanitize_text_field($_POST['ticket_id']));

if( !$ticket_id || !wp_verify_nonce(sanitize_text_field($_POST['wpsp_nonce']),$ticket_id)){
    die('Not Authorized!');
}

$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
$ticket = $wpdb->get_row( $sql );

$status      = sanitize_text_field($_POST['status']);
$category_id = $ticket->cat_id;
$priority    = $ticket->priority;
$ticket_type = $ticket->ticket_type;

include( WCE_PLUGIN_DIR.'includes/admin/sendChangeTicketStatusMail.php' );