<?php 
	
	/**
	 *
	 * Template part loading the responsive CSS code
	 *
	 **/
	
	// create an access to the template main object
	global $gk_tpl;
	global $fullwidth;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
?>

<style type="text/css">
	.gk-page { max-width: <?php echo get_option($gk_tpl->name . '_template_width', 980); ?>px; }
	
	<?php if (is_home() && get_option($gk_tpl->name . '_override_frontpage', 'Y') == 'Y') : ?>
		<?php if(
			gk_is_active_sidebar('sidebar_left') && 
			($fullwidth != true)
		) : ?>
		#gk-sidebar-left { width: <?php echo get_option($gk_tpl->name . '_frontpage_sidebar_left_width', '30'); ?>%;}
		.gk-page-wrap  { width: <?php echo 100 - get_option($gk_tpl->name . '_frontpage_sidebar_left_width', '30'); ?>%!important; }
		<?php else : ?>
		.gk-page-wrap { width: 100%; }
		<?php endif; ?>
		
		<?php if(
			gk_is_active_sidebar('sidebar_right') && 
			($fullwidth != true)
		) : ?>
		#gk-sidebar-right { width: <?php echo get_option($gk_tpl->name . '_frontpage_sidebar_right_width', '30'); ?>%;}
		#gk-mainbody-columns { width: <?php echo 100 - get_option($gk_tpl->name . '_frontpage_sidebar_right_width', '30'); ?>%!important; }
		
		<?php else : ?>
		#gk-mainbody-columns { width: 100%; }
		<?php endif; ?>
	
	<?php else : ?>
		<?php if(
			gk_is_active_sidebar('sidebar_left') && 
			($fullwidth != true)
		) : ?>
		#gk-sidebar-left { width: <?php echo get_option($gk_tpl->name . '_sidebar_left_width', '30'); ?>%;}
		.gk-page-wrap { width: <?php echo 100 - get_option($gk_tpl->name . '_sidebar_left_width', '30'); ?>%!important; }
		<?php else : ?>
		.gk-page-wrap { width: 100%; }
		<?php endif; ?>
		
		<?php if(
			gk_is_active_sidebar('sidebar_right') && 
			($fullwidth != true)
		) : ?>
		#gk-sidebar-right { width: <?php echo get_option($gk_tpl->name . '_sidebar_right_width', '30'); ?>%;}
		#gk-mainbody-columns { width: <?php echo 100 - get_option($gk_tpl->name . '_sidebar_right_width', '30'); ?>%!important; }
		
		<?php else : ?>
		#gk-mainbody-columns { width: 100%; }
		<?php endif; ?>
	<?php endif; ?>
	
	
	<?php if(
		get_option($gk_tpl->name . '_inner_inset_position', 'right') != 'none' && 
		gk_is_active_sidebar('inner_inset') && 
		($fullwidth != true)
	) : ?>
	#gk-inset { width: <?php echo get_option($gk_tpl->name . '_inner_width', '30'); ?>%;}
	#gk-content-wrap { width: <?php echo 100 - get_option($gk_tpl->name . '_inner_width', '30'); ?>%; }
	<?php else : ?>
	#gk-content-wrap { width: 100%; }
	<?php endif; ?>
	
	#gk-banner-left + #gk-banner-right {
		width: <?php echo get_option($gk_tpl->name . '_right_banner_width', '17.5'); ?>%;
	} 
	
	#gk-banner-left {
		width: <?php echo 100 - get_option($gk_tpl->name . '_right_banner_width', '17.5'); ?>%;
	}
	
	
	<?php 
		$page_content_width = 100;
		$page_content_div_width = 100;
		$content_width = 100;
		$contentwrap_width = 100;
		$highlights_width = 100;
		if(is_home()) {
			$sidebar_right_width = get_option($gk_tpl->name . '_frontpage_sidebar_right_width', '30');
			$sidebar_left_width = get_option($gk_tpl->name . '_frontpage_sidebar_left_width', '30');
		} else {
			$sidebar_right_width = get_option($gk_tpl->name . '_sidebar_right_width', '30');
			$sidebar_left_width = get_option($gk_tpl->name . '_sidebar_left_width', '30');
		}
		
		if(gk_is_active_sidebar('sidebar_left')) {			
			if(gk_is_active_sidebar('updates')) {
				$highlights_width = $highlights_width - $sidebar_left_width;
			}
			
		} else {	
			$sidebar_left_width = 21.25;
		}
		
		if(gk_is_active_sidebar('sidebar_right')) {
			$page_content_div_width = 100 - $sidebar_right_width;
		
			if(gk_is_active_sidebar('search')) {
				$old_highlights_width = $highlights_width;
				$highlights_width = $highlights_width - ($sidebar_right_width * ($old_highlights_width / 100));
			}
		}
	
		if(!gk_is_active_sidebar('sidebar_left') && gk_is_active_sidebar('updates')) {
			$highlights_width = $highlights_width - 21.25;
		}
		
		if(!gk_is_active_sidebar('sidebar_right') && gk_is_active_sidebar('search')) {
			$highlights_width = $highlights_width - 18.5;
		}	
		
	?>

		#gk-updates { width: <?php echo $sidebar_left_width; ?>%!important; }

		#gk-search {
			width: <?php echo $sidebar_right_width * ($old_highlights_width / 100); ?>%!important;
		}
		
		#gk-highlights {
			width: <?php echo $highlights_width; ?>%!important;
		}
		
	
	@media (min-width: <?php echo get_option($gk_tpl->name . '_tablet_width', '800') + 1; ?>px) {
		#gk-mainmenu-collapse { height: auto!important; }
	}
	
</style>

<?php
// check the dependencies for the desktop.small.css file
if(get_option($gk_tpl->name . "_shortcodes3_state", 'Y') == 'Y') {
     wp_enqueue_style('gavern-desktop-small', gavern_file_uri('css/desktop.small.css'), array('gavern-shortcodes-template'), false, '(max-width: '. get_option($gk_tpl->name . '_theme_width', '1410') . 'px)');
} elseif(get_option($gk_tpl->name . "_shortcodes2_state", 'Y') == 'Y') {
     wp_enqueue_style('gavern-desktop-small', gavern_file_uri('css/desktop.small.css'), array('gavern-shortcodes-elements'), false, '(max-width: '. get_option($gk_tpl->name . '_theme_width', '1410') . 'px)');
} elseif(get_option($gk_tpl->name . "_shortcodes1_state", 'Y') == 'Y') {
     wp_enqueue_style('gavern-desktop-small', gavern_file_uri('css/desktop.small.css'), array('gavern-shortcodes-typography'), false, '(max-width: '. get_option($gk_tpl->name . '_theme_width', '1410') . 'px)');
} else {
     wp_enqueue_style('gavern-desktop-small', gavern_file_uri('css/desktop.small.css'), array('gavern-extensions'), false, '(max-width: '. get_option($gk_tpl->name . '__theme_width', '1410') . 'px)');
}

// tablet.css is always loaded after the desktop.small.css file
wp_enqueue_style('gavern-tablet', gavern_file_uri('css/tablet.css'), array('gavern-extensions'), false, '(max-width: '. get_option($gk_tpl->name . '_tablet_width', '1030') . 'px)');

// tablet.small.css is always loaded after the tablet.css file
wp_enqueue_style('gavern-tablet-small', gavern_file_uri('css/tablet.small.css'), array('gavern-tablet'), false, '(max-width: '. get_option($gk_tpl->name . '_small_tablet_width', '820') . 'px)');

// mobile.css is always loaded after the tablet.small.css file
wp_enqueue_style('gavern-mobile', gavern_file_uri('css/mobile.css'), array('gavern-tablet-small'), false, '(max-width: '. get_option($gk_tpl->name . '_mobile_width', '580') . 'px)');