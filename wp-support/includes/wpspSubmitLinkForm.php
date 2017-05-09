<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Check Nounce
 */
if( !wp_verify_nonce( sanitize_text_field($_POST['wpsp_link_reply_nounce']) , 'wpsp-link-reply?ticket_id='.sanitize_text_field($_POST['ticket_id']) ) ){
    die(__("No robots allowed!", 'wp-support-plus-responsive-ticket-system'));
}

/*
 * check for spam
 */
if (!$this->verifyCaptchaTokenForLinkReply()){
    die(__("No robots allowed!", 'wp-support-plus-responsive-ticket-system'));
}

//catch JS injection
if(stristr($_POST['replyBody'],"<script")){
    die(__("Javascript Injection Not Allowed!",'wp-support-plus-responsive-ticket-system'));
}

/*
 * Sanitize, validate and process reply
 */
include( WCE_PLUGIN_DIR.'includes/admin/validations/reply_ticket.php' );
$reply = new WPSPTicketReply();

do_action('wpsp_after_ticket_reply',$reply);

include( WCE_PLUGIN_DIR . 'includes/admin/sendTicketReplyMail.php' );