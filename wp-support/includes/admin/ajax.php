<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

final class SupportPlusAjax {

    function createNewTicket() {
        
        //check nocaptcha token
        if(!$this->verifyCaptchaTokenForCreateTicket()){
            die(__("No robots allowed!", 'wp-support-plus-responsive-ticket-system'));
        }
        
        //catch JS injection
        if (stristr($_POST['create_ticket_body'], "<script")) {
            die(__("Javascript Injection Not Allowed!", 'wp-support-plus-responsive-ticket-system'));
        }
        
        /*
         * Sanitize, validate and process create ticket
         */
        include( WCE_PLUGIN_DIR.'includes/admin/validations/create_ticket.php' );
        $ticket = new WPSPTicketCreate();
        
        do_action('wpsp_after_ticket_create', $ticket);
        
        include( WCE_PLUGIN_DIR . 'includes/admin/sendTicketCreateMail.php' );
        
        if (!( $ticket->is_pipe || $ticket->extension_meta ) ) {
            echo "1";
            die();
        }
    }

    function replyTicket() {

        //catch JS injection
        if (stristr($_POST['replyBody'], "<script")) {
            die(__("Javascript Injection Not Allowed!", 'wp-support-plus-responsive-ticket-system'));
        }

        /*
         * Check Nounce
         */
        if (!isset($_POST['pipe']) || $_POST['pipe'] != 1) {
            
            $reply_nounce   = sanitize_text_field($_POST['wpsp_reply_nounce']);
            $user_id        = sanitize_text_field($_POST['user_id']);
            $ticket_id      = sanitize_text_field($_POST['ticket_id']);
            
            if (!wp_verify_nonce($reply_nounce, 'wpsp-reply?userid=' . $user_id . '&ticket_id=' . $ticket_id)) {
                die(__("Not Allowed!", 'wp-support-plus-responsive-ticket-system'));
            }
            
        }

        /*
         * Sanitize, validate and process reply
         */
        include( WCE_PLUGIN_DIR.'includes/admin/validations/reply_ticket.php' );
        $reply = new WPSPTicketReply();
        
        do_action('wpsp_after_ticket_reply',$reply);
        
        if( $reply->isNote == 0 ) {
            include( WCE_PLUGIN_DIR . 'includes/admin/sendTicketReplyMail.php' );
        }

        if ( $reply->is_pipe == 0 ) {
            die();
        }
    }

    function ticketFromThread() {
        global $wpdb;
        global $current_user;
        $current_user=wp_get_current_user();
        if (!$current_user->has_cap('manage_support_plus_ticket')) exit;
        $thread_id = intval(sanitize_text_field($_POST['thread_id']));
        if (!$thread_id) {
            die();
        }
        $now = time();
        $generalSettings = get_option('wpsp_general_settings');

        // get ticket id
        $sql = "SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE id='" . $thread_id . "'";
        $result = $wpdb->get_row($sql);
        $ticket_id = $result->ticket_id;

        // get existing ticket and place into temporary table
        $sql = "CREATE TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table AS SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id='" . $ticket_id . "'";
        $wpdb->query($sql);

        // set default values
        // id, subject, updated_by, status, cat_id, create_time, update_time, priority, ticket_type
        $sql = "UPDATE 
                            {$wpdb->prefix}wpsp_temp_table 
                    SET 
                            id='0',
                            subject='New ticket from Ticket #" . $ticket_id . " (" . $thread_id . ")',
                            updated_by='0',
                            status='open',
                            cat_id='" . $generalSettings['default_ticket_category'] . "',
                            create_time='" . gmdate('Y-m-d H:i:s', $now) . "',
                            update_time='" . gmdate('Y-m-d H:i:s', $now) . "',
                            priority='normal',
                            ticket_type='" . $generalSettings['default_ticket_type'] . "'
                    WHERE 
                            id='" . $ticket_id . "'";
        $wpdb->query($sql);

        // add updated entry into tickets table from temp table
        $sql = "INSERT INTO {$wpdb->prefix}wpsp_ticket SELECT * FROM {$wpdb->prefix}wpsp_temp_table";
        $wpdb->query($sql);

        // get new ticket id
        $new_ticket = $wpdb->insert_id;

        // drop temp table
        $sql = "DROP TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table";
        $wpdb->query($sql);

        // get ticket owner information
        $sql = "SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id='" . $new_ticket . "'";
        $result = $wpdb->get_row($sql);
        $created_by = $result->created_by;
        $guest_name = $result->guest_name;
        $guest_email = $result->guest_email;

        // get existing thread and place into temporary table
        $sql = "CREATE TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table AS SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE id='" . $thread_id . "'";
        $wpdb->query($sql);

        // set default values
        // id, ticket_id, create_time, created_by, guest_name, guest_email
        $sql = "UPDATE 
                            {$wpdb->prefix}wpsp_temp_table 
                    SET 
                            id='0',
                            ticket_id='" . $new_ticket . "', 
                            create_time='" . gmdate('Y-m-d H:i:s', $now) . "',
                            created_by='" . $created_by . "',
                            guest_name='" . $guest_name . "',
                            guest_email='" . $guest_email . "' 
                    WHERE id='" . $thread_id . "'";
        $wpdb->query($sql);

        // add updated entry into thread table from temp table
        $sql = "INSERT INTO {$wpdb->prefix}wpsp_ticket_thread SELECT * FROM {$wpdb->prefix}wpsp_temp_table";
        $wpdb->query($sql);

        // drop temp table
        $sql = "DROP TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table";
        $wpdb->query($sql);
        echo $new_ticket;
    }
    
    function verifyCaptchaTokenForCreateTicket(){
        
        if (isset($_POST['pipe']) && $_POST['pipe'] == 1){
            return TRUE;
        }
        if( !isset($_POST['wpsp_nocaptcha_token']) ){
            return FALSE;
        }
        if( !wp_verify_nonce(sanitize_text_field($_POST['wpsp_nocaptcha_token']),'wpsp_nocaptcha_token') ){
            return FALSE;
        }
        return TRUE;
        
    }
    
    function verifyCaptchaTokenForLinkReply(){
        
        if( !isset($_POST['wpsp_nocaptcha_token']) ){
            return FALSE;
        }
        if( !wp_verify_nonce(sanitize_text_field($_POST['wpsp_nocaptcha_token']),'wpsp_nocaptcha_token') ){
            return FALSE;
        }
        return TRUE;
        
    }

    function getTickets() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getTicketsByFilter.php' );
        die();
    }

    function getFrontEndTickets() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getFrontEndTicket.php' );
        die();
    }

    function openTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getIndivisualTicket.php' );
        die();
    }

    function openTicketFront() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getIndivisualTicketFront.php' );
        die();
    }

    function getAgentSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getAgentSettings.php' );
        die();
    }

    function setAgentSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setAgentSettings.php' );
        die();
    }

    function getGeneralSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getGeneralSettings.php' );
        die();
    }

    function setGeneralSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setGeneralSettings.php' );
        die();
    }

    function getCategories() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCategories.php' );
        die();
    }

    function createNewCategory() {
        include( WCE_PLUGIN_DIR . 'includes/admin/createNewCategory.php' );
        die();
    }

    function updateCategory() {
        include( WCE_PLUGIN_DIR . 'includes/admin/updateCategory.php' );
        die();
    }

    function deleteCategory() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteCategory.php' );
        die();
    }

    function getEmailNotificationSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getEmailNotificationSettings.php' );
        die();
    }

    function setEmailSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setEmailSettings.php' );
        die();
    }

    //version 2.0
    function getTicketAssignment() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getTicketAssignment.php' );
        die();
    }

    //version 2.0
    function setTicketAssignment() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setTicketAssignment.php' );
        die();
    }

    //Version 3.0
    function deleteTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteTicket.php' );
        die();
    }

    function cloneTicket() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/cloneTicket.php' );
        die();
    }

    //Version 3.0
    function getChangeTicketStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getChangeTicketStatus.php' );
        die();
    }

    //Version 3.0
    function setChangeTicketStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setChangeTicketStatus.php' );
        die();
    }

    //Version 3.2
    function getChatOnlineAgents() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getChatOnlineAgents.php' );
        die();
    }

    //Version 3.2
    function getCallOnlineAgents() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCallOnlineAgents.php' );
        die();
    }

    //version 3.9
    function getCreateTicketForm() {
        include( WCE_PLUGIN_DIR . 'includes/admin/create_new_ticket.php' );
        die();
    }

    //version 3.9
    function getCustomSliderMenus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCustomSliderMenus.php' );
        die();
    }

    //version 3.9
    function addCustomSliderMenu() {
        include( WCE_PLUGIN_DIR . 'includes/admin/addCustomSliderMenu.php' );
        die();
    }

    //version 3.9
    function deleteCustomSliderMenu() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteCustomSliderMenu.php' );
        die();
    }

    //version 4.0
    function searchRegisteredUsaers() {
        include( WCE_PLUGIN_DIR . 'includes/admin/searchRegisteredUsaers.php' );
        die();
    }

    //version 4.3
    function getRollManagementSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getRollManagementSettings.php' );
        die();
    }

    function setRoleManagement() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setRoleManagement.php' );
        die();
    }

    //version 4.4
    function getCustomFields() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCustomFields.php' );
        die();
    }

    function createNewCustomField() {
        include( WCE_PLUGIN_DIR . 'includes/admin/createNewCustomField.php' );
        die();
    }

    function updateCustomField() {
        include( WCE_PLUGIN_DIR . 'includes/admin/updateCustomField.php' );
        die();
    }

    function deleteCustomField() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteCustomField.php' );
        die();
    }

    function getFrontEndFAQ() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getFrontEndFAQ.php' );
        die();
    }

    function openFrontEndFAQ() {
        include( WCE_PLUGIN_DIR . 'includes/admin/openFrontEndFAQ.php' );
        die();
    }

    function getFaqCategories() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getFaqCategories.php' );
        die();
    }

    function createNewFaqCategory() {
        include( WCE_PLUGIN_DIR . 'includes/admin/createNewFaqCategory.php' );
        die();
    }

    function updateFaqCategory() {
        include( WCE_PLUGIN_DIR . 'includes/admin/updateFaqCategory.php' );
        die();
    }

    function deleteFaqCategory() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteFaqCategory.php' );
        die();
    }

    function getCustomCSSSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCustomCSSSettings.php' );
        die();
    }

    function setCustomCSSSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomCSSSettings.php' );
        die();
    }

    function getAdvancedSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getAdvancedSettings.php' );
        die();
    }

    function setAdvancedSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setAdvancedSettings.php' );
        die();
    }

    function getCustomStatusSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCustomStatusSettings.php' );
        die();
    }

    function deleteCustomStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteCustomStatus.php' );
        die();
    }

    function addCustomStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/addCustomStatus.php' );
        die();
    }

    function setChangeTicketStatusMultiple() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setChangeTicketStatusMultiple.php' );
        die();
    }

    function setAssignAgentMultiple() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setAssignAgentMultiple.php' );
        die();
    }

    function deleteTicketMultiple() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteTicketMultiple.php' );
        die();
    }

    function wpspCheckLogin() {
        include( WCE_PLUGIN_DIR . 'includes/admin/wpspCheckLogin.php' );
        die();
    }

    /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
     * Update 1 - Change Custom Status Color
     * Include file required to process database change for existing custom status color change
     */

    function setCustomStatusColor() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomStatusColor.php' );
        die();
    }

    /* END CLOUGH I.T. SOLUTIONS MODIFICATION
     */

    function getFieldsReorderSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getFieldsReorderSettings.php' );
        die();
    }

    function setFieldsReorderSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setFieldsReorderSettings.php' );
        die();
    }

    function getTicketListFieldSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getTicketListFieldSettings.php' );
        die();
    }

    function setTicketListFieldSettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setTicketListFieldSettings.php' );
        die();
    }

    function getCustomFilterFrontEnd() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCustomFilterFrontEnd.php' );
        die();
    }

    function setCustomFilterFrontEnd() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomFilterFrontEnd.php' );
        die();
    }

    function getCustomPrioritySettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getCustomPrioritySettings.php' );
        die();
    }

    function setCustomPrioritySettings() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomPrioritySettings.php' );
        die();
    }

    function addCustomPriority() {
        include( WCE_PLUGIN_DIR . 'includes/admin/addCustomPriority.php' );
        die();
    }

    function setCustomPriorityColor() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomPriorityColor.php' );
        die();
    }

    function deleteCustomPriority() {
        include( WCE_PLUGIN_DIR . 'includes/admin/deleteCustomPriority.php' );
        die();
    }

    function setSubCharLength() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setSubCharLength.php' );
        die();
    }

    function getETCreateNewTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getETCreateNewTicket.php' );
        die();
    }

    function setEtCreateNewTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setEtCreateNewTicket.php' );
        die();
    }

    function getETReplayTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getETReplayTicket.php' );
        die();
    }

    function setEtReplyTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setEtReplyTicket.php' );
        die();
    }

    function getETChangeTicketStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getETChangeTicketStatus.php' );
        die();
    }

    function setEtChangeTicketStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setEtChangeTicketStatus.php' );
        die();
    }

    function getETAssignAgent() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getETAssignAgent.php' );
        die();
    }

    function setETAssignAgent() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setETAssignAgent.php' );
        die();
    }

    function getETDeleteTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getETDeleteTicket.php' );
        die();
    }

    function setETDeleteTicket() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setETDeleteTicket.php' );
        die();
    }

    function setCustomStatusOrder() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomStatusOrder.php' );
        die();
    }

    function setCustomPriorityOrder() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setCustomPriorityOrder.php' );
        die();
    }

    function setDateFormat() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setDateFormat.php' );
        die();
    }

    function updateCustomStatus() {
        include( WCE_PLUGIN_DIR . 'includes/admin/updateCustomStatus.php' );
        die();
    }

    function updateCustomPriority() {
        include( WCE_PLUGIN_DIR . 'includes/admin/updateCustomPriority.php' );
        die();
    }

    function getTicketRaisedByUser() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getTicketRaisedByUser.php' );
        die();
    }

    function setTicketRaisedByUser() {
        include( WCE_PLUGIN_DIR . 'includes/admin/setTicketRaisedByUser.php' );
        die();
    }

    function showcanned() {
        include( WCE_PLUGIN_DIR . 'includes/admin/showcanned.php' );
        die();
    }

    function shareCanned() {
        include( WCE_PLUGIN_DIR . 'includes/admin/shareCanned.php' );
        die();
    }

    function getCKEditorSettings() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/getCKEditorSettings.php' );
        die();
    }

    function setCKEditorSettings() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/setCKEditorSettings.php' );
        die();
    }

    function wpspSubmitLinkForm() {
        include_once( WCE_PLUGIN_DIR . 'includes/wpspSubmitLinkForm.php' );
        die();
    }

    function getSupportButton() {
        include( WCE_PLUGIN_DIR . 'includes/admin/getSupportButton.php' );
        die();
    }

    function image_upload() {
        include( WCE_PLUGIN_DIR . 'includes/admin/imageUpload.php' );
        die();
    }

    function nl2br_save_html($string) {
        $string = str_replace(array("\r\n", "\r", "\n"), "\n", $string);
        $lines = explode("\n", $string);
        $output = '';
        foreach ($lines as $line) {
            $line .= '<br />';
            $output .= $line;
        }
        return $output;
    }

    function closeTicketStatus() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/closeTicketStatus.php' );
        die();
    }

    function wpsp_getCatName() {
        include( WCE_PLUGIN_DIR . 'includes/admin/wpsp_getCatName.php' );
        die();
    }

    function get_cat_custom_field() {
        include( WCE_PLUGIN_DIR . 'includes/admin/cat_get_custom_field.php' );
        die();
    }

    function getAddOnLicenses() {
        include( WCE_PLUGIN_DIR . 'includes/licenses/getAddOnLicenses.php' );
        die();
    }

    function wpsp_act_license() {
        include( WCE_PLUGIN_DIR . 'includes/licenses/wpsp_act_license.php' );
        die();
    }

    function wpsp_dact_license() {
        include( WCE_PLUGIN_DIR . 'includes/licenses/wpsp_dact_license.php' );
        die();
    }

    function wpsp_check_license() {
        include( WCE_PLUGIN_DIR . 'includes/licenses/wpsp_check_license.php' );
        die();
    }

    function getFrontEndDisplay() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/getFrontEndDisplay.php' );
        die();
    }

    function setFrontEndDisplay() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/setFrontEndDisplay.php' );
        die();
    }

    function getEditCustomField() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/editTicket.php' );
        die();
    }

    function setEditCustomField() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/setEditTicket.php' );
        die();
    }

    function check_email_in_ignore_list($flag, $ignore_emails, $user_email) {
        $emailSettings = get_option('wpsp_email_notification_settings');
        $emailcount = strlen($emailSettings['ignore_emails']) ? count(explode(',', $emailSettings['ignore_emails'])) : 0;
        if ($emailcount) {
            if (array_search($user_email, $ignore_emails) > -1) {
                $flag = false;
            } else {
                foreach ($ignore_emails as $ignore_email) {
                    if ($ignore_email != '' && $ignore_email[0] == '*') {

                        $checkStr = substr($ignore_email, 1);
                        if (strpos($user_email, $checkStr) > -1) {
                            $flag = false;
                            break;
                        }
                    } else if ($ignore_email != '' && $ignore_email[strlen($ignore_email) - 1] == '*') {

                        $checkStr = substr($ignore_email, 0, -1);
                        if (strpos($user_email, $checkStr) > -1) {
                            $flag = false;
                            break;
                        }
                    }
                }
            }
        } else {
            $flag = true;
        }

        return $flag;
    }

    function wpsp_upload_attachment() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/attachment/uploadAttachment.php' );
        die();
    }

    function deleteThread() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/deleteThread.php' );
        die();
    }

    function wpsp_submit_reply_confirm_box() {
        include_once( WCE_PLUGIN_DIR . 'includes/admin/submit_reply_confirm_box.php' );
    }
    
    function wpsp_get_captcha_token(){
        include_once( WCE_PLUGIN_DIR . 'includes/anti_spam/get-token.php' );
        die();
    }
    
    function wpsp_restore_ticket(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/wpsp_restore_ticket.php' );            
        die();
    }
    
    function wpsp_indivisualticket_backend_button_confirm_messages($ticket){
        include_once( WCE_PLUGIN_DIR . 'includes/admin/indi_ticket_backend_del_confirm.php' );
    }
    
    function wpsp_add_canned_reply(){             
            include_once( WCE_PLUGIN_DIR.'includes/admin/getcannedreply.php' );                        
            die();         
    }
    
    function setCannedReply(){             
        include_once( WCE_PLUGIN_DIR.'includes/admin/setcannedreply.php' );                        
        die();         
    }
    
    function wpsp_edit_canned_reply(){            
        include_once( WCE_PLUGIN_DIR.'includes/admin/editcanned.php' );                        
        die();        
    }
    
    function setEditCannedReply(){             
        include_once( WCE_PLUGIN_DIR.'includes/admin/set_edit_cannedreply.php' );                        
        die();         
    } 
    
    function wpsp_delete_canned_reply(){             
        include_once( WCE_PLUGIN_DIR.'includes/admin/deletcanned.php' );                        
        die();         
    }
    
    function wpsp_edit_thread(){             
        include_once( WCE_PLUGIN_DIR.'includes/admin/editThread.php' );                        
        die();         
    }
    
    function setEditThread(){             
        include_once( WCE_PLUGIN_DIR.'includes/admin/setEditThread.php' );                        
        die();         
    }
    
    function getTicketStatistics(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/getTicketStatistics.php' );                        
        die();
    }
    
    function wpspgetFrontDashboardStatistics(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/getFrontDashboardStatistics.php' );                        
        die();
    }
    
    function resetETCreateNewTicket(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/resetETCreateNewTicket.php' );                        
        die();
    }
    
    function resetETReplyTicket(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/resetETReplyTicket.php' );                        
        die();
    }
    
    function resetETChangeTicketStatus(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/resetETChangeTicketStatus.php' );                        
        die();
    }
    
    function resetETAssignAgent(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/resetETAssignAgent.php' );                        
        die();
    }
    
    function resetETDeleteTicket(){
        include_once( WCE_PLUGIN_DIR.'includes/admin/resetETDeleteTicket.php' );                        
        die();
    }
}

?>
