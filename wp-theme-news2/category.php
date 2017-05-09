<?php

/**
 *
 * Category page
 *
 **/

global $gk_tpl, $post;

gk_load('header');
gk_load('before');

// geting category page layout settings
$leading_rows = get_option($gk_tpl->name . '_leading_row', '1');
$leading_cols = get_option($gk_tpl->name . '_leading_column', '3');

$primary_rows = get_option($gk_tpl->name . '_primary_row', '1');
$primary_cols = get_option($gk_tpl->name . '_primary_column', '2');

$secondary_rows = get_option($gk_tpl->name . '_secondary_row', '1');
$secondary_cols = get_option($gk_tpl->name . '_secondary_column', '3');

// get the page number
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

// setting offsets
$leading_offset = ($paged-1) * (($leading_rows * $leading_cols) + ($primary_rows * $primary_cols) + ($secondary_rows * $secondary_cols));
$primary_offset = $leading_offset + ($leading_cols * $leading_rows);
$secondary_offset = $primary_offset + ($primary_cols * $primary_rows);

$per_page = ($leading_rows * $leading_cols) + ($primary_rows * $primary_cols) + ($secondary_rows * $secondary_cols)

?>

<section id="gk-mainbody" class="category-page">
	<?php if ( have_posts() ) : ?>
		<div id="gk-category-containter">
		<?php do_action('gavernwp_before_loop'); ?>
		<?php if (get_option($gk_tpl->name . '_category_grid', 'Y') == 'Y') : ?>

		<div id="gk-articles-leading">
			<div class="gk-articles-row gk-cols-<?php echo $leading_cols ?>">
			<?php query_posts('cat='. $cat .'&offset='.$leading_offset.'&posts_per_page='. ($leading_cols * $leading_rows) .'&paged='.$paged); ?>
			<?php $iter = 1; ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content-archive', get_post_format() ); ?>
				
				<?php if(
					$iter >= $leading_cols && 
					$iter < $leading_cols * $leading_rows &&
					$iter % $leading_cols == 0
				) : ?> 
					</div><div class="gk-articles-row gk-cols-<?php echo $leading_cols ?>">
				<?php endif; ?>
				<?php $iter++; ?>
			<?php endwhile; ?>
			</div>
		</div>
		
		<?php $wp_query->rewind_posts(); ?>
		
		<?php query_posts('cat='. $cat .'&offset='.$primary_offset.'&posts_per_page='. ($primary_cols * $primary_rows) .'&paged='.$paged); ?>
		
		<?php if(have_posts()) : ?>
		<div id="gk-articles-primary">
			<div class="gk-articles-row gk-cols-<?php echo $primary_cols ?>">
				<?php $iter = 1; ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content-archive', get_post_format() ); ?>
					
					<?php if(
						$iter >= $primary_cols && 
						$iter < $primary_cols * $primary_rows && 
						$iter % $primary_cols == 0
					) : ?> 
						</div><div class="gk-articles-row gk-cols-<?php echo $primary_cols ?>">
					<?php endif; ?>
					<?php $iter++; ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php $wp_query->rewind_posts(); ?>
		
		<?php query_posts('cat='. $cat .'&offset='.$secondary_offset.'&posts_per_page='. ($secondary_cols * $secondary_rows) .'&paged='.$paged); ?>
		
		<?php if(have_posts()) : ?>
		<div id="gk-articles-secondary">
			<div class="gk-articles-row gk-cols-<?php echo $secondary_cols ?>">
				<?php $iter = 1; ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content-archive', get_post_format() ); ?>
					
					<?php if(
						$iter >= $secondary_cols && 
						$iter < $secondary_cols * $secondary_rows &&
						$iter % $secondary_cols == 0
					) : ?> 
						</div><div class="gk-articles-row gk-cols-<?php echo $secondary_cols ?>">
					<?php endif; ?>
					<?php $iter++; ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php endif; ?>
		</div>
		<?php wp_reset_query(); ?>
		
		<?php query_posts('cat='. $cat .'&posts_per_page='. $per_page .'&paged='.$paged); ?>
		<?php gk_content_nav(); ?>
		<?php wp_reset_query(); ?>
		
		<?php do_action('gavernwp_after_loop'); ?>
		
		<?php else : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content-archive', get_post_format() ); ?>
			<?php endwhile; ?>
		
			<?php gk_content_nav(); ?>
			
			<?php do_action('gavernwp_after_loop'); ?>
		<?php endif; ?>
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