<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $current_user;
$current_user=wp_get_current_user();
if (!$current_user->has_cap('manage_support_plus_ticket')) exit;

global $wpdb;
$ticket_ids_temp=explode(',', $_POST['ticket_ids']);
$ticket_ids=array();
foreach ($ticket_ids_temp as $ticket_id){
    if(intval($ticket_id)){
        $ticket_ids[] = intval(sanitize_text_field($ticket_id));
    } else {
        die(); //not process if ticket id is not an integer
    }
}

$mulStatus=sanitize_text_field($_POST['status']);
$mulCategory=sanitize_text_field($_POST['category']);
$mulPriority=sanitize_text_field($_POST['priority']);

foreach ($ticket_ids as $ticket_id){
    $sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
    $ticket = $wpdb->get_row( $sql );
    
    $category_id = $ticket->cat_id;
    $status      = $ticket->status;
    $priority    = $ticket->priority;
    $ticket_type = $ticket->ticket_type;
    
    if($mulStatus   != 'select') $status        = $mulStatus;
    if($mulCategory != 'select') $category_id   = $mulCategory;
    if($mulPriority != 'select') $priority      = $mulPriority;
    
    include( WCE_PLUGIN_DIR.'includes/admin/sendChangeTicketStatusMail.php' );
}
?>
