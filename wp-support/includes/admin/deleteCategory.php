<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	
        $values=array(
		'cat_id'=>1
	);
        
        $category_id= intval(sanitize_text_field($_POST['cat_id']));
        if($category_id){
            $wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('cat_id'=>$category_id));
            $wpdb->delete($wpdb->prefix.'wpsp_catagories',array('id'=>$category_id));
        }
        
}
?>