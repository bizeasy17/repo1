<?php

/**
 *
 * The template fragment to show post featured image
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $gk_tpl; 

$params = get_post_custom();
$params_image = isset($params['gavern-post-params-image']) ? esc_attr( $params['gavern-post-params-image'][0] ) : 'Y';

?>

<?php 
	// variable for the social API HTML output
	$social_api_output = gk_social_api(get_the_title(), get_the_ID()); 
?>

<div class="gk-article-wrap">
<?php if(is_singular()) : ?>
	<?php if($social_api_output != '' ): ?>
		<aside id="gk-social-aside">
			<?php echo $social_api_output; ?>
		</aside>
	<?php endif; ?>
<?php endif; ?>
	<div class="gk-article-body">
	
	<?php if((is_single() || is_page()) && $params_image == 'Y') : ?>
		<?php if(has_post_thumbnail()) : ?>
		<figure class="featured-image">
			<?php if(is_sticky()) : ?>
			<sup>
				<?php _e( 'Featured', GKTPLNAME ); ?>
			</sup>
			<?php endif; ?>
			
			<?php the_post_thumbnail(); ?>
			
			<?php if(is_single() || is_page()) : ?>
				<?php echo gk_post_thumbnail_caption(); ?>
			<?php endif; ?>
		</figure>
		<?php 
			// if there is a Featured Video
			elseif(get_post_meta(get_the_ID(), "_gavern-featured-video", true) != '') : 
		?>
		<div class="gk-video-wrap">
		<?php echo get_post_meta(get_the_ID(), "_gavern-featured-video", true); ?>
		</div>
		<?php endif; ?>
		
	<?php elseif(!(is_single() || is_page())) : ?>
		<?php if($params_image == 'Y') : ?>
			<?php if(has_post_thumbnail()) : ?>
			<figure class="featured-image">
				<?php if(is_sticky()) : ?>
				<sup>
					<?php _e( 'Featured', GKTPLNAME ); ?>
				</sup>
				<?php endif; ?>
				
				<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail(); ?>
				</a>
				
				<?php if(is_single() || is_page()) : ?>
					<?php echo gk_post_thumbnail_caption(); ?>
				<?php endif; ?>
			</figure>
			<?php 
				// if there is a Featured Video
				elseif(get_post_meta(get_the_ID(), "_gavern-featured-video", true) != '') : 
			?>
			<div class="gk-video-wrap">
			<?php echo get_post_meta(get_the_ID(), "_gavern-featured-video", true); ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>