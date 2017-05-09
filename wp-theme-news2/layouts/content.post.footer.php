<?php

/**
 *
 * The template fragment to show post footer
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $gk_tpl, $post; 



$tag_list = get_the_tag_list( '', __( ' ', GKTPLNAME ) );

$params = get_post_custom();
$params_aside = isset($params['gavern-post-params-aside']) ? $params['gavern-post-params-aside'][0] : false;
$params_image = isset($params['gavern-post-params-image']) ? esc_attr( $params['gavern-post-params-image'][0] ) : 'Y';

$param_aside = true;
$param_tags = true;

if($params_aside) {
  $params_aside = unserialize(unserialize($params_aside));
  $param_aside = $params_aside['aside'] == 'Y';
  $param_tags = $params_aside['tags'] == 'Y';
}
?>

	<?php if(is_singular()) : ?>
		
		<?php do_action('gavernwp_after_post_content'); ?>
		
		<?php if(get_post_meta(get_the_ID(), "_gavern-featured-video", true) != '' && has_post_thumbnail() && $params_image ) : ?>
			<?php echo '<h3 class="video-block">' . __('Media', GKTPLNAME) . '</h3>'; ?>
			<div class="gk-video-wrap">
			<?php echo get_post_meta(get_the_ID(), "_gavern-featured-video", true); ?>
			</div>
		<?php endif; ?>
		
		<?php gk_post_fields(); ?>
	
		<?php if($tag_list != '' && $param_tags): ?>
		<dl class="tags">
			<dt><?php _e('Tagged under', GKTPLNAME); ?></dt>
			<dd><?php echo $tag_list; ?></dd>
		</dl>
		<?php endif; ?>		
		
		<?php if (is_single()) : ?>
			<?php if(get_option($gk_tpl->name . '_show_related', 'Y') == 'Y') : ?>
			<?php 
			$tags_ids = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
			$exclude = array($post->ID);
			$args = array(
					'tag__in' => $tags_ids,
					'post__not_in' => $exclude,
					'posts_per_page' => 4
					); 
			$related = new WP_Query($args);		
			?>
			
			<?php if ( $related->have_posts() ) : ?>
				<div class="gk-related-posts">
				<h3><?php _e('Related Posts', GKTPLNAME); ?></h3>
				<?php while ( $related->have_posts() ) : $related->the_post(); ?>
					<div>
						<?php if(has_post_thumbnail()) : ?>
						<figure class="featured-image">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('medium'); ?>
							</a>
						</figure>
						<?php endif; ?>
						<header>
							<h3>
								<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', GKTPLNAME ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>
							</h3>
						</header>
					</div>
				<?php endwhile; ?>
				</div>
			<?php else : ?>
				<p><?php _e( 'Apologies, but no related posts were found.', GKTPLNAME ); ?></p>
			<?php endif; ?>
			<?php wp_reset_query(); ?>
			<?php endif; ?>
			
			<?php if(get_option($gk_tpl->name . '_show_author_posts', 'Y') == 'Y') : ?>
			<?php 
			$args_author = array(
					'author' => get_the_author_meta( 'ID' ),
					'posts_per_page' => 5
					); 
			$author_posts = new WP_Query($args_author);		
			?>
			
			<?php if ( $author_posts->have_posts() ) : ?>
				<div class="gk-author-posts">
				<h3> <?php printf( __( 'Latest from %s ', GKTPLNAME ), get_the_author_meta('display_name', get_the_author_meta( 'ID' )) ); ?></h3>
				<ul>
				<?php while ( $author_posts->have_posts() ) : $author_posts->the_post(); ?>
					<li>	
						<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', GKTPLNAME ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<?php the_title(); ?>
						</a>
					</li>
				<?php endwhile; ?>
				</ul>
				</div>
			<?php else : ?>
				<p><?php _e( 'Apologies, but no posts were found.', GKTPLNAME ); ?></p>
			<?php endif; ?>
			<?php wp_reset_query(); ?>
			<?php endif; ?>
		<?php endif; ?>
			
		<?php if(gk_author(false, true)): ?>
		<footer>
			<?php gk_author(); ?>
		</footer>
		<?php endif; ?>
	<?php endif; ?>
	</div>
</div>