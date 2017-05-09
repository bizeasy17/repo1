<?php 
	
	/**
	 *
	 * Template elements after the page content
	 *
	 **/
	
	// create an access to the template main object
	global $gk_tpl;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
?>
		
					<?php if(gk_is_active_sidebar('mainbody_bottom')) : ?>
					<div id="gk-mainbody-bottom">
						<?php gk_dynamic_sidebar('mainbody_bottom'); ?>
					</div>
					<?php endif; ?>
					</div>
				</div><!-- end of the #gk-content-wrap -->
				
				<?php if(get_option($gk_tpl->name . '_inner_inset_position', 'right') != 'none' && 
					gk_is_active_sidebar('inner_inset')) : ?>
					<?php do_action('gavernwp_before_inner_inset'); ?>
					<aside id="gk-inset">
						<?php gk_dynamic_sidebar('inner_inset'); ?>
					</aside>
					<?php do_action('gavernwp_after_inner_inset'); ?>
				<?php endif; ?>	
				
					
				</section><!-- end of the mainbody section -->
				<?php if(gk_is_active_sidebar('bottom1')) : ?>
				<div id="gk-bottom1" class="widget-area">
					<div class="gk-equal-columns">
						<?php gk_dynamic_sidebar('bottom1'); ?>
						
						<!--[if IE 8]>
						<div class="ie8clear"></div>
						<![endif]-->
					</div>
				</div>
				<?php endif; ?>	
				
				<?php if(gk_is_active_sidebar('bottom2')) : ?>
				<div id="gk-bottom2" class="gk-page">
					<div class="widget-area">
						<?php gk_dynamic_sidebar('bottom2'); ?>
						
						<!--[if IE 8]>
						<div class="ie8clear"></div>
						<![endif]-->
					</div>
				</div>
				<?php endif; ?>
	</div><!-- end of the #gk-mainbody-columns -->
	
	<?php 
	if(!is_home() && gk_is_active_sidebar('sidebar_right') && 
		( $args == null || ($args != null && $args['sidebar_left'] == true) ) || is_home()  && gk_is_active_sidebar('sidebar_right')) : ?>
	<?php do_action('gavernwp_before_inset'); ?>
	<aside id="gk-sidebar-right">
		<?php gk_dynamic_sidebar('sidebar_right'); ?>
	</aside>
	<?php do_action('gavernwp_after_inset'); ?>
	<?php endif; ?>
	
</div><!-- end of the .gk-page-wrap section -->	

<?php 
if(!is_home() && gk_is_active_sidebar('sidebar_left') && 
	( $args == null || ($args != null && $args['sidebar_left'] == true) ) || is_home() && gk_is_active_sidebar('sidebar_left')) : ?>
<?php do_action('gavernwp_before_column'); ?>
<aside id="gk-sidebar-left">
	<?php gk_dynamic_sidebar('sidebar_left'); ?>
</aside>
<?php do_action('gavernwp_after_column'); ?>
<?php endif; ?>

</div><!-- end of the .gk-bg section -->

<?php if(gk_is_active_sidebar('bottom3')) : ?>
<div id="gk-bottom3" class="gk-page">
	<div class="widget-area">
		<?php gk_dynamic_sidebar('bottom3'); ?>	
	</div>
</div>
<?php endif; ?>
<?php if(gk_is_active_sidebar('bottom4')) : ?>
<div id="gk-bottom4" class="gk-page">
	<div class="widget-area">
		<?php gk_dynamic_sidebar('bottom4'); ?>
	</div>
</div>
<?php endif; ?>
<?php if(gk_is_active_sidebar('bottom5')) : ?>
<div id="gk-bottom5" class="gk-page">
	<div class="widget-area">
		<?php gk_dynamic_sidebar('bottom5'); ?>
	</div>
</div>
<?php endif; ?>