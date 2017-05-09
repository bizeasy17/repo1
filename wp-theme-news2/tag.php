<?php

/**
 *
 * Tag page
 *
 **/

global $gk_tpl;

gk_load('header');
gk_load('before');

$other_cols = get_option($gk_tpl->name . '_other_cols', '2');

?>

<section id="gk-mainbody" class="tag-page">
	<div id="gk-article-containter">
	<?php if ( have_posts() ) : ?>		
		
		<?php do_action('gavernwp_before_loop'); ?>
		<div id="gk-articles-leading">
		
			<div class="gk-articles-row gk-cols-<?php echo $other_cols ?>">
			<?php $iter = 1; ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content-archive', get_post_format() ); ?>
				<?php if($iter > 0 && $iter >= $other_cols && $iter % $other_cols == 0) : ?> 
					</div><div class="gk-articles-row gk-cols-<?php echo $other_cols ?>">
				<?php endif; ?>
			<?php $iter++; ?>	
			<?php endwhile; ?>
			</div>
		</div>
		</div>
		<?php gk_content_nav(); ?>
		<?php do_action('gavernwp_after_loop'); ?>
		
	<?php else : ?>
		<h1 class="page-title">
			<?php _e( 'Nothing Found', GKTPLNAME ); ?>
		</h1>
	
		<section class="intro">
			<?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', GKTPLNAME ); ?>
		</section>
		
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF