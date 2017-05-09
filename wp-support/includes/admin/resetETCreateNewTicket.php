<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$customFields=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$wpsp_create_new_ticket=get_option('wpsp_et_create_new_ticket');
$templates=array(
        'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ),
        'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_description' => __("Ticket Description", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_priority' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_url'=>__("Ticket URL", 'wp-support-plus-responsive-ticket-system' ),
        'time_created'=>__("Ticket Created", 'wp-support-plus-responsive-ticket-system' ),
        'agent_created'=>__("Agent Created", 'wp-support-plus-responsive-ticket-system' )
);
foreach($customFields as $field){
        $templates['cust'.$field->id]=$field->label;
}
$staff_to_notify=array(
        'administrator'=>'1',
        'supervisor'=>'1',
        'assigned_agent'=>'1',
        'all_agents'=>'0'
);
if(isset($wpsp_create_new_ticket['enable_success'])){
    $wpsp_create_new_ticket['enable_success']='1';
    update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
if(isset($wpsp_create_new_ticket['success_subject'])){
    $wpsp_create_new_ticket['success_subject']="Your Ticket has been created successfully";
    update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
if(isset($wpsp_create_new_ticket['success_body'])){
    $wpsp_create_new_ticket['success_body']='Dear {customer_name},<br />
								<br />
								Thank you for contacting Support. Your ticket has been created Successfully!<br />
								<br />
								Below are details of your ticket -<br />
								<br />
								<strong>Subject:</strong> {ticket_subject}<br />
								<strong>Description:</strong>
								<p>{ticket_description}</p>
								<br />
								<br />';
update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
if(isset($wpsp_create_new_ticket['staff_subject'])){
    $wpsp_create_new_ticket['staff_subject']='{ticket_subject}';
    update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
if(isset($wpsp_create_new_ticket['staff_body'])){
    $wpsp_create_new_ticket['staff_body']='<strong>{customer_name} ({customer_email})</strong> wrote:
								<p>{ticket_description}</p>
								<br />
								<br />';
    update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
if(isset($wpsp_create_new_ticket['templates'])){
    $wpsp_create_new_ticket['templates']=$templates;
    update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
if(isset($wpsp_create_new_ticket['staff_to_notify'])){
    $wpsp_create_new_ticket['staff_to_notify']=$staff_to_notify;
    update_option('wpsp_et_create_new_ticket',$wpsp_create_new_ticket);
}
?>