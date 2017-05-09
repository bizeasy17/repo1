<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$submit_ticket_id='';
$submit_emailAddress='';
$open_ticket_page=get_permalink(get_option( 'wpsp_ticket_open_page_shortcode' ));
if (isset($_POST['mode'])){
    
    if(!wp_verify_nonce(sanitize_text_field($_POST['nounce']))) die();
    
    $flag                   = true;
    $submit_ticket_id       = intval(sanitize_text_field($_POST['submit_ticket_id']));
    $submit_emailAddress    = sanitize_email($_POST['submit_ticket_email']);
    
    if(!$submit_ticket_id || !$submit_emailAddress){
        $flag = false;
        ?>
        <div class="wpsp_open_ticket_error">
            <?php _e('Incorrect Ticket ID or Email Address!','wp-support-plus-responsive-ticket-system');?>
        </div>
        <?php
    }
    
    global $wpdb;
    $sql="select created_by, guest_email, guest_name from ".$wpdb->prefix . 'wpsp_ticket'.' where id='.$submit_ticket_id;
    $ticket = array();
    $ticket_user_email='Not Set'; //can not keep empty string here
    $ticket_user_name='Not Set';
    
    if( $flag ){
        $ticket = $wpdb->get_row($sql);
        if($ticket->created_by){
            $user               = get_userdata( $ticket->created_by );
            $ticket_user_email  = $user->user_email;
            $ticket_user_name   = $user->display_name;
        } else {
            $ticket_user_email  = $ticket->guest_email;
            $ticket_user_name   = $ticket->guest_name;
        }
        $ticket_user_email = apply_filters('wpsp_open_ticket_match_user_email',$ticket_user_email,$submit_emailAddress,$ticket);
    }
    
    if( $flag && $ticket_user_email == $submit_emailAddress){
        
        $emailSettings  = get_option( 'wpsp_email_notification_settings' );
        
        $advancedSettings = get_option( 'wpsp_advanced_settings' );
        
        $ticket_url     = $open_ticket_page.'?ticket_id='.$submit_ticket_id.'&auth='.$wpsp_hash->getHash($submit_ticket_id);
        
        $subject        = '['.
                           __($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system').' '.$advancedSettings['wpsp_ticket_id_prefix'].$submit_ticket_id
                           .'] '
                           .__('Ticket Link','wp-support-plus-responsive-ticket-system');
        
        $body           =  __('Dear','wp-support-plus-responsive-ticket-system')
                           .' '.$ticket_user_name.', <br>'
                           .__('Please click on below link to open ticket:','wp-support-plus-responsive-ticket-system').'<br>'
                           .'<a href="'.$ticket_url.'">'.$ticket_url.'</a>';
        
        $headers        = array("Content-Type: text/html;charset=utf-8");
        
        $headers[]      = 'From: ' . $emailSettings['default_from_name'] . ' <' . $emailSettings['default_from_email'] . '>';
        
        if ( isset( $emailSettings['default_reply_to']) && $emailSettings['default_reply_to'] != '' ) {
                $headers[] = 'Reply-To: ' .  $emailSettings['default_reply_to'];
        }
        
        wp_mail($ticket_user_email,$subject,$body,$headers);
        
        ?>
        <div class="wpsp_open_ticket_success">
            <?php _e('Success! Ticket link now sent to your email address.','wp-support-plus-responsive-ticket-system');?>
        </div>
        <?php
        
        $submit_ticket_id='';
        $submit_emailAddress='';
        
    } else if($flag) {
        $flag = false;
        ?>
        <div class="wpsp_open_ticket_error">
            <?php _e('Ticket ID and Email Address does not match!','wp-support-plus-responsive-ticket-system');?>
        </div>
        <?php
    }
    
}
?>
<div style="margin-top: 10px;" id="wpsp_link_form">
    <form action="<?php echo $open_ticket_page;?>" method="POST">
        <?php _e('Ticket # (Number Only)','wp-support-plus-responsive-ticket-system');?>:<br>
        <input type="text" name="submit_ticket_id" value="<?php echo $submit_ticket_id;?>"><br><br>
        <?php _e('Email Address used for this ticket','wp-support-plus-responsive-ticket-system');?>:<br>
        <input type="text" name="submit_ticket_email" value="<?php echo $submit_emailAddress;?>"><br><br>
        <input type="hidden" name="mode" value="submit">
        <input type="hidden" name="nounce" value="<?php echo wp_create_nonce();?>">
        <button type="submit"><?php _e('Get Link','wp-support-plus-responsive-ticket-system');?></button>
    </form>
</div>

