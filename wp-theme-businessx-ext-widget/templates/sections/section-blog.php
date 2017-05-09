<?php
/* ------------------------------------------------------------------------- *
 *  Blog Section Wrapper
/* ------------------------------------------------------------------------- */

	$blog_sec__bg_overlay	= get_theme_mod( 'blog_bg_overlay', false );
	$blog_sec__hide 		= get_theme_mod( 'blog_section_hide' ) == 0 ? true : false;
	$blog_sec__title 		= get_theme_mod( 'blog_section_title', esc_html__( 'Blog Heading', 'businessx-extensions' ) );
	$blog_sec__description 	= get_theme_mod( 'blog_section_description', esc_html__( 'This is a description for the Blog section. You can set it up in the Customizer > Sections > Blog Section.', 'businessx-extensions' ) );
	$blog_sec__nr_posts 	= get_theme_mod( 'blog_section_nr_posts', 4 );
?>
<?php if( $blog_sec__hide ) : ?>
<?php do_action( 'businessx_blog_sec__before_wrapper' ); ?>
<section id="section-blog" class="grid-wrap sec-blog"<?php businessx_section_parallax( 'blog_bg_parallax', 'blog_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_blog_sec__inner_wrapper_top' ); ?>
	<?php if( $blog_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_blog_sec__inner_container_top' ); ?>
    	<?php if( $blog_sec__title != '' || $blog_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $blog_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $blog_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $blog_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $blog_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <div id="sec-blog-wrap" class="js-masonry grid-masonry-wrap <?php businessx_anim_classes(); ?>" data-masonry-options='{ "columnWidth": ".sec-blog-grid-sizer", "gutter": ".sec-blog-gutter-sizer", "percentPosition": true, "itemSelector": ".grid-col" }'>
        	<div class="sec-blog-grid-sizer"></div>
            <div class="sec-blog-gutter-sizer"></div>

			<?php
			$args = array(
				'order'           	=> 'desc',
				'orderby'         	=> 'date',
				'posts_per_page' 	=> intval( $blog_sec__nr_posts ),
				'post__not_in' 		=> get_option( 'sticky_posts' ),
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
				$post_id = get_the_ID();
            ?>

            <div class="grid-col grid-2x-col sec-blog-post">
            	<?php do_action( 'businessx_blog_sec__inner_post_top' ); ?>
            	<?php if( has_post_thumbnail( $post_id ) ) { ?>
            	<figure class="sec-blog-post-thumbnail">
                	<a href="<?php echo esc_url( get_permalink() ); ?>" rel="nofollow"><?php echo get_the_post_thumbnail( $post_id, apply_filters( 'businessx_blog_thumbnail_size', 'businessx-tmb-blog-wide' ) ); ?></a>
                    <?php do_action( 'businessx_blog_sec__inner_thumbnail' ); ?>
                </figure><?php } ?>
                 <header class="sec-blog-post-title">
                	<h3 class="hs-secondary-large fw-light"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( the_title_attribute() ); ?>"><?php the_title(); ?></a></h3>
				</header>
                <div class="sec-blog-post-excerpt">
                	<?php the_excerpt(); ?>
                </div>
                <footer class="sec-blog-post-meta">
                	<ul class="sec-blog-post-meta-list">
                    	<?php businessx_post_meta(); ?>
                    </ul>
                </footer>
                <?php do_action( 'businessx_blog_sec__inner_post_bottom' ); ?>
            </div><!-- .sec-blog-post -->

            <?php
				endwhile;
				wp_reset_postdata(); else : ?>
            	<p class="ta-center"><?php _e( 'There are no posts to display. Maybe add some!', 'businessx-extensions' ); ?></p>
            <?php
				endif; // Query
			?>

        </div>
        <script type='text/javascript'>
			jQuery( document ).ready( function( $ ) { var $sec_blogwrap = $('#sec-blog-wrap').masonry(); $sec_blogwrap.imagesLoaded( function() { $sec_blogwrap.masonry(); }); });
		</script>
        <?php do_action( 'businessx_blog_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_blog_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_blog_sec__after_wrapper' );
	endif; // END Blog Section
?>
