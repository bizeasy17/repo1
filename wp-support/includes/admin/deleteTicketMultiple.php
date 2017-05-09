<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );

if(($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets']==0 && !$current_user->has_cap('manage_support_plus_agent')) || (!$current_user->has_cap('manage_support_plus_ticket') && !$current_user->has_cap('manage_support_plus_agent'))){
    die();
}

$ticket_ids=array();
$ticket_ids_temp = explode(',', sanitize_text_field($_POST['ticket_ids']));
foreach ($ticket_ids_temp as $ticket_id){
    if(intval($ticket_id)){
        $ticket_ids[] = intval($ticket_id);
    } else {
        die();
    }
}

foreach ($ticket_ids as $ticket_id){
    include 'deleteTicket.php';
}
?>
