<?php 
	
	/**
	 *
	 * Template elements before the page content
	 *
	 **/
	
	// create an access to the template main object
	global $gk_tpl;
	global $post;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
?>

<div class="gk-page-wrap">	
		<div id="gk-mainbody-columns"<?php if (get_option($gk_tpl->name . '_inner_inset_position', 'right') == 'left') : ?> class="gk-inset-left"<?php endif; ?>>
			<section>
				<?php if(gk_is_active_sidebar('banner_left') || gk_is_active_sidebar('banner_right')) : ?>
				<div id="gk-banners" class="gk-equal-columns">
					<?php if(gk_is_active_sidebar('banner_left')) : ?>
					<div id="gk-banner-left">
						<?php gk_dynamic_sidebar('banner_left'); ?>
					</div>
					<?php endif; ?>
					
					<?php if(gk_is_active_sidebar('banner_right')) : ?>
					<div id="gk-banner-right">						
							<?php gk_dynamic_sidebar('banner_right'); ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if(gk_show_breadcrumbs()) : ?>
				<div id="gk-breadcrumb-area">
					<?php gk_breadcrumbs_output(); ?>
				</div>
				<?php endif; ?>
				
				<?php if(gk_is_active_sidebar('top1')) : ?>
				<div id="gk-top1">
					<div class="widget-area">
						<?php gk_dynamic_sidebar('top1'); ?>
						
						<!--[if IE 8]>
						<div class="ie8clear"></div>
						<![endif]-->
					</div>
				</div>
				<?php endif; ?>
				
				<?php if(gk_is_active_sidebar('top2')) : ?>
				<div id="gk-top2">
					<div class="widget-area">
						<?php gk_dynamic_sidebar('top2'); ?>
						
						<!--[if IE 8]>
						<div class="ie8clear"></div>
						<![endif]-->
					</div>
				</div>
				<?php endif; ?>
					<div id="gk-content-wrap" <?php if(gk_is_active_sidebar('inner_inset') && get_option($gk_tpl->name . '_inner_inset_position', 'right') != 'none') : ?> class="has-inset"<?php endif; ?>>
					<div>
					<!-- Mainbody -->
					<?php if(gk_is_active_sidebar('mainbody_top')) : ?>
					<div id="gk-mainbody-top">
						<?php gk_dynamic_sidebar('mainbody_top'); ?>
					</div>
					<?php endif; ?>