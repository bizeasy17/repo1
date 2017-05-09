<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$generalSettings=get_option( 'wpsp_general_settings' );
$wpsp_et_change_ticket_assign_agent=get_option( 'wpsp_et_change_ticket_assign_agent' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );

$flag=false;
if (current_user_can('manage_options')) {
    $flag=true;
} else {
    foreach ($advancedSettings['modify_raised_by'] as $modifyRaisedBy) {
        if ((($modifyRaisedBy == 'wp_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket')) || (($modifyRaisedBy == 'wp_support_plus_supervisor') && $current_user->has_cap('manage_support_plus_agent'))) {
            $flag=true;
            break;
        }
    }
}

if(!$flag) die('not authorized'); //die if user is not authorized

/*****************************************************/
$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".intval(sanitize_text_field($_POST['ticket_id']));
$ticket = $wpdb->get_row( $sql );
/*****************************************************/
$ticket_created_by = sanitize_text_field($_POST['user_id']);
$wpspGuestName='';
$wpspGuestEmail='';
if($ticket_created_by==0){
	$ticket_type="guest";
        $wpspGuestName=sanitize_text_field($_POST['guest_name']);
        $wpspGuestEmail=sanitize_text_field($_POST['guest_email']);     
}
else{
	$ticket_type="user";
        $ticket_created_by=sanitize_text_field($_POST['reg_user_id']);
}
$values=array(
		'created_by'=>$ticket_created_by,
		'update_time'=>current_time('mysql', 1),
		'updated_by'=>$current_user->ID,
		'type'=>$ticket_type,
                'guest_name'=>$wpspGuestName,
                'guest_email'=>$wpspGuestEmail
);
$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>intval(sanitize_text_field($_POST['ticket_id']))));
/* Thread for change raised by*/ 
$thread_creater=$current_user->ID; if($ticket_created_by!=$ticket->created_by){     
    $threads=array(                     
        'ticket_id'=>intval(sanitize_text_field($_POST['ticket_id'])),
        'body'=>$ticket_created_by,                     
        'attachment_ids'=>'',                     
        'create_time'=>current_time('mysql', 1),                     
        'created_by'=>$thread_creater,                     
        'guest_name'=>$wpspGuestName,                     
        'guest_email'=>$wpspGuestEmail,                     
        'is_note'=>6     
    );     
    $wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$threads); 
}
die();
?>