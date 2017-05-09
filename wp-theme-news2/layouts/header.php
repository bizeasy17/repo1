<?php 
	
	/**
	 *
	 * Template header
	 *
	 **/
	
	// create an access to the template main object
	global $gk_tpl;

	// set the flag for the latest posts page template
	$gk_tpl->isLatestPosts = is_page_template('template.latest.php');

?>
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
	wp_enqueue_style('gavern-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css', array('gavern-normalize'), '4.0.3' );;
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
	
	
	<?php if(is_singular() && get_option('thread_comments' )) wp_enqueue_script( 'comment-reply' ); ?>
	
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
<body <?php do_action('gavernwp_body_attributes'); ?>>
	<div id="gk-bg" class="gk-page">	
		<header id="gk-top-bar" <?php if(get_option($gk_tpl->name . '_menu_type', 'overlay') == 'overlay') : ?> class="gk-menu-overlay" <?php endif; ?>>
				<?php if(get_option($gk_tpl->name . "_branding_logo_type", 'css') != 'none') : ?>
					<a href="<?php echo home_url(); ?>" id="gk-logo" class="<?php echo get_option($gk_tpl->name . "_branding_logo_type", 'css'); ?>Logo"><?php gk_blog_logo(); ?></a>
				<?php endif; ?>
				
				<?php if(gk_is_active_sidebar('banner-top')) : ?>
				<div id="gk-banner-top">
					<?php gk_dynamic_sidebar('banner-top'); ?>
				</div>
				<?php endif; ?>
				
				<div id ="gk-top-nav">
					<?php if(gk_show_menu('mainmenu')) : ?>
						<?php gavern_menu('mainmenu', 'main-menu-mobile', array('walker' => new GKMenuWalkerMobile(), 'items_wrap' => '<i class="fa fa-bars"></i><select onchange="window.location.href=this.value;"><option value="#">'.__('Select a page', GKTPLNAME).'</option>%3$s</select>', 'container' => 'div')); ?>
					<?php endif; ?>
					
					<?php if(get_option($gk_tpl->name . '_login_link', 'Y') == 'Y') : ?>
						<?php if(!is_user_logged_in()) : ?>
							<a href="<?php echo get_option($gk_tpl->name . '_login_url', 'wp-login.php?action=login'); ?>" id="gk-login"><?php _e('Log In', GKTPLNAME); ?></a>
						<?php else : ?>
							<a href="<?php echo wp_logout_url(); ?>" id="gk-login"><?php _e('Logout', GKTPLNAME) ?></a>
						<?php endif; ?>
					<?php endif; ?>
	
					<div id ="gk-main-menu" <?php if(get_option($gk_tpl->name . '_menu_type', 'overlay') == 'overlay') : ?> class="gk-menu-overlay" <?php endif; ?>>
						<nav class="gk-menu-wrap">
						<?php if(gk_show_menu('mainmenu')) : ?>
							<?php gavern_menu('mainmenu', 'gk-main-menu', array('walker' => new GKMenuWalker())); ?>
						<?php endif; ?>
						</nav>
					</div>
					<?php if(gk_is_active_sidebar('social')) : ?>
					<div id="gk-social">
						<?php gk_dynamic_sidebar('social'); ?>
					</div>
					<?php endif; ?>
				</div>
				
				<?php if(gk_is_active_sidebar('updates') || gk_is_active_sidebar('highlights') || gk_is_active_sidebar('search')) : ?>
					<div id="gk-toolbar">
					<?php if(gk_is_active_sidebar('updates')) : ?>
						<div id="gk-updates">
							<?php gk_dynamic_sidebar('updates'); ?>
						</div>
					<?php endif; ?>
					<?php if(gk_is_active_sidebar('highlights')) : ?>
						<div id="gk-highlights">
							<?php gk_dynamic_sidebar('highlights'); ?>
						</div>
					<?php endif; ?>
					<?php if(gk_is_active_sidebar('search')) : ?>
						<div id="gk-search">
							<?php gk_dynamic_sidebar('search'); ?>
						</div>
					<?php endif; ?>
					</div>
				<?php endif; ?>
		</header>
	