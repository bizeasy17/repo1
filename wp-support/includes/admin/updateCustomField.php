<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

$field_id=intval(sanitize_text_field($_POST['field_id']));
if(!$field_id) die();

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
        $field_options_array=apply_filters('wpsp_update_extra_field_options',$field_options_array);
        $field_categories=0;
        if($_POST['field_categories_update']){
            $field_categories=  sanitize_text_field(implode(',', $_POST['field_categories_update']));
        }
	$values=array(
            'label'             => sanitize_text_field($_POST['label']),
            'required'          => sanitize_text_field($_POST['required']),
            'field_type'        => sanitize_text_field($_POST['field_type']),
            'field_options'     => serialize($field_options_array),
            'field_categories'  => $field_categories,
            'isVarFeild'        => sanitize_text_field($_POST['isVarFeild'])
        );
	$wpdb->update($wpdb->prefix.'wpsp_custom_fields',$values,array('id'=>$field_id));
        $advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
        
        if(isset($advancedSettingsFieldOrder['fields_order']) && $advancedSettingsFieldOrder['fields_order']){
                if($_POST['isVarFeild']=='0'){
                    $advancedSettingsFieldOrder['fields_order']=array_merge($advancedSettingsFieldOrder['fields_order'],array($field_id));
                }
                else{
                    if(($key = array_search($field_id, $advancedSettingsFieldOrder['fields_order'])) !== false) {
                        unset($advancedSettingsFieldOrder['fields_order'][$key]);
                    }
                }
                if(isset($advancedSettingsFieldOrder['display_fields']) && array_search($field_id, $advancedSettingsFieldOrder['display_fields']) >-1){
                    unset($advancedSettingsFieldOrder['display_fields'][array_search($field_id, $advancedSettingsFieldOrder['display_fields'])]);
                    update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
                }
                update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
        }
        $wpsp_et_create_new_ticket=get_option('wpsp_et_create_new_ticket');
        $wpsp_et_create_new_ticket['templates']['cust'.$field_id]=sanitize_text_field($_POST['label']);
        update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
        
        $wpsp_et_reply_ticket=get_option( 'wpsp_et_reply_ticket' );
        $wpsp_et_reply_ticket['templates']['cust'.$field_id]=sanitize_text_field($_POST['label']);
        update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
        
        apply_filters('wpsp_in_updatecustomfield_filter',$field_id, sanitize_text_field($_POST['label']));
}
?>
