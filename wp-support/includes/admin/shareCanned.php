<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_ticket')) exit;

global $wpdb;
    $cid=sanitize_text_field($_POST['cid']);
    if($_POST['cuid']){
        $cuid=sanitize_text_field(implode(',', $_POST['cuid']));
    }
    else {
        $cuid='';
    }
    $wpdb->update( 
        $wpdb->prefix.'wpsp_canned_reply', 
        array(
            'sid' => $cuid
        ), 
        array( 'id' => $cid )
    );
?>
