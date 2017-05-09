<?php 
	
	/**
	 *
	 * Template footer
	 *
	 **/
	
	// create an access to the template main object
	global $gk_tpl;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
?>

	<footer id="gk-footer">
		<div class="gk-page">			
			<div id="gk-footer-area">
				<?php if(get_option($gk_tpl->name . '_template_footer_top_button', 'Y') == 'Y') : ?>
					<a href="#gk-bg" id="gk-back-to-top"><?php _e('Back to top', GKTPLNAME); ?></a>
				<?php endif; ?>
				<?php if(gk_is_active_sidebar('newsletter')) : ?>
				<div id="gk-newsletter">
					<?php gk_dynamic_sidebar('newsletter'); ?>	
				</div>
				<?php endif; ?>
			</div>
			
			<?php gavern_menu('footermenu', 'gk-footer-menu'); ?>
			
			<div class="gk-copyrights">
				<?php echo str_replace('\\', '', htmlspecialchars_decode(get_option($gk_tpl->name . '_template_footer_content', ''))); ?>
			</div>
			
			<?php if(get_option($gk_tpl->name . '_styleswitcher_state', 'Y') == 'Y') : ?>
			<div id="gk-style-area">
				<?php for($i = 0; $i < count($gk_tpl->styles); $i++) : ?>
				<div class="gk-style-switcher-<?php echo $gk_tpl->styles[$i]; ?>">
					<?php 
						$j = 1;
						foreach($gk_tpl->style_colors[$gk_tpl->styles[$i]] as $stylename => $link) : 
					?> 
					<a href="#<?php echo $link; ?>" id="gk-color<?php echo $j++; ?>"><?php echo $stylename; ?></a>
					<?php endforeach; ?>
				</div>
				<?php endfor; ?>
			</div>
			<?php endif; ?>
			
			<?php if(get_option($gk_tpl->name . '_template_footer_logo', 'Y') == 'Y') : ?>
			<img src="<?php echo gavern_file_uri('images/gavernwp.png'); ?>" class="gk-framework-logo" alt="GavernWP" />
			<?php endif; ?>
		</div>
	</footer>
	
	<?php gk_load('social'); ?>
	
	<?php do_action('gavernwp_footer'); ?>
	
	<?php 
		echo stripslashes(
			htmlspecialchars_decode(
				str_replace( '&#039;', "'", get_option($gk_tpl->name . '_footer_code', ''))
			)
		); 
	?>
	
	<?php wp_footer(); ?>
	
	<?php do_action('gavernwp_ga_code'); ?>
</body>
</html>
