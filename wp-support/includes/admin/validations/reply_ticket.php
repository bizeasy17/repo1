<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('WPSPTicketReply')) :

class WPSPTicketReply {
    
    public $ticket_id;
    public $ticket;
    public $status;
    public $category_id;
    public $priority;
    public $reply_body;
    public $thread_user_id          = 0;
    public $thread_user_object      = false;
    public $guest_name              = '';
    public $guest_email             = '';
    public $thread_user_name;
    public $thread_user_email;
    public $is_pipe                 = 0;
    public $isCKEditorEnabled       = 1;
    public $isNote                  = 0;
    public $attachment_ids          = array();
    public $email_attachments       = array();

    public function __construct() {
        
        //error_log('execution start for __construct() in reply_ticket.php');
        global $wpdb;
        
        $this->getThreadUserDetails();  //error_log('checkpoint1');
        
        $this->ticket_id            = $this->getTicketID(); //error_log('checkpoint2');
        $this->ticket               = $wpdb->get_row( "select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=" . $this->ticket_id );
        $this->is_pipe              = $this->get_isPipe();  //error_log('checkpoint3');
        $this->isCKEditorEnabled    = $this->get_isCKEditorEnabled();
        $this->isNote               = $this->get_isNote();  //error_log('checkpoint4');
        $this->reply_body           = $this->get_reply_body();  //error_log('checkpoint5');
        $this->status               = $this->getStatus();   //error_log('checkpoint6');
        $this->category_id          = $this->getCategory(); //error_log('checkpoint7');
        $this->priority             = $this->getPriority(); //error_log('checkpoint8');
        
        $this->processAttachments();    //error_log('checkpoint9');
        $this->updateTicket();          //error_log('checkpoint10');
        $this->createThread();          //error_log('checkpoint11');
        $this->insert_logs();           //error_log('checkpoint12');
        //error_log('execution end for __construct() in reply_ticket.php');
        
    }
    
    function insert_logs(){
        
        global $wpdb;
        if ($this->status != $this->ticket->status) {
            $threadstatus = array(
                'ticket_id'         => $this->ticket_id,
                'body'              => $this->status,
                'create_time'       => current_time('mysql', 1),
                'created_by'        => $this->thread_user_id,
                'is_note'           => 3
            );
            $wpdb->insert($wpdb->prefix . 'wpsp_ticket_thread', $threadstatus);
        }
        if ( $this->category_id != $this->ticket->cat_id) {
            $threadstatus = array(
                'ticket_id'         => $this->ticket_id,
                'body'              => $this->category_id,
                'create_time'       => current_time('mysql', 1),
                'created_by'        => $this->thread_user_id,
                'is_note'           => 4
            );
            $wpdb->insert($wpdb->prefix . 'wpsp_ticket_thread', $threadstatus);
        }
        if ( $this->priority != $this->ticket->priority) {
            $threadstatus = array(
                'ticket_id'         => $this->ticket_id,
                'body'              => $this->priority,
                'create_time'       => current_time('mysql', 1),
                'created_by'        => $this->thread_user_id,
                'is_note'           => 5
            );
            $wpdb->insert($wpdb->prefix . 'wpsp_ticket_thread', $threadstatus);
        }
        
    }
    
    function get_isNote(){
        
        $isNote = 0;
        if ( isset($_POST['notify']) && $_POST['notify']=='false' ) {
            $isNote = 1;
        }
        return $isNote;
        
    }
    
    function get_isCKEditorEnabled(){
        
        $isCKEditorEnabled = 1;
        if(isset($_POST['ckeditor_enabled'])){
            $isCKEditorEnabled =  intval(sanitize_text_field($_POST['ckeditor_enabled']));
            if ( !is_numeric($isCKEditorEnabled) ) { die(); }
        }
        return $isCKEditorEnabled;
        
    }
    
    function get_isPipe(){
        
        $isPipe=0;
        if(isset($_POST['pipe'])){
            $isPipe = intval(sanitize_text_field($_POST['pipe']));
            if ( !is_numeric($isPipe) ) { die(); }
        }
        return $isPipe;
        
    }
    
    function getTicketID(){
        
        $ticket_id = intval(sanitize_text_field($_POST['ticket_id']));
        if (!$ticket_id) { die(); }
        return $ticket_id;
        
    }
    
    function createThread(){
        global $wpdb;
        $values = array(
            'ticket_id'         => $this->ticket_id,
            'body'              => $this->reply_body,
            'attachment_ids'    => implode(',', $this->attachment_ids),
            'create_time'       => current_time('mysql', 1),
            'created_by'        => $this->thread_user_id,
            'guest_name'        => $this->guest_name,
            'guest_email'       => $this->guest_email,
            'is_note'           => $this->isNote
        );
        $values = apply_filters('wpsp_reply_field_ticket_thread_values', $values, $this);
        $wpdb->insert($wpdb->prefix . 'wpsp_ticket_thread', $values);
    }
    
    function updateTicket(){
        
        global $wpdb;
        $values = array(
            'status'        => $this->status,
            'cat_id'        => $this->category_id,
            'update_time'   => current_time('mysql', 1),
            'priority'      => $this->priority
        );
        $values = apply_filters('wpsp_reply_update_ticket',$values,$this);
        $wpdb->update($wpdb->prefix . 'wpsp_ticket', $values, array( 'id' => $this->ticket_id ));
        
    }
    
    function processAttachments(){
        
        global $wpdb;
        $attachment_ids = $this->sanitize_attachments();
        $attachment_ids = apply_filters('wpsp_reply_email_attachment_ids',$attachment_ids,$this);
        $emailAttachments = array();
        if(count($attachment_ids)){
            foreach ($attachment_ids as $attachment_id) {
                $attachment_path = $wpdb->get_var("select filepath from " . $wpdb->prefix . "wpsp_attachments where id=" . $attachment_id);
                $wpdb->update($wpdb->prefix . 'wpsp_attachments', array('active' => 1), array('id' => $attachment_id));
                $emailAttachments[] = $attachment_path;
            }
        }
        $emailAttachments=apply_filters('wpsp_reply_email_attachment_path',$emailAttachments,$this);
        $this->attachment_ids    = $attachment_ids;
        $this->email_attachments = $emailAttachments;
        
    }
    
    function sanitize_attachments(){
        
        $attachment_ids=array();
        if( isset($_POST['attachment_ids']) && is_array($_POST['attachment_ids']) && count($_POST['attachment_ids']) > 0 ){
            foreach ( $_POST['attachment_ids'] as $attachment_id ){
                $attachment_id = intval(sanitize_text_field($attachment_id));
                if($attachment_id) $attachment_ids[]=$attachment_id;
            }
        }
        return $attachment_ids;
        
    }
    
    function getPriority(){
        
        $priority='';
        if(isset($_POST['reply_ticket_priority'])){
            $priority=sanitize_text_field($_POST['reply_ticket_priority']);
        } else {
            $priority=$this->ticket->priority;
        }
        return apply_filters('wpsp_reply_ticket_priority', $priority, $this);
        
    }
    
    function getCategory(){
        
        $cat_id=0;
        if(isset($_POST['reply_ticket_category'])){
            $cat_id=intval(sanitize_text_field($_POST['reply_ticket_category']));
            if (!$cat_id) { die(); }
        } else {
            $cat_id=$this->ticket->cat_id;
        }
        return apply_filters('wpsp_reply_ticket_category', $cat_id, $this->ticket);
        
    }
    
    function get_reply_body(){
        
        $reply_body='';
        if ( $this->isCKEditorEnabled == 0 ) {
            $reply_body = htmlspecialchars( nl2br($_POST['replyBody']) , ENT_QUOTES );
        } else {
            $reply_body = htmlspecialchars( $_POST['replyBody'] , ENT_QUOTES );
        }
        return apply_filters('wpsp_reply_ticket_body', $reply_body, $this->ticket);
        
    }
    
    function getThreadUserDetails(){
        
        if( isset($_POST['user_id']) ){
            $this->thread_user_id = intval(sanitize_text_field($_POST['user_id']));
            if(!is_numeric($this->thread_user_id)) { die(); }
            if($this->thread_user_id == 0){ 
                $this->guest_name         = sanitize_text_field($_POST['guest_name']);
                $this->guest_email        = sanitize_text_field($_POST['guest_email']);
                $this->thread_user_name   = $this->guest_name;
                $this->thread_user_email  = $this->guest_email;
                $this->thread_user_object = false;
            } else {
                $this->thread_user_object = get_userdata($this->thread_user_id);
                $this->thread_user_name   = $this->thread_user_object->display_name;
                $this->thread_user_email  = $this->thread_user_object->user_email;
            }
        } else if(isset($_POST['guest_email'])) {
            $this->guest_name           = sanitize_text_field($_POST['guest_name']);
            $this->guest_email          = sanitize_text_field($_POST['guest_email']);
            $this->thread_user_name   = $this->guest_name;
            $this->thread_user_email  = $this->guest_email;
            $this->thread_user_object   = get_user_by( 'email', $this->guest_email );
            if( $this->thread_user_object ){    
                $this->thread_user_id     = $this->thread_user_object->ID;
                $this->thread_user_name   = $this->thread_user_object->display_name;
                $this->thread_user_email  = $this->thread_user_object->user_email;
            }
        } else {
            die();
        }
        
    }
            
    function getStatus(){
        
        global $wpdb;
        $generalSettings=get_option( 'wpsp_general_settings' );
        $replyStatus='';
        if(isset($_POST['reply_ticket_status'])){
            $replyStatus = sanitize_text_field($_POST['reply_ticket_status']);
        } else {
            $replyStatus = $this->ticket->status;
        }
        if ($generalSettings['ticket_status_after_cust_reply'] != 'default' && $this->ticket->created_by == $this->thread_user_id) {
            $replyStatus = $generalSettings['ticket_status_after_cust_reply'];
        } else if( $this->thread_user_object && !$this->thread_user_object->has_cap( 'manage_support_plus_ticket' ) ){
            $sql = "select name from {$wpdb->prefix}wpsp_custom_status WHERE id=" . $generalSettings['default_new_ticket_status'];
            $replyStatus = $wpdb->get_var($sql);
        }
        if ( $this->is_pipe == 1 ) {
            $replyStatus = apply_filters('wpsp_reply_ticket_status_after_dashboard_reply', $replyStatus, $this->ticket);
        } else {
            $replyStatus = apply_filters('wpsp_reply_ticket_status_after_pipe_reply', $replyStatus, $this->ticket);
        }
       return $replyStatus;
       
    }
    
}

endif;