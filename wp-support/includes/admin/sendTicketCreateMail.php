<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$generalSettings            = get_option( 'wpsp_general_settings' );
$advancedSettings           = get_option( 'wpsp_advanced_settings' );
$emailSettings              = get_option( 'wpsp_email_notification_settings' );
$wpsp_et_create_new_ticket  = get_option( 'wpsp_et_create_new_ticket' );

/*
 * Create ticket link
 */
$wpsp_hash=new WPSP_Hash_Auth();
$wpsp_open_ticket_page_url  = get_permalink(get_option( 'wpsp_ticket_open_page_shortcode' ));
$wpsp_open_ticket_page_url .= '?ticket_id='.$ticket->id.'&auth='.$wpsp_hash->getHash($ticket->id);
$wpsp_open_ticket_page_url  = '<a href="'.$wpsp_open_ticket_page_url.'">'.$wpsp_open_ticket_page_url.'</a>';

$et_success_subject         = __(stripslashes($wpsp_et_create_new_ticket['success_subject']),'wp-support-plus-responsive-ticket-system');
$et_success_body            = __(stripslashes($wpsp_et_create_new_ticket['success_body']),'wp-support-plus-responsive-ticket-system');
$et_success_staff_subject   = __(stripslashes($wpsp_et_create_new_ticket['staff_subject']),'wp-support-plus-responsive-ticket-system');
$et_staff_body              = __(stripslashes($wpsp_et_create_new_ticket['staff_body']),'wp-support-plus-responsive-ticket-system');

$signature='';
if($ticket->user_id){
    $userSignature = $wpdb->get_var( "select signature FROM {$wpdb->prefix}wpsp_agent_settings WHERE agent_id=".$ticket->user_id );
    if($userSignature) $signature='<br>---<br>'.stripcslashes(htmlspecialchars_decode($userSignature,ENT_QUOTES));
}

$agent_created_name='';
if($ticket->agentCreated){
    $agent_created      = get_userdata($ticket->agentCreated);
    $agent_created_name = $agent_created->display_name;
}

$etCategoryName = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}wpsp_catagories where id=".$ticket->category );
$etCategoryName = __($etCategoryName,'wp-support-plus-responsive-ticket-system');
$sql="select * FROM {$wpdb->prefix}wpsp_custom_priority WHERE name="."'$ticket->priority'";
$cust_priority = $wpdb->get_row($sql);
$description    = stripcslashes(htmlspecialchars_decode($ticket->body,ENT_QUOTES));

foreach ($wpsp_et_create_new_ticket['templates'] as $et_key=>$et_val){
	switch ($et_key){
		case 'customer_name': 
			$et_success_subject = str_replace('{customer_name}', $ticket->user_name, $et_success_subject);
			$et_success_body = str_replace('{customer_name}', $ticket->user_name, $et_success_body);
			$et_success_staff_subject = str_replace('{customer_name}', $ticket->user_name, $et_success_staff_subject);
			$et_staff_body = str_replace('{customer_name}', $ticket->user_name, $et_staff_body);
			break;
		case 'customer_email': 
			$et_success_subject = str_replace('{customer_email}', $ticket->user_email, $et_success_subject);
			$et_success_body = str_replace('{customer_email}', $ticket->user_email, $et_success_body);
			$et_success_staff_subject = str_replace('{customer_email}', $ticket->user_email, $et_success_staff_subject);
			$et_staff_body = str_replace('{customer_email}', $ticket->user_email, $et_staff_body);
			break;
		case 'ticket_id': 
			$et_success_subject = str_replace('{ticket_id}', $ticket->id, $et_success_subject);
			$et_success_body = str_replace('{ticket_id}', $ticket->id, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_id}', $ticket->id, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_id}', $ticket->id, $et_staff_body);
			break;
		case 'ticket_subject': 
			$et_success_subject = str_replace('{ticket_subject}', $ticket->subject, $et_success_subject);
			$et_success_body = str_replace('{ticket_subject}', $ticket->subject, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_subject}', $ticket->subject, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_subject}', $ticket->subject, $et_staff_body);
			break;
		case 'ticket_description': 
			$et_success_subject = str_replace('{ticket_description}', $description, $et_success_subject);
			$et_success_body = str_replace('{ticket_description}', $description, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_description}', $description, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_description}', $description, $et_staff_body);
			break;
		case 'ticket_category': 
			$et_success_subject = str_replace('{ticket_category}', $etCategoryName, $et_success_subject);
			$et_success_body = str_replace('{ticket_category}', $etCategoryName, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_category}', $etCategoryName, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_category}', $etCategoryName, $et_staff_body);
			break;
		case 'ticket_priority': 
            $et_success_subject = str_replace('{ticket_priority}', $cust_priority->name, $et_success_subject);
			$et_success_body = str_replace('{ticket_priority}', $cust_priority->name, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_priority}', $cust_priority->name, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_priority}', $cust_priority->name, $et_staff_body);
			break;
                case 'ticket_url': 
			$et_success_body = str_replace('{ticket_url}', $wpsp_open_ticket_page_url, $et_success_body);
			$et_staff_body = str_replace('{ticket_url}', $wpsp_open_ticket_page_url, $et_staff_body);
			break;
                case 'time_created':
                        $et_success_subject = str_replace('{time_created}', $ticket->time_created, $et_success_subject);
 			$et_success_body = str_replace('{time_created}', $ticket->time_created, $et_success_body);
 			$et_success_staff_subject = str_replace('{time_created}', $ticket->time_created, $et_success_staff_subject);
 			$et_staff_body = str_replace('{time_created}', $ticket->time_created, $et_staff_body);
			break;
                case 'agent_created':
                        $et_success_subject = str_replace('{agent_created}', $agent_created_name, $et_success_subject);
 			$et_success_body = str_replace('{agent_created}', $agent_created_name, $et_success_body);
 			$et_success_staff_subject = str_replace('{agent_created}', $agent_created_name, $et_success_staff_subject);
 			$et_staff_body = str_replace('{agent_created}', $agent_created_name, $et_staff_body);
			break;
		default:
			break;
	}
}
foreach ($ticket->etCustomField as $etFieldKey=>$etFieldVal){
    $etFieldVal                 = stripcslashes(htmlspecialchars_decode($etFieldVal,ENT_QUOTES));
    $etFieldVal                 = apply_filters('wpsp_email_template_key_value',$etFieldVal,$etFieldKey);
    $et_success_subject         = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_subject);
    $et_success_body            = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_body);
    $et_success_staff_subject   = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_staff_subject);
    $et_staff_body              = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_staff_body);
}

$headers = array("Content-Type: text/html;charset=utf-8");
$headers[] = 'From: ' . $emailSettings['default_from_name'] . ' <' . $emailSettings['default_from_email'] . '>';
if ( isset( $emailSettings['default_reply_to']) && $emailSettings['default_reply_to'] != '' ) {
	$headers[] = 'Reply-To: ' .  $emailSettings['default_reply_to'];
}
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

$ignore_emails=explode("\n",$emailSettings['ignore_emails']);

$piping_emails=array();
if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='cpanel'){
    $piping_mail=$emailSettings['default_reply_to'];
    if(!$emailSettings['default_reply_to']){
        $piping_mail=$emailSettings['default_from_email'];
    }
    $piping_emails[]=$piping_emails;
} else if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='imap'){
    $imap_pipe_list=get_option( 'wpsp_imap_pipe_list' );
    foreach ($imap_pipe_list as $pipe_connection){
        $piping_emails[]=$pipe_connection['pipe_email'];
    }
}

if (!isset($_POST['agent_silent_create']) && !(array_search($ticket->user_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $ticket->user_email)) {
    if ($wpsp_et_create_new_ticket['enable_success']) {
        $subject_user = '[' . __($advancedSettings['ticket_label_alice'][1], 'wp-support-plus-responsive-ticket-system') . ' ' . $advancedSettings['wpsp_ticket_id_prefix'] . $ticket->id . '] ' . $et_success_subject;
        $body_user = '';
        if ($emailSettings['enable_email_pipe'] && $advancedSettings['reply_above'] == 1) {
            $body_user.='----------reply above----------';
            $body_user.='<br><br>';
        }
        $body_user.=$et_success_body;
        wp_mail($ticket->user_email, $subject_user, $body_user, $headers);
    }
}

$to=array();

if($wpsp_et_create_new_ticket['staff_to_notify']['assigned_agent'] && $ticket->default_assign_id != '0'){
	$assigned_users=explode(',', $ticket->default_assign_id);
	foreach ($assigned_users as $user){
		$userdata=get_userdata($user);
		if($ticket->user_email!=$userdata->user_email){
			$to[] = $userdata->user_email;
		}
	}
}

$administrator_emails=explode("\n",$emailSettings['administrator_emails']);
if($wpsp_et_create_new_ticket['staff_to_notify']['administrator']){
    if($administrator_emails && !$to){
        $to=$administrator_emails;
    }
    else if($administrator_emails){
        foreach ($administrator_emails as $admin_email){
            if($ticket->user_email != $admin_email && !(array_search($admin_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $admin_email)){
                $headers[] = " Bcc:" . $admin_email;
            }
        }
    }
}

$roleManage=get_option( 'wpsp_role_management' );

if($wpsp_et_create_new_ticket['staff_to_notify']['supervisor']){
    $supervisors=array();
    $supervisors=array_merge($supervisors,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
    foreach($roleManage['supervisors'] as $supervisorRole)
    {
        $supervisors=array_merge($supervisors,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
    }
    $supervisors_emails=array();
    foreach ($supervisors as $supervisor){
        $supervisors_emails[]=$supervisor->user_email;
    }

    $supervisors_emails=apply_filters('wpsp_mail_supervisor',$supervisors_emails,$ticket);

    if($supervisors_emails && !$to){
        $to=$supervisors_emails;
    }
    else if($supervisors_emails){
        foreach ($supervisors_emails as $supervisor_email){
            if($ticket->user_email!=$supervisor_email && !(array_search($supervisor_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $supervisor_email)){
                $headers[] = " Bcc:" . $supervisor_email;
            }
        }
    }
}

if($wpsp_et_create_new_ticket['staff_to_notify']['all_agents']){
    $agents=array();
    $agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
    foreach($roleManage['agents'] as $agentRole){
        $agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
    }
    $agents_emails=array();
    foreach ($agents as $agent){
        $agents_emails[]=$agent->user_email;
    }
    if($agents_emails && !$to){
        $to=$agents_emails;
    }
    else if($agents_emails){
        foreach ($agents_emails as $agents_email){
            if($ticket->user_email!=$agents_email && !(array_search($agents_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $agents_email)){
                $headers[] = " Bcc:" . $agents_email;
            }
        }
    }
}

foreach ($to as $key=>$val){
    if(array_search($val, $piping_emails)>-1 && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $val)){
        unset($to[$key]);
    }
}

$to_new=array();
foreach ($to as $to_email){
    if($to_email != $ticket->user_email){
        $to_new[]=$to_email;
    }
}

$to = apply_filters('wpsp_create_ticket_to_email_addresses',$to_new,$ticket);

$subject='['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system').' '.$advancedSettings['wpsp_ticket_id_prefix'].$ticket->id.'] '.$et_success_staff_subject;
$body='';
if ($emailSettings['enable_email_pipe']){
	$body.='----------reply above----------';
	$body.='<br><br>';
}
$body.=$et_staff_body.$signature;

//error_log(PHP_EOL.'To emails in create ticket:'.PHP_EOL.implode(PHP_EOL, $to_new));
//error_log(PHP_EOL.'Headers in create ticket:'.PHP_EOL.implode(PHP_EOL, $headers));
//error_log('Subject:'.PHP_EOL.$subject);
//error_log('Body:'.PHP_EOL.$body);
if($to){
    $emailAttachments=apply_filters('wpsp_emailattachment_in_sendticketcreatemail_template',$ticket->email_attachments, $ticket,$to,$headers);
    $headers=apply_filters('wpsp_after_send_staff_email_in_sendticketcreatemail',$headers,$ticket,$piping_emails,$ignore_emails,$emailAttachments);
    wp_mail($to,$subject,$body,$headers,$emailAttachments);
    add_filter('wp_mail_content_type',create_function('', 'return "text/plain"; '));
}