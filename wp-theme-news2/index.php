<?php

/**
 * GavernWP main template file
 *
 * This file is loaded only when user openes the site using the template
 *
 * @package WordPress
 * @subpackage GavernWP
 * @since GavernWP 1.0
 **/

global $gk_tpl;

gk_load('header');
gk_load('before');

// get the page number
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

if($paged == 1) {
	$per_page = get_option('posts_per_page') -1;
}
else {
	$per_page = get_option('posts_per_page');
}
query_posts('posts_per_page=' . $per_page . '&paged=' . $paged );

$other_cols = get_option($gk_tpl->name . '_other_cols', '2');

?>		
		
	<?php if(get_option($gk_tpl->name . '_template_homepage_mainbody', 'N') == 'N') : ?>
		<?php do_action('gavernwp_before_mainbody'); ?>
		<section id="gk-mainbody">
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
			</section>
		<?php else : ?>
			<section id="gk-mainbody">
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', GKTPLNAME ); ?></h1>
					</header>
		
					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', GKTPLNAME ); ?></p>
						<?php get_search_form(); ?>
					</div>
				</article>
			</section>
		<?php endif; ?>
		
		<?php do_action('gavernwp_after_mainbody'); ?>
	<?php else: ?>
		<?php if(gk_is_active_sidebar('mainbody')) : ?>
		<section id="gk-mainbody">
			<?php gk_dynamic_sidebar('mainbody'); ?>
		</section>
		<?php endif; ?>
	<?php endif; ?>
<?php

gk_load('after');
gk_load('footer');

/* EOF */