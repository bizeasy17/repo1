<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<h3><?php _e('Add New Canned Reply','wp-support-plus-responsive-ticket-system');?>
<a id="backtocannedlist" href="#" onclick="showCannedList();"> Back To List</a>
</h3><br> 

<form id="wpsp_add_canned" onsubmit="setCannedReply(this,event);" method="post"> 
    <?php do_action('wpsp_in_getcannedreply_before_title');?>
    <b><?php _e('Title:','wp-support-plus-responsive-ticket-system');?></b><br> 	
    <input type="text" id="wpsp_title" name="title" style="width: 100%;"><br><br>         
    <?php do_action('wpsp_add_field_for_canned_reply_after_title');?> 	
    <b><?php _e('Reply:','wp-support-plus-responsive-ticket-system');?></b><br>         
    <textarea id="canned_reply_body" name="canned_reply_body"></textarea><br><br>         
    <b><?php _e('Visibility:','wp-support-plus-responsive');?></b><br>         
    <select name="visibility">             
        <option value="private"><?php _e('Private','wp-support-plus-responsive-ticket-system');?></option>             
        <option value="public"><?php _e('Public','wp-support-plus-responsive-ticket-system');?></option>         
    </select>         
    <br><br>
    <input type="hidden" name="action" value="wpsp_setCannedReply"/>
    
    <button type="submit" class="btn btn-success"><?php _e('Submit','wp-support-plus-responsive-ticket-system');?></button> 
</form>