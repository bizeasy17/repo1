<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$customFields=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$wpsp_reply_ticket=get_option('wpsp_et_reply_ticket');
$templates=array(
        'reply_by_name' => __("Reply By Name", 'wp-support-plus-responsive-ticket-system' ),
        'reply_by_email' => __("Reply By Email", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_status' => __("Ticket Status", 'wp-support-plus-responsive-ticket-system' ),
        'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
        'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
        'reply_description' => __("Reply Description", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_priority' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' ),
        'ticket_url'=>__("Ticket URL", 'wp-support-plus-responsive-ticket-system' ),
        'reply_description'=>__("Reply Description", 'wp-support-plus-responsive-ticket-system' ),
        'time_created'=>__("Ticket Created", 'wp-support-plus-responsive-ticket-system' )
);
foreach($customFields as $field){
    $templates['cust'.$field->id]=$field->label;
}
$notify_to=array(
        'customer'=>'1',
        'administrator'=>'1',
        'supervisor'=>'1',
        'assigned_agent'=>'1',
        'all_agents'=>'0'
);
if(isset($wpsp_reply_ticket['reply_subject'])){
    $wpsp_reply_ticket['reply_subject']='{ticket_subject}';
    update_option('wpsp_et_reply_ticket',$wpsp_reply_ticket);
}
if(isset($wpsp_reply_ticket['reply_body'])){
    $wpsp_reply_ticket['reply_body']='<strong>{reply_by_name} ({reply_by_email})</strong> wrote:
								<p>{reply_description}</p>
								<br />
								<br />';
    update_option('wpsp_et_reply_ticket',$wpsp_reply_ticket);
}
if(isset($wpsp_reply_ticket['templates'])){
    $wpsp_reply_ticket['templates']=$templates;
    update_option('wpsp_et_reply_ticket',$wpsp_reply_ticket);
}
if(isset($wpsp_reply_ticket['notify_to'])){
    $wpsp_reply_ticket['notify_to']=$notify_to;
    update_option('wpsp_et_reply_ticket',$wpsp_reply_ticket);
}
?>