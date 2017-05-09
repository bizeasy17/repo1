<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$generalSettings=get_option( 'wpsp_general_settings' );
$support_permalink=get_permalink($generalSettings['post_id']);
$roleManage=get_option('wpsp_role_management');
$loginUrl=wp_login_url( $support_permalink );

global $wpdb;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );

?>
<div id="loginContainer">
	<?php
	if($generalSettings['enable_default_login']==1){
		include( WCE_PLUGIN_DIR.'includes/loginForm.php' );
	}
	if($generalSettings['enable_default_login']==1 && ($generalSettings['enable_guest_ticket']))
	{?>
	<h3><?php echo __('OR', 'wp-support-plus-responsive-ticket-system');?></h3>
	<?php
	}
	?>
	<?php if($generalSettings['enable_guest_ticket'] && $roleManage['front_ticket_all']){?>
            <?php include( WCE_PLUGIN_DIR.'includes/guestTicketForm.php' );?>
	<?php }?>
</div>
<div id="wsp_wait">
	<img alt="<?php echo __('Please Wait...', 'wp-support-plus-responsive-ticket-system')?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>" />
</div>
