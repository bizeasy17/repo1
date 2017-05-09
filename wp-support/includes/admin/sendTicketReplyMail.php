<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

global $wpdb;
$generalSettings = get_option('wpsp_general_settings');
$emailSettings = get_option('wpsp_email_notification_settings');
$wpsp_et_reply_ticket = get_option('wpsp_et_reply_ticket');
$advancedSettings = get_option('wpsp_advanced_settings');

/*
 * Create ticket link
 */
$wpsp_hash=new WPSP_Hash_Auth();
$wpsp_open_ticket_page_url  = get_permalink(get_option( 'wpsp_ticket_open_page_shortcode' ));
$wpsp_open_ticket_page_url .= '?ticket_id='.$reply->ticket_id.'&auth='.$wpsp_hash->getHash($reply->ticket_id);
$wpsp_open_ticket_page_url  = '<a href="'.$wpsp_open_ticket_page_url.'">'.$wpsp_open_ticket_page_url.'</a>';

$ignore_emails = explode("\n", $emailSettings['ignore_emails']);

$customFields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpsp_custom_fields");
$etCustomField = array();
foreach ($customFields as $field) {
    $cust_alice = 'cust' . $field->id;
    if ($field->field_type == '5') {
        $etCustomField['cust' . $field->id] = nl2br($reply->ticket->$cust_alice);
    } else {
        $etCustomField['cust' . $field->id] = $reply->ticket->$cust_alice;
    }
}

$et_success_staff_subject = __(stripslashes($wpsp_et_reply_ticket['reply_subject']),'wp-support-plus-responsive-ticket-system');
$et_staff_body = __(stripslashes($wpsp_et_reply_ticket['reply_body']),'wp-support-plus-responsive-ticket-system');

$signature = '';
if ($reply->thread_user_id && $reply->thread_user_object->has_cap('manage_support_plus_ticket')) {
    $userSignature = $wpdb->get_var("select signature FROM {$wpdb->prefix}wpsp_agent_settings WHERE agent_id=" . $reply->thread_user_id);
    if ($wpdb->num_rows) {
        $signature = '<br>---<br>' . stripcslashes(htmlspecialchars_decode($userSignature, ENT_QUOTES));
    }
}

$etCategoryName = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}wpsp_catagories where id=" . $reply->ticket->cat_id);
$etCategoryName = __($etCategoryName, 'wp-support-plus-responsive-ticket-system');
$description = htmlspecialchars_decode($reply->reply_body, ENT_QUOTES);
$description = apply_filters('wpsp_et_reply_description', $description, $reply->ticket);
$priority = __($reply->priority, 'wp-support-plus-responsive-ticket-system');
$sql="select * FROM {$wpdb->prefix}wpsp_custom_priority WHERE name="."'$reply->priority'";
$cust_priority = $wpdb->get_row($sql);

$wpsp_subject = stripslashes(htmlspecialchars_decode($reply->ticket->subject, ENT_QUOTES));
$customerName = '';
$customerEmail = '';
if ($reply->ticket->created_by) {
    $user = get_userdata($reply->ticket->created_by);
    $customerName = $user->display_name;
    $customerEmail = $user->user_email;
} else {
    $customerName = $reply->ticket->guest_name;
    $customerEmail = $reply->ticket->guest_email;
}

$ticketStatus = __($reply->status, 'wp-support-plus-responsive-ticket-system');
foreach ($wpsp_et_reply_ticket['templates'] as $et_key => $et_val) {
    switch ($et_key) {
        case 'reply_by_name':
            $et_success_staff_subject = str_replace('{reply_by_name}', $reply->thread_user_name, $et_success_staff_subject);
            $et_staff_body = str_replace('{reply_by_name}', $reply->thread_user_name, $et_staff_body);
            break;
        case 'reply_by_email':
            $et_success_staff_subject = str_replace('{reply_by_email}', $reply->thread_user_email, $et_success_staff_subject);
            $et_staff_body = str_replace('{reply_by_email}', $reply->thread_user_email, $et_staff_body);
            break;
        case 'ticket_status':
            $et_success_staff_subject = str_replace('{ticket_status}', $ticketStatus, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_status}', $ticketStatus, $et_staff_body);
            break;
        case 'customer_name':
            $et_success_staff_subject = str_replace('{customer_name}', $customerName, $et_success_staff_subject);
            $et_staff_body = str_replace('{customer_name}', $customerName, $et_staff_body);
            break;
        case 'customer_email':
            $et_success_staff_subject = str_replace('{customer_email}', $customerEmail, $et_success_staff_subject);
            $et_staff_body = str_replace('{customer_email}', $customerEmail, $et_staff_body);
            break;
        case 'ticket_id':
            $et_success_staff_subject = str_replace('{ticket_id}', $reply->ticket_id, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_id}', $reply->ticket_id, $et_staff_body);
            break;
        case 'ticket_subject':
            $et_success_staff_subject = str_replace('{ticket_subject}', $wpsp_subject, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_subject}', $wpsp_subject, $et_staff_body);
            break;
        case 'reply_description':
            $et_success_staff_subject = str_replace('{reply_description}', $description, $et_success_staff_subject);
            $et_staff_body = str_replace('{reply_description}', $description, $et_staff_body);
            break;
        case 'ticket_category':
            $et_success_staff_subject = str_replace('{ticket_category}', $etCategoryName, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_category}', $etCategoryName, $et_staff_body);
            break;
        case 'ticket_priority':
            $et_success_staff_subject = str_replace('{ticket_priority}', $cust_priority->name, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_priority}', $cust_priority->name, $et_staff_body);
            break;
        case 'ticket_url':
            $et_staff_body = str_replace('{ticket_url}', $wpsp_open_ticket_page_url, $et_staff_body);
            break;
        case 'time_created':
            $et_success_staff_subject = str_replace('{time_created}', $reply->ticket->create_time, $et_success_staff_subject);
            $et_staff_body = str_replace('{time_created}', $reply->ticket->create_time, $et_staff_body);
            break;
        default:
            break;
    }
}
foreach ($etCustomField as $etFieldKey => $etFieldVal) {
    $etFieldVal = apply_filters('wpsp_email_template_key_value', $etFieldVal, $etFieldKey);
    $et_success_staff_subject = str_replace('{' . $etFieldKey . '}', $etFieldVal, $et_success_staff_subject);
    $et_staff_body = str_replace('{' . $etFieldKey . '}', $etFieldVal, $et_staff_body);
}

/*
 * Send Email based on settings
 */
$piping_emails = array();
if ($emailSettings['enable_email_pipe'] && $emailSettings['piping_type'] == 'cpanel') {
    $piping_mail = $emailSettings['default_reply_to'];
    if (!$emailSettings['default_reply_to']) {
        $piping_mail = $emailSettings['default_from_email'];
    }
    $piping_emails[] = $piping_emails;
} else if ($emailSettings['enable_email_pipe'] && $emailSettings['piping_type'] == 'imap') {
    $imap_pipe_list = get_option('wpsp_imap_pipe_list');
    foreach ($imap_pipe_list as $pipe_connection) {
        $piping_emails[] = $pipe_connection['pipe_email'];
    }
}

$headers = array("Content-Type: text/html;charset=utf-8");
$headers[] = 'From: ' . $emailSettings['default_from_name'] . ' <' . $emailSettings['default_from_email'] . '>';
if (isset($emailSettings['default_reply_to']) && $emailSettings['default_reply_to'] != '') {
    $headers[] = 'Reply-To: ' . $emailSettings['default_reply_to'];
}

if (isset($_POST['reply_cc']) && $_POST['reply_cc'] != '') {
    $reply_cc = explode(',', $_POST['reply_cc']);
    foreach ($reply_cc as $cc) {
        if (!(array_search($cc, $piping_emails) > -1)) {
            $headers[] = 'Cc: ' . sanitize_text_field($cc);
        }
    }
}
if (isset($_POST['reply_bcc']) && $_POST['reply_bcc'] != '') {
    $reply_bcc = explode(',', $_POST['reply_bcc']);
    foreach ($reply_bcc as $bcc) {
        if (!(array_search($bcc, $piping_emails) > -1)) {
            $headers[] = 'Bcc: ' . sanitize_text_field($bcc);
        }
    }
}
add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));


$subject = '[' . __($advancedSettings['ticket_label_alice'][1], 'wp-support-plus-responsive-ticket-system') . ' ' . $advancedSettings['wpsp_ticket_id_prefix'] . $reply->ticket_id . '] ' . $et_success_staff_subject;
$body = '';
if ($emailSettings['enable_email_pipe'] && $advancedSettings['reply_above'] == 1) {
    $body.='----------reply above----------';
    $body.='<br><br><br>';
}
$body.=$et_staff_body . $signature;

$to = array();

if ($wpsp_et_reply_ticket['notify_to']['customer'] && $reply->thread_user_email != $customerEmail) {
    $to[] = $customerEmail;
}

if ($wpsp_et_reply_ticket['notify_to']['assigned_agent'] && $reply->ticket->assigned_to != '0') {

    $assigned_users = explode(',', $reply->ticket->assigned_to);
    $assigned_users = apply_filters('wpsp_reply_email_assign_agent', $assigned_users, $reply->ticket, $reply->thread_user_email);

    if (!$to) {
        foreach ($assigned_users as $user) {
            $userdata = get_userdata($user);
            if ($reply->thread_user_email != $userdata->user_email) {
                $to[] = $userdata->user_email;
            }
        }
    } else {
        foreach ($assigned_users as $user) {
            $userdata = get_userdata($user);
            if ($reply->thread_user_email != $userdata->user_email && !(array_search($userdata->user_email, $piping_emails) > -1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $userdata->user_email)) {
                $headers[] = "Bcc:" . $userdata->user_email;
            }
        }
    }
}

$administrator_emails = explode("\n", $emailSettings['administrator_emails']);
if ($wpsp_et_reply_ticket['notify_to']['administrator']) {
    if ($administrator_emails && !$to) {
        $to = $administrator_emails;
    } else if ($administrator_emails) {
        foreach ($administrator_emails as $admin_email) {
            if ($reply->thread_user_email != $admin_email && !(array_search($admin_email, $piping_emails) > -1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $admin_email)) {
                $headers[] = "Bcc:" . $admin_email;
            }
        }
    }
}

$roleManage = get_option('wpsp_role_management');

if ($wpsp_et_reply_ticket['notify_to']['supervisor']) {
    $supervisors = array();
    $supervisors = array_merge($supervisors, get_users(array('orderby' => 'display_name', 'role' => 'wp_support_plus_supervisor')));
    foreach ($roleManage['supervisors'] as $supervisorRole) {
        $supervisors = array_merge($supervisors, get_users(array('orderby' => 'display_name', 'role' => $supervisorRole)));
    }
    $supervisors_emails = array();
    foreach ($supervisors as $supervisor) {
        $supervisors_emails[] = $supervisor->user_email;
    }

    $supervisors_emails = apply_filters('wpsp_mail_supervisor', $supervisors_emails, $reply->ticket);

    if ($supervisors_emails && !$to) {
        $to = $supervisors_emails;
    } else if ($supervisors_emails) {
        foreach ($supervisors_emails as $supervisor_email) {
            if ($reply->thread_user_email != $supervisor_email && !(array_search($supervisor_email, $piping_emails) > -1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $supervisor_email)) {
                $headers[] = "Bcc:" . $supervisor_email;
            }
        }
    }
}

if ($wpsp_et_reply_ticket['notify_to']['all_agents']) {
    $agents = array();
    $agents = array_merge($agents, get_users(array('orderby' => 'display_name', 'role' => 'wp_support_plus_agent')));
    foreach ($roleManage['agents'] as $agentRole) {
        $agents = array_merge($agents, get_users(array('orderby' => 'display_name', 'role' => $agentRole)));
    }

    $agents_emails = array();
    foreach ($agents as $agent) {
        $agents_emails[] = $agent->user_email;
    }

    $agents_emails = apply_filters('wpsp_reply_email_all_agent', $agents_emails, $reply->ticket, $reply->thread_user_email);

    if ($agents_emails && !$to) {
        $to = $agents_emails;
    } else if ($agents_emails) {
        foreach ($agents_emails as $agents_email) {
            if ($reply->thread_user_email != $agents_email && !(array_search($agents_email, $piping_emails) > -1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $agents_email)) {
                $headers[] = "Bcc:" . $agents_email;
            }
        }
    }
}

foreach ($to as $key => $val) {
    if ((array_search($val, $piping_emails) > -1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $val)) {
        unset($to[$key]);
    }
}
  
//error_log(PHP_EOL.'To emails in reply ticket:'.PHP_EOL.implode(PHP_EOL, $to));
//error_log(PHP_EOL.'Headers in reply ticket:'.PHP_EOL.implode(PHP_EOL, $headers));
//error_log('Subject:'.PHP_EOL.$subject);
//error_log('Body:'.PHP_EOL.$body);

if ($to) {
    $emailAttachments = apply_filters('wpsp_emailattachment_in_sendticketreplyemail_template', $reply->email_attachments, $reply->ticket, $to, $headers);
    $headers=apply_filters('wpsp_after_send_staff_email_in_sendticketreplyemail',$headers,$reply->ticket,$piping_emails,$ignore_emails,$emailAttachments);
    wp_mail($to, $subject, $body, $headers, $emailAttachments);
    add_filter('wp_mail_content_type', create_function('', 'return "text/plain"; '));
}
?>
