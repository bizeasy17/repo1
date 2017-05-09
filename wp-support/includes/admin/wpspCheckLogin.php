<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$creds = array();
	$creds['user_login'] = sanitize_text_field($_POST['username']);
	$creds['user_password'] = sanitize_text_field($_POST['password']);
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) )
		_e('Incorrect Username or Password', 'wp-support-plus-responsive-ticket-system');
	else 'OK';
?>