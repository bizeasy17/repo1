<?php
/*
Template Name: Login Page
*/

global $gk_tpl;

?>

<?php if ( is_user_logged_in() ) : 
	gk_load('header');
	gk_load('before');
	
	?>
	
	<section id="gk-mainbody" class="loginpage">
		<?php the_post(); ?>
		
		<h1 class="page-title"><?php the_title(); ?></h1>
		
		<article>
			
			<?php if ( is_user_logged_in() ) : ?>
				<?php 
					
					global $current_user;
					get_currentuserinfo();
				
				?>
				
				<p>
					<?php echo __('Hi, ', GKTPLNAME) . ($current_user->user_firstname) . ' ' . ($current_user->user_lastname) . ' (' . ($current_user->user_login) . ') '; ?>
					 <a href="<?php echo wp_logout_url(); ?>" title="<?php _e('Logout', GKTPLNAME); ?>">
						 <?php _e('Logout', GKTPLNAME); ?>
					 </a>
				</p>
			
			<?php else : ?>
			    
				<?php 
					wp_login_form(
						array(
							'echo' => true,
							'form_id' => 'loginform',
							'label_username' => __( 'Username', GKTPLNAME ),
							'label_password' => __( 'Password', GKTPLNAME ),
							'label_remember' => __( 'Remember Me', GKTPLNAME ),
							'label_log_in' => __( 'Log In', GKTPLNAME ),
							'id_username' => 'user_login',
							'id_password' => 'user_pass',
							'id_remember' => 'rememberme',
							'id_submit' => 'wp-submit',
							'remember' => true,
							'value_username' => NULL,
							'value_remember' => false 
						)
					); 
				?>
				
				<nav class="small">
					<ul>
						<li>
							<a href="<?php echo home_url(); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost and Found', GKTPLNAME); ?>"><?php _e('Lost your password?', GKTPLNAME); ?></a>
						</li>
						<li>
							<a href="<?php echo home_url(); ?>/wp-login.php?action=register" title="<?php _e('Not a member? Register', GKTPLNAME); ?>"><?php _e('Register', GKTPLNAME); ?></a>
						</li>
					</ul>
				</nav>
			
			<?php endif; ?>
		
		</article>
	</section>
	
	<?php
	
	gk_load('after');
	gk_load('footer');
	?>

<?php else : ?>
	<?php do_action('gavernwp_doctype'); ?>
	<html <?php do_action('gavernwp_html_attributes'); ?>>
	<head>
		<title><?php do_action('gavernwp_title'); ?></title>
		<?php do_action('gavernwp_metatags'); ?>
		
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="shortcut icon" href="<?php get_stylesheet_directory_uri(); ?>/favicon.ico" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
		wp_enqueue_style('gavern-normalize', gavern_file_uri('css/normalize.css'), false);
		wp_enqueue_style('gavern-font-awesome', gavern_file_uri('css/font-awesome.css'), array('gavern-normalize'));
		wp_enqueue_style('gavern-template', gavern_file_uri('css/template.css'), array('gavern-font-awesome'));
		wp_enqueue_style('gavern-wp', gavern_file_uri('css/wp.css'), array('gavern-template'));
		wp_enqueue_style('gavern-stuff', gavern_file_uri('css/stuff.css'), array('gavern-wp'));
		wp_enqueue_style('gavern-wpextensions', gavern_file_uri('css/wp.extensions.css'), array('gavern-stuff'));
		wp_enqueue_style('gavern-extensions', gavern_file_uri('css/extensions.css'), array('gavern-wpextensions'));
		wp_enqueue_style('gavern-weather', gavern_file_uri('gavern/icons_weather/meteocons_font/stylesheet.css'), array('gavern-extensions'));
		?>
		<!--[if IE 9]>
		<link rel="stylesheet" href="<?php echo gavern_file_uri('css/ie9.css'); ?>" />
		<![endif]-->
		<!--[if lte IE 8]>
		<link rel="stylesheet" href="<?php echo gavern_file_uri('css/ie8.css'); ?>" />
		<div id="ie-toolbar">
		<div><?php _e('You are using an unsupported version of Internet Explorer. Please', GKTPLNAME); ?>
		<a href="http://windows.microsoft.com/en-us/internet-explorer/products/ie/home"><?php _e('upgrade your browser', GKTPLNAME); ?></a>
		<?php _e('for the best user experience on our site. Thank you.', GKTPLNAME); ?>
		</div>
		</div>
		<![endif]-->
		
		<?php do_action('gavernwp_ie_scripts'); ?>
		
		<?php gk_head_shortcodes(); ?>
			  
		<?php 
		 gk_load('responsive_css'); 
		 
		 if(get_option($gk_tpl->name . "_overridecss_state", 'Y') == 'Y') {
		   wp_enqueue_style('gavern-override', gavern_file_uri('css/override.css'), array('gavern-style'));
		 }
		?>
		
		<?php
		if(get_option($gk_tpl->name . '_prefixfree_state', 'N') == 'Y') {
		  wp_enqueue_script('gavern-prefixfree', gavern_file_uri('js/prefixfree.js'));
		} 
		?>
		
		<?php gk_head_style_css(); ?>
		<?php gk_head_style_pages(); ?>
		
		<?php gk_thickbox_load(); ?>
		<?php wp_head(); ?>
		
		<?php do_action('gavernwp_fonts'); ?>
		<?php gk_head_config(); ?>
		<?php wp_enqueue_script("jquery"); ?>
		
		<?php
		    wp_enqueue_script('gavern-scripts', gavern_file_uri('js/gk.scripts.js'), array('jquery'), false, true);
		    wp_enqueue_script('gavern-menu', gavern_file_uri('js/gk.menu.js'), array('jquery', 'gavern-scripts'), false, true);
		    wp_enqueue_script('gavern-modernizr', gavern_file_uri('js/modernizr.js'), false, false, true);
		    wp_enqueue_script('gavern-video', gavern_file_uri('js/jquery.fitvids.js'), false, false, true); 
		?>
		
		<?php do_action('gavernwp_head'); ?>
		
		<?php 
			echo stripslashes(
				htmlspecialchars_decode(
					str_replace( '&#039;', "'", get_option($gk_tpl->name . '_head_code', ''))
				)
			); 
		?>
	</head>
	<body class="loginpage">
		<div class="gk-page">
		<section id="gk-page-wrap" class="loginpage">
			<?php the_post(); ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<div id="gk-login-wrap">
				<div class="left">
					<?php the_content(); ?>
					<a href="<?php echo home_url(); ?>/wp-login.php?action=register" title="<?php _e('Not a member? Register', GKTPLNAME); ?>" class="register"><?php _e('Create an account', GKTPLNAME); ?></a>
				</div>
				<div class="right">
				
				<h2><?php _e('Already a member?', GKTPLNAME); ?></h2>
				<p><?php _e('Sign into your account right now.', GKTPLNAME); ?></p>
				<?php if ( is_user_logged_in() ) : ?>
					<?php 
						
						global $current_user;
						get_currentuserinfo();
					
					?>
					<p>
						<?php echo __('Hi, ', GKTPLNAME) . ($current_user->user_firstname) . ' ' . ($current_user->user_lastname) . ' (' . ($current_user->user_login) . ') '; ?>
						 <a href="<?php echo wp_logout_url(); ?>" title="<?php _e('Logout', GKTPLNAME); ?>">
							 <?php _e('Logout', GKTPLNAME); ?>
						 </a>
					</p>
				
				<?php else : ?>
				    
					<?php 
						gk_login_form(
							array(
								'echo' => true,
								'form_id' => 'gk-loginform',
								'label_remember' => __( 'Remember Me', GKTPLNAME ),
								'label_log_in' => __( 'Log In', GKTPLNAME ),
								'label_password' => __( 'Password', GKTPLNAME ),
								'username_placeholder' => __( 'Username', GKTPLNAME ),
								'password_placeholder' => __( 'Password', GKTPLNAME ),
								'id_username' => 'gk-user_login',
								'id_password' => 'gk-user_pass',
								'id_remember' => 'gk-rememberme',
								'id_submit' => 'gk-wp-submit',
								'remember' => false,
								'value_username' => NULL,
								'value_remember' => false 
							)
						); 
					?>
					
					<nav class="small">
						<ul>
							<li>
								<a href="<?php echo home_url(); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost and Found', GKTPLNAME); ?>"><?php _e('Lost your password?', GKTPLNAME); ?></a>
							</li>
						</ul>
					</nav>
				
				<?php endif; ?>
				</div>
			</div>
			<div class="gk-cancel"><span><?php _e('or', GKTPLNAME); ?> <a href="<?php echo home_url(); ?>" title="Cancel"><?php _e('Cancel', GKTPLNAME); ?></a></span></div>
		</section>
		</div>
	</body>
	</html>
<?php endif; ?>
<?php

// EOF