<?php
/*
Template Name: Latest Posts
*/

global $gk_tpl;

gk_load('header');
gk_load('before');

global $more;
$more = 0;

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

<?php if ( have_posts() ) : ?>
	<section id="gk-mainbody">
		<div id="gk-article-containter">		
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
		
		<?php wp_reset_query(); ?>
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

<?php

gk_load('after');
gk_load('footer');

// EOF