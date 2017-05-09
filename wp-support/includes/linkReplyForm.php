<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$wpsp_open_ticket_page_url=get_permalink(get_option( 'wpsp_ticket_open_page_shortcode'));
$wpsp_open_ticket_page_url.='?ticket_id='.$ticket_id.'&auth='.$auth;
$wpsp_user_name='';
$wpsp_user_email='';
if(is_user_logged_in()){
    global $current_user;
    $current_user=wp_get_current_user();
    $wpsp_user_name=$current_user->display_name;
    $wpsp_user_email=$current_user->user_email;
}
?>
<?php 
 $sql="select status,active FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
 $ticket = $wpdb->get_row( $sql );

if($advancedSettings['hide_selected_status_ticket']!=$ticket->status && $ticket->active==1){?>
<br>
<div id="wpsp_link_form">
    <div id="wpsp_link_form_body">
        <h3><?php echo __('Reply to Ticket #','wp-support-plus-responsive-ticket-system').$ticket_id;?></h3>
        
        <form id="wpsp_frm_ticket_link" onsubmit="submitLinkForm(event,this);">
            <?php _e('Name','wp-support-plus-responsive-ticket-system');?>:<br>
            <input type="text" id="wpsp_link_form_name" name="guest_name" <?php echo ($wpsp_user_name)?'disabled="disabled"':'';?> value="<?php echo $wpsp_user_name;?>"><br>
            <?php _e('Email Address','wp-support-plus-responsive-ticket-system');?>:<br>
            <input type="text" id="wpsp_link_form_email" name="guest_email" <?php echo ($wpsp_user_email)?'disabled="disabled"':'';?> value="<?php echo $wpsp_user_email;?>"><br>
            <?php _e('Description','wp-support-plus-responsive-ticket-system');?>:<br>
            <textarea id="wpsp_link_form_desc" name="replyBody"></textarea><br>
            <?php include( WCE_PLUGIN_DIR.'includes/anti_spam/anti-spam-loader.php' );?>
            <button id="wpsp_link_form_submit_btn" type="submit"><?php _e('Submit Reply','wp-support-plus-responsive-ticket-system');?></button>
            <input type="hidden" name="action" value="wpspSubmitLinkForm">
            <input type="hidden" name="ticket_id" value="<?php echo $ticket_id;?>">
            <input type="hidden" name="wpsp_link_reply_nounce" value="<?php echo wp_create_nonce( 'wpsp-link-reply?ticket_id='.$ticket_id );?>">
            <input type="hidden" name="ckeditor_enabled" value="0">
            <input type="hidden" name="pipe" value="1">
        </form>        
    </div>
    <div id="wsp_wait">
	<img alt="<?php echo __('Please Wait...', 'wp-support-plus-responsive-ticket-system')?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>" />
    </div>
</div>
<?php }
else{
      echo stripslashes($advancedSettings['message_for_ticket_url']);
}?>
<script type="text/javascript">
    function submitLinkForm(e,obj){
        e.preventDefault();
        if(jQuery('#wpsp_link_form_name').val().trim()==''){
            alert('<?php _e('Please insert your name!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if(jQuery('#wpsp_link_form_email').val().trim()==''){
            alert('<?php _e('Please insert your email!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if(jQuery('#wpsp_link_form_desc').val().trim()==''){
            alert('<?php _e('Please insert description!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if(!wpsp_validateEmail(jQuery('#wpsp_link_form_email').val().trim())){
            alert('<?php _e('Please insert valid email!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if( jQuery('#wpsp_nocaptcha_token').val().trim() == '' ){
            alert('<?php _e('Please prove you are a human!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        
        jQuery('#wpsp_link_form_body').hide();
	jQuery('#wsp_wait').show();
        var dataform = new FormData( obj );
        dataform.append("guest_name",jQuery('#wpsp_link_form_name').val().trim());
        dataform.append("guest_email",jQuery('#wpsp_link_form_email').val().trim());
        
        jQuery.ajax( {
          url: '<?php echo admin_url( 'admin-ajax.php' );?>',
          type: 'POST',
          data: dataform,
          processData: false,
          contentType: false
        }) 
        .done(function( response ) {
            window.location.href="<?php echo $wpsp_open_ticket_page_url;?>";
        });
        
        e.preventDefault();
    }
    function wpsp_validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
</script>