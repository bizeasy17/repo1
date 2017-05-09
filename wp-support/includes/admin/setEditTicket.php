<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_ticket')) exit; // Exit if current user is not admin

$ticket_id=intval(sanitize_text_field($_REQUEST['ticket_id']));
if(!$ticket_id || !wp_verify_nonce(sanitize_text_field($_POST['wpsp_nonce']),$ticket_id)) die('Not Authorized');

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
 	//code to insert into db
$values=array(
        'subject'=>htmlspecialchars($_POST['subject'],ENT_QUOTES)
);
foreach ($customFields as $field){
    $val='';
    if(!apply_filters('wpsp_extra_custom_fields_db_editticket',false,$field) && isset($_POST['cust'.$field->id]) && is_array($_POST['cust'.$field->id])){
        $val=sanitize_text_field(implode(",",$_POST['cust'.$field->id]));
    } else {
        $val=(isset($_POST['cust'.$field->id]))?htmlspecialchars($_POST['cust'.$field->id],ENT_QUOTES):'';
    }
    $values['cust'.$field->id]=$val;
}
$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>$ticket_id));