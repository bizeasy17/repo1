<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$cu = wp_get_current_user();
if (!$cu->has_cap('manage_support_plus_ticket')) exit;
?>
<h3><?php _e('Edit Canned Reply','wp-support-plus-responsive-ticket-system');?>
    <a id="backtocannedlist" href="#" onclick="showCannedList();"> Back To List</a>
</h3><br>
<?php
global $wpdb;

$canned_id = sanitize_text_field($_POST['can_id']);

$can=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_canned_reply where id=".intval($canned_id));
?>

<form id="wpsp_add_canned" onsubmit="setEditCannedReply(this,event);" method="post"> 
    <?php do_action('wpsp_editcanned_action');?>
    <b><?php _e('Title','wp-support-plus-responsive-ticket-system');?></b><br>     
    <input type="text" id="wpsp_can_title" name="title" value="<?php echo stripcslashes($can->title);?>" style="width: 100%;"><br><br>     
    <?php do_action('wpsp_add_field_in_editcanned_form',$can);?>     
    <b><?php _e('Reply:','wp-support-plus-responsive');?></b><br>     
    <textarea id="canned_reply_edit_body" name="canned_reply_edit_body"><?php echo stripcslashes(htmlspecialchars_decode($can->reply))?></textarea><br><br>
    <b><?php _e('Visibility:','wp-support-plus-responsive-ticket-system');?></b><br>     
    <?php $visibility= array('private' => 'Private','public'=>'Public' ); ?>     
    <select name="visibility">         
        <?php foreach ($visibility as $key => $value) { ?>         
                <option value="<?php echo $key;?>" <?php echo ($key ==  $can->visibility) ? ' selected="selected"' : '';?>><?php echo $value;?></option>         
        <?php } ?>     
    </select><br><br>
    <input type="hidden" name="action" value="setEditCannedReply" />
    <input type="hidden" name="can_id" value="<?php echo $can->id;?>" />
    <button type="submit" class="btn btn-success"><?php _e('Submit','wp-support-plus-responsive-ticket-system');?></button> 
</form>
