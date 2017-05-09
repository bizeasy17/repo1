<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('WPSPTicketCreate')) :

class WPSPTicketCreate {
    
    public $id                  = 0;
    public $subject             = '';
    public $body                = '';
    public $status;
    public $category;
    public $priority;
    public $default_assign_id   = '0';
    public $user_type;
    public $user_id             = 0;
    public $user_object         = false;
    public $guest_name          = '';
    public $guest_email         = '';
    public $user_name;
    public $user_email;
    public $attachment_ids      = array();
    public $email_attachments   = array();
    public $is_pipe;
    public $is_CKEditorEnabled  = 1;
    public $extension_meta      = '';
    public $agentCreated        = 0;
    public $ticket_type         = 0;
    public $time_created;
    public $etCustomField       = array();

    public function __construct() {
        $this->getUserDetails();
        $this->get_isPipe();
        $this->get_CKEditorStatus();
        $this->getExtensionMeta();
        $this->processAttachments();
        $this->getSubject();
        $this->getBody();
        $this->getStatus();
        $this->getCategory();
        $this->getPriority();
        $this->getTicketType();
        $this->getDefaultAssignID();
        $this->getAgentCreated();
        $this->createTicket();
        $this->createTicketThread();
        $this->insertAutoAssignAgentLog();
    }
    
    function insertAutoAssignAgentLog(){
        global $wpdb;
        if ($this->default_assign_id != '0') {
            $threadvalues = array(
                'ticket_id'     => $this->id,
                'body'          => $this->default_assign_id,
                'create_time'   => current_time('mysql', 1),
                'created_by'    => $this->user_id,
                'is_note'       => 2
            );
            $wpdb->insert($wpdb->prefix . 'wpsp_ticket_thread', $threadvalues);
        }
    }
    
    function createTicketThread(){
        global $wpdb;
        $values = array(
            'ticket_id'         => $this->id,
            'body'              => $this->body,
            'attachment_ids'    => implode(',', $this->attachment_ids),
            'create_time'       => current_time('mysql', 1),
            'created_by'        => $this->user_id,
            'guest_name'        => $this->guest_name,
            'guest_email'       => $this->guest_email
        );
        $values = apply_filters('wpsp_reply_field_create_ticket_thread_values', $values);
        $wpdb->insert($wpdb->prefix . 'wpsp_ticket_thread', $values);
    }
            
    function createTicket(){
        global $wpdb;
        $this->time_created = current_time('mysql', 1);
        $values = array(
            'subject'           => $this->subject,
            'created_by'        => $this->user_id,
            'assigned_to'       => $this->default_assign_id,
            'guest_name'        => $this->guest_name,
            'guest_email'       => $this->guest_email,
            'type'              => $this->user_type,
            'status'            => $this->status,
            'cat_id'            => $this->category,
            'create_time'       => $this->time_created,
            'update_time'       => $this->time_created,
            'priority'          => $this->priority,
            'ticket_type'       => $this->ticket_type,
            'agent_created'     => $this->agentCreated,
            'extension_meta'    => $this->extension_meta
        );
        $values = $this->getTicketID($values);
        $values = $this->insertCustomFields($values);
        $values = apply_filters('wpsp_create_new_ticket_values', $values);
        $wpdb->insert($wpdb->prefix . 'wpsp_ticket', $values);
        $this->id = $wpdb->insert_id;
    }
    
    function insertCustomFields($values){
        global $wpdb;
        $customFields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpsp_custom_fields");
        foreach ($customFields as $field) {
            $fieldval='';
            if( isset($_POST['cust' . $field->id]) ){
                if( is_array($_POST['cust' . $field->id]) && !apply_filters('wpsp_extra_custom_fields_db_insert', false, $field) ){
                    $fieldval = sanitize_text_field( implode(",", $_POST['cust' . $field->id]) );
                } else {
                    $fieldval = htmlspecialchars($_POST['cust' . $field->id], ENT_QUOTES);
                }
            }
            if(!$this->isCustomFieldAssignedCategory($field)){
                $fieldval='';
            }
            $values['cust' . $field->id] = apply_filters('wpsp_create_ticket_custom_field_db_val', $fieldval, $field);
            $this->etCustomField['cust' . $field->id] = $values['cust' . $field->id];
        }
        return $values;
    }
    
    function isCustomFieldAssignedCategory($field){
        $catAssignFlag = TRUE;
        $assignCategories = array();
        if ($field->field_categories) {
            $assignCategories = explode(',', $field->field_categories);
        }
        if ($field->field_categories == 0) {
            $catAssignFlag = TRUE;
        } else if (array_search($this->category, $assignCategories) > -1) {
            $catAssignFlag = TRUE;
        } else {
            $catAssignFlag = FALSE;
        }
        return $catAssignFlag;
    }
            
    function getTicketID($values){
        global $wpdb;
        $advancedSettings=get_option( 'wpsp_advanced_settings' );
        if ($advancedSettings['ticketId'] != 1){
            $id = 0;
            do {
                $id = rand(111111, 999999);
                $sql = "select id from {$wpdb->prefix}wpsp_ticket where id=" . $id;
                $result = $wpdb->get_var($sql);
            } while ($result);
            $values['id']=$id;
        }
        return $values;
    }
            
    function getAgentCreated(){
        if ( !$this->is_pipe && is_user_logged_in() && get_current_user_id() != $this->user_id ) {
            $this->agentCreated = get_current_user_id();
        }
    }
            
    function getDefaultAssignID(){
        global $wpdb;
        $default_assignees = $wpdb->get_var("SELECT default_assignee FROM {$wpdb->prefix}wpsp_catagories WHERE id='" . $this->category . "'");
        $this->default_assign_id = apply_filters('wpsp_create_ticket_default_assign_id', $default_assignees, $this);
    }
            
    function getTicketType(){
        if (isset($_POST['create_ticket_type']) && ($_POST['create_ticket_type'] == "on" || $_POST['create_ticket_type'] == 1)) {
            $this->ticket_type = 1;
        }
    }
    
    function getPriority(){
        global $wpdb;
        $priority = $wpdb->get_var("select name from {$wpdb->prefix}wpsp_custom_priority where id=1");
        if(isset($_POST['create_ticket_priority'])){
            $priority = sanitize_text_field($_POST['create_ticket_priority']);
        }
        $this->priority = apply_filters('wpsp_create_ticket_priority', $priority, $this);
    }
            
    function getCategory(){
        $cat_id = 1;
        if(isset($_POST['create_ticket_category'])){
            $cat_id = intval(sanitize_text_field($_POST['create_ticket_category']));
            if(!$cat_id) die();
        }
        $this->category = apply_filters('wpsp_create_ticket_category', $cat_id, $this);
    }
            
    function getStatus(){
        global $wpdb;
        $generalSettings = get_option('wpsp_general_settings');
        $sql = "select name from {$wpdb->prefix}wpsp_custom_status WHERE id=" . $generalSettings['default_new_ticket_status'];
        $status = $wpdb->get_var($sql);
        $this->status = apply_filters('wpsp_create_ticket_status', $status, $this);
    }
    
    function getExtensionMeta(){
        if (isset($_POST['extension_meta'])) {
            $this->extension_meta = sanitize_text_field($_POST['extension_meta']);
        }
    }
    
    function get_CKEditorStatus(){
        if( isset($_POST['ckeditor_enabled']) ){
            $this->is_CKEditorEnabled = intval(sanitize_text_field($_POST['ckeditor_enabled']));
            if ( !is_numeric($this->is_CKEditorEnabled) ) { die(); }
        }
    }
    
    function getBody(){
        $body='';
        if ( $this->is_CKEditorEnabled == 0 || $this->extension_meta ) {
            $body = htmlspecialchars( nl2br($_POST['create_ticket_body']) , ENT_QUOTES );
        } else {
            $body = htmlspecialchars( $_POST['create_ticket_body'] , ENT_QUOTES );
        }
        $this->body = apply_filters('wpsp_create_ticket_body', $body, $this);
    }
    
    function getSubject(){
        $subject='';
        $advancedSettingsFieldOrder = get_option('wpsp_advanced_settings_field_order');
        if(isset($_POST['create_ticket_subject'])){
            $subject = sanitize_text_field( $_POST['create_ticket_subject'] );
        } else {
            $subject = $advancedSettingsFieldOrder['wpsp_default_value_of_subject'];
        }
        $this->subject = apply_filters('wpsp_create_ticket_subject', $subject, $this);
    }
    
    function processAttachments(){
        global $wpdb;
        $attachment_ids = $this->sanitize_attachments();
        $attachment_ids = apply_filters('wpsp_create_email_attachment_ids',$attachment_ids,$this);
        $emailAttachments = array();
        if(count($attachment_ids)){
            foreach ($attachment_ids as $attachment_id) {
                $attachment_path = $wpdb->get_var("select filepath from " . $wpdb->prefix . "wpsp_attachments where id=" . $attachment_id);
                $wpdb->update($wpdb->prefix . 'wpsp_attachments', array('active' => 1), array('id' => $attachment_id));
                $emailAttachments[] = $attachment_path;
            }
        }
        $emailAttachments=apply_filters('wpsp_create_email_attachment_path',$emailAttachments,$this);
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
    
    function getUserDetails(){
        if( !( isset($_POST['user_id']) || isset($_POST['guest_email']) ) ) die();
        $generalSettings = get_option('wpsp_general_settings');
        if( isset($_POST['user_id']) && intval(sanitize_text_field($_POST['user_id'])) == 0 ){
            $this->user_id = intval(sanitize_text_field($_POST['user_id']));
            if(!is_numeric($this->user_id)) die();
            $this->guest_name  = sanitize_text_field($_POST['guest_name']);
            $this->guest_email = sanitize_text_field($_POST['guest_email']);
            $this->user_name   = $this->guest_name;
            $this->user_email  = $this->guest_email;
            $this->user_type   = 'guest';
            $this->user_object = get_user_by( 'email', $this->guest_email );
            if( $this->user_object ){    
                $this->user_type   = 'user';
                $this->user_id     = $this->user_object->ID;
                $this->user_name   = $this->user_object->display_name;
                $this->user_email  = $this->user_object->user_email;
            } else if( !$this->is_pipe && $generalSettings['enable_register_guest_user'] == 1 ){
                $id=register_new_user($this->guest_email, $this->guest_email);
                if(!is_wp_error( $id )){
                    $this->user_id = $id;
                    $this->user_object = get_user_by('email', $this->guest_email);
                    $this->user_type = 'user';
                    wp_update_user(array('ID' => $this->user_id, 'display_name' => $this->guest_name));
                }
            }
        } else if( isset($_POST['user_id']) && intval($_POST['user_id']) ){
            $this->user_id = intval(sanitize_text_field($_POST['user_id']));
            if(!is_numeric($this->user_id)) die();
            $this->user_object = get_userdata($this->user_id);
            $this->user_name   = $this->user_object->display_name;
            $this->user_email  = $this->user_object->user_email;
            $this->user_type   = 'user';
        }
        if( isset($_POST['guest_email']) && !isset($_POST['user_id']) ) {
            $this->user_id     = 0;
            $this->guest_name  = sanitize_text_field($_POST['guest_name']);
            $this->guest_email = sanitize_text_field($_POST['guest_email']);
            $this->user_name   = $this->guest_name;
            $this->user_email  = $this->guest_email;
            $this->user_type   = 'guest';
            $this->user_object = get_user_by( 'email', $this->guest_email );
            if( $this->user_object ){    
                $this->user_type   = 'user';
                $this->user_id     = $this->user_object->ID;
                $this->user_name   = $this->user_object->display_name;
                $this->user_email  = $this->user_object->user_email;
            } else if( !$this->is_pipe && $generalSettings['enable_register_guest_user'] == 1 ){
                $id = register_new_user($this->guest_email, $this->guest_email);
                if( !is_wp_error( $id ) ){
                    $this->user_id = $id;
                    $this->user_object = get_user_by( 'email', $this->guest_email );
                    $this->user_type   = 'user';
                    wp_update_user( array( 'ID' => $this->user_id, 'display_name' => $this->guest_name ) );
                }
            }
        }
    }
    
    function get_isPipe(){
        $isPipe=0;
        if(isset($_POST['pipe'])){
            $isPipe = intval(sanitize_text_field($_POST['pipe']));
            if ( !is_numeric($isPipe) ) { die(); }
        }
        $this->is_pipe = $isPipe;
    }
    
}

endif;