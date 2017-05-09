<?php

/**
 *
 * The default template for displaying content
 *
 **/

global $gk_tpl; 

$params = get_post_custom();
$params_title = isset($params['gavern-post-params-title']) ? esc_attr( $params['gavern-post-params-title'][0] ) : 'Y';
$params_image = isset($params['gavern-post-params-image']) ? esc_attr( $params['gavern-post-params-image'][0] ) : 'Y';

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
		<?php get_template_part( 'layouts/content.post.featured' ); ?>
		
		<header>
			<?php if(get_the_title() != '' && $params_title == 'Y') : ?>
			<h<?php echo (is_singular()) ? '1' : '2'; ?>>
				<?php if(!is_singular()) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', GKTPLNAME ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<?php endif; ?>
					<?php the_title(); ?>
				<?php if(!is_singular()) : ?>
				</a>
				<?php endif; ?>
			</h<?php echo (is_singular()) ? '1' : '2'; ?>>
			<?php endif; ?>
		</header>

		<?php if ( (!is_single() && get_option($gk_tpl->name . '_readmore_on_frontpage', 'Y') == 'Y') || is_search() || is_archive() || is_tag() ) : ?>
		<section class="summary">
			<?php if (is_front_page()) : ?>
				<a href="<?php echo get_permalink(get_the_ID()); ?>" class="readon btn"><?php _e('Read more', GKTPLNAME); ?></a>
			<?php endif; ?>
			<?php the_excerpt(); ?>
		</section>
		<?php else : ?>
		<section class="content">
			<?php the_content( __( 'Read more', GKTPLNAME ) ); ?>
			
			<?php gk_post_fields(); ?>
			<?php gk_post_links(); ?>
		</section>
		<?php endif; ?>
		
		<?php if((!is_page_template('template.fullwidth.php') && ('post' == get_post_type() || 'page' == get_post_type())) && get_the_title() != '') : ?>
			<?php if(!(get_post_type() == 'page' && get_option($gk_tpl->name . '_template_show_details_on_pages', 'Y') == 'N')) : ?>
				<?php if(!('post' == get_post_type() && get_option($gk_tpl->name . '_post_aside_state', 'Y') == 'N')) : ?>
					<?php gk_post_meta(); ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
		
		
		<?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>