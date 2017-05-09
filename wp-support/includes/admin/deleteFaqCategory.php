<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array(
		'category_id'=>1
	);
	
        $faq_cat_id = intval(sanitize_text_field($_POST['faq_cat_id']));
        if($faq_cat_id){
            $wpdb->update($wpdb->prefix.'wpsp_faq',$values,array('category_id'=>$faq_cat_id));
            $wpdb->delete($wpdb->prefix.'wpsp_faq_catagories',array('id'=>$faq_cat_id));
        }
}
?>
