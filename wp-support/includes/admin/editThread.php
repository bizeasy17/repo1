<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_agent')) exit; // Exit if current user is not admin
global $wpdb;
$thread_id = sanitize_text_field($_POST['thread_id']);
$thread=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_ticket_thread where id=".intval($thread_id)); ?> 

<h3><?php _e('Edit Thread','wp-support-plus-responsive-ticket-system');?></h3><br> 

<form method="post" name="wpsp_edit_thread">     
    <textarea id="edit_thread" name="edit_thread"><?php echo stripcslashes(htmlspecialchars_decode($thread->body))?></textarea><br>     
    <button type="submit" class="btn btn-success" onclick="setEditThread(<?php echo intval($thread_id) ?>,<?php echo $thread->ticket_id ?>)"><?php  _e('Submit','wp-support-plus-responsive-ticket-system')?></button> 
</form>
