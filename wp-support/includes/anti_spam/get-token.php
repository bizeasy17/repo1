<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$auth_token = wp_create_nonce('wpsp_nocaptcha_token');
echo $auth_token;