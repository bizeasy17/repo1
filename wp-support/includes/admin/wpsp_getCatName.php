<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
if($current_user->has_cap('manage_options') && intval($_POST['cat_id'])){
    $cat_id=intval(sanitize_text_field($_POST['cat_id']));
    $category = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wpsp_catagories where id=".$cat_id );
    echo stripcslashes($category->name);
}
?>