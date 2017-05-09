<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $current_user;
$current_user=wp_get_current_user();

$generalSettings=get_option( 'wpsp_general_settings' );
if(($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==0 && !$current_user->has_cap('manage_support_plus_agent')) || (!$current_user->has_cap('manage_support_plus_ticket') && !$current_user->has_cap('manage_support_plus_agent'))){
	echo "Sorry You don't have permission to access this!!!";
	die();
}

$ticket_ids_temp    = explode(',', $_POST['ticket_ids']);
$ticket_ids         = array();
foreach ($ticket_ids_temp as $ticket_id){
    if(intval($ticket_id)){
        $ticket_ids[] = intval(sanitize_text_field($ticket_id));
    } else {
        die(); // die if ticket id not an integer
    }
}

$agent_ids=array();
if(isset($_POST['agent_ids'])){
    foreach ($_POST['agent_ids'] as $agent_id){
        if(intval($agent_id)){
            $agent_ids[] = intval(sanitize_text_field($agent_id));
        } else {
            die(); // die if ticket id not an integer
        }
    }
}

foreach ($ticket_ids as $ticket_id){
    include 'setTicketAssignment.php';
}
?>
