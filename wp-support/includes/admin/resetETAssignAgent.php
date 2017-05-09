<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$customFields=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$wpsp_change_ticket_assign_agent=get_option('wpsp_et_change_ticket_assign_agent');
$notify_to=array(
        'customer'=>'1',
        'administrator'=>'1',
        'supervisor'=>'1',
        'assigned_agent'=>'1',
        'all_agents'=>'0'
);
$templates=array(
    'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
    'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_description' => __("Ticket Description", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_status' => __("Ticket Status", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_priority' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' ),
    'ticket_url'=>__("Ticket URL", 'wp-support-plus-responsive-ticket-system' ),
    'updated_by'=>__("User who assigned ticket", 'wp-support-plus-responsive-ticket-system' ),
    'old_assigned_to'=>__("User to whom assigned ticket before", 'wp-support-plus-responsive-ticket-system' ),
    'new_assigned_to'=>__("User to whom assigned ticket now", 'wp-support-plus-responsive-ticket-system' ),
    'time_created'=>__("Ticket Created", 'wp-support-plus-responsive-ticket-system' )
);
foreach($customFields as $field){
    $templates['cust'.$field->id]=$field->label;
}
if(isset($wpsp_change_ticket_assign_agent['mail_subject'])){
    $wpsp_change_ticket_assign_agent['mail_subject']='{updated_by} assigned ticket to {new_assigned_to}';
    update_option('wpsp_et_change_ticket_assign_agent',$wpsp_change_ticket_assign_agent);
}
if(isset($wpsp_change_ticket_assign_agent['mail_body'])){
    $wpsp_change_ticket_assign_agent['mail_body']='<strong>Below are details of ticket:</strong><br />
                    <br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Subject:</strong> {ticket_subject}<br />
                    <strong>Status:</strong> {ticket_status}<br />
                    <strong>Category:</strong> {ticket_category}<br />
                    <strong>Priority:</strong> {ticket_priority}<br />
                    <strong>Previously Assigned:</strong> {old_assigned_to}<br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Description:</strong><br />
                    {ticket_description}';
    update_option('wpsp_et_change_ticket_assign_agent',$wpsp_change_ticket_assign_agent);
}
if(isset($wpsp_change_ticket_assign_agent['notify_to'])){
    $wpsp_change_ticket_assign_agent['notify_to']=$notify_to;
    update_option('wpsp_et_change_ticket_assign_agent',$wpsp_change_ticket_assign_agent);
}
if(isset($wpsp_change_ticket_assign_agent['templates'])){
    $wpsp_change_ticket_assign_agent['templates']=$templates;
    update_option('wpsp_et_change_ticket_assign_agent',$wpsp_change_ticket_assign_agent);
}
?>