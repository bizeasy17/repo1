<?php

/**
 *
 * Search page
 *
 **/

global $gk_tpl;

gk_load('header');
gk_load('before');

$other_cols = get_option($gk_tpl->name . '_other_cols', '2');

?>

<section id="gk-mainbody" class="search-page">
	<?php if ( have_posts() ) : ?>
		<h1 class="page-title">
			<?php printf( __( 'Search Results for: %s', GKTPLNAME ), '<em>' . get_search_query() . '</em>' ); ?>
		</h1>
	
		<?php 
			get_search_form(); 
			$founded = false;
		?>
		
		<div id="gk-article-containter">
		
		<?php do_action('gavernwp_before_loop'); ?>
		<div id="gk-articles-leading">
		
			<div class="gk-articles-row gk-cols-<?php echo $other_cols ?>">
			<?php $iter = 1; ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content-archive', get_post_format() ); ?>
				<?php $founded = true; ?>
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

		<?php if(!$founded) : ?>
		<h2>
			<?php _e( 'Nothing Found', GKTPLNAME ); ?>
		</h2>
		
		<section class="intro">
			<?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', GKTPLNAME ); ?>
		</section>
		<?php endif; ?>
	
	<?php else : ?>				
		<h1 class="page-title">
			<?php _e( 'Nothing Found', GKTPLNAME ); ?>
		</h1>
		
		<?php get_search_form(); ?>
		
		<section class="intro">
			<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', GKTPLNAME ); ?></p>
		</section>
	<?php endif; ?>
	
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF