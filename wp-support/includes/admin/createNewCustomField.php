<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$field_options=array();
        if($_POST['field_options']){
            $field_options_temp=explode("\n",  $_POST['field_options'] );
            foreach ($field_options_temp as $option){
                $field_options[]=sanitize_text_field($option);
            }
        }
	$field_options_array=array();
	if(count($field_options)>0)
	{
		foreach($field_options as $field_option)
		{
			$field_options_array=array_merge($field_options_array,array($field_option=>$field_option)); 
		}
	}
        
        $field_options_array=apply_filters('wpsp_insert_extra_field_options',$field_options_array);
        if(empty($_POST['field_categories'])){
            $field_categories='0';
        }
        else{
            $field_categories = sanitize_text_field(implode(',', $_POST['field_categories']));
        }
        
        $values=array(
            'label'            => sanitize_text_field($_POST['label']),
            'required'         => intval(sanitize_text_field($_POST['required'])),
            'field_type'       => sanitize_text_field($_POST['field_type']),
            'field_options'    => serialize($field_options_array),
            'field_categories' => $field_categories,
            'isVarFeild'       => intval(sanitize_text_field($_POST['isVariableFeild']))
        );
	$wpdb->insert($wpdb->prefix.'wpsp_custom_fields',$values);
	$last_id=$wpdb->insert_id;
	
	$sql = "alter table {$wpdb->prefix}wpsp_ticket ADD cust".$last_id." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
	$wpdb->query($sql);
	
        if($_POST['isVariableFeild']=='0'){
            $advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
            if(isset($advancedSettingsFieldOrder['fields_order']) && $advancedSettingsFieldOrder['fields_order']){
                    $advancedSettingsFieldOrder['fields_order']=array_merge($advancedSettingsFieldOrder['fields_order'],array($last_id));
                    update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
            }
        }
	$advancedSettingsTicketList=get_option( 'wpsp_advanced_settings_ticket_list_order' );
	if(isset($advancedSettingsTicketList['backend_ticket_list']) && $advancedSettingsTicketList['backend_ticket_list']){
		$advancedSettingsTicketList['backend_ticket_list']=$advancedSettingsTicketList['backend_ticket_list'] + array($last_id=>0);
		$advancedSettingsTicketList['frontend_ticket_list']=$advancedSettingsTicketList['frontend_ticket_list'] + array($last_id=>0);
		update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
	}
	
	$wpsp_et_create_new_ticket=get_option( 'wpsp_et_create_new_ticket' );
	$wpsp_et_create_new_ticket['templates']['cust'.$last_id]= sanitize_text_field($_POST['label']);
	update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
	
	$wpsp_et_reply_ticket=get_option( 'wpsp_et_reply_ticket' );
	$wpsp_et_reply_ticket['templates']['cust'.$last_id]= sanitize_text_field($_POST['label']);
	update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
        
        apply_filters('wpsp_in_createnewcustomfield_filter',$last_id, sanitize_text_field($_POST['label']));
}
?>
