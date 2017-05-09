<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<span class="label label-info" style="font-size: 13px;"><?php _e('Are you human?','wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
<small><?php _e("Sorry to ask but we need to keep spam bots out. Please click below checkbox and wait until it is done.",'wp-support-plus-responsive-ticket-system');?></small><br>
<div id="wpsp_nocaptcha">
    <table>
        <tr>
            <td style="width:30px;padding:0px !important;">
                <img alt="wpspnocaptcha" id="wpsp_nocaptcha_checkbox" onclick="get_wpsp_nocaptcha_token();" src="<?php echo WCE_PLUGIN_URL.'asset/images/checkbox_unchecked.png';?>"/>
                <img alt="wpspwait" id="wpsp_spam_check_loading" style="display: none;" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <img alt="checked" id="wpsp_spam_check_done" style="display: none;" src="<?php echo WCE_PLUGIN_URL.'asset/images/checkmark.png';?>"/>
            </td>
            <td><?php _e("I'm not a robot",'wp-support-plus-responsive-ticket-system');?></td>
        </tr>
    </table>
</div>
<input type="hidden" id="wpsp_nocaptcha_token" name="wpsp_nocaptcha_token" value="">

<script type="text/javascript">
function get_wpsp_nocaptcha_token(){
    jQuery("#wpsp_nocaptcha img").hide();
    jQuery("#wpsp_spam_check_loading").show();
    
    var data = {
        'action':'wpsp_get_captcha_token'
    };

    jQuery.post('<?php echo admin_url( 'admin-ajax.php' );?>', data, function(response) {
        jQuery("#wpsp_nocaptcha_token").val(response.trim());
        jQuery("#wpsp_nocaptcha img").hide();
        jQuery("#wpsp_spam_check_done").show();
    });
}
</script>