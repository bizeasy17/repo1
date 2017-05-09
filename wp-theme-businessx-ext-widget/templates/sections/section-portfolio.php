<?php
/* ------------------------------------------------------------------------- *
 *  Portfolio Section Wrapper
/* ------------------------------------------------------------------------- */

	$portfolio_sec__bg_overlay	= get_theme_mod( 'portfolio_bg_overlay', false );
	$portfolio_sec__hide 		= get_theme_mod( 'portfolio_section_hide' ) == 0 ? true : false;
	$portfolio_sec__title 		= get_theme_mod( 'portfolio_section_title', esc_html__( 'Portfolio Heading', 'businessx-extensions' ) );
	$portfolio_sec__description = get_theme_mod( 'portfolio_section_description', esc_html__( 'This is a description for the Portfolio section. You can set it up in the Customizer where you can also change some options.', 'businessx-extensions' ) );
	$portfolio_sec__nr_posts 	= get_theme_mod( 'portfolio_section_nr_posts', 4 );
	$portfolio_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $portfolio_sec__hide ) : ?>
<?php do_action( 'businessx_portfolio_sec__before_wrapper' ); ?>
<section id="section-portfolio" class="grid-wrap sec-portfolio"<?php businessx_section_parallax( 'portfolio_bg_parallax', 'portfolio_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_portfolio_sec__inner_wrapper_top' ); ?>
	<?php if( $portfolio_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-2 clearfix">
    	<?php do_action( 'businessx_portfolio_sec__inner_container_top' ); ?>
    	<?php if( $portfolio_sec__title != '' || $portfolio_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $portfolio_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $portfolio_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $portfolio_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $portfolio_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <div id="sec-portfolio-wrap" class="js-masonry grid-masonry-wrap <?php businessx_anim_classes(); ?>" data-masonry-options='{ "columnWidth": ".sec-portfolio-grid-sizer", "gutter": ".sec-portfolio-gutter-sizer", "percentPosition": true, "itemSelector": ".grid-col" }'>
        	<div class="sec-portfolio-grid-sizer"></div>
            <div class="sec-portfolio-gutter-sizer"></div>

			<?php
			if( businessx_jetpack_check( 'custom-content-types' ) ) :
			$args = array(
				'order'           	=> 'asc',
				'orderby'         	=> 'date',
				'posts_per_page' 	=> absint( $portfolio_sec__nr_posts ),
				'post_type'			=> 'jetpack-portfolio'
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

				// Get portfolio item template
				get_template_part( 'partials/posts/content', 'portfolio-page' );

				endwhile; wp_reset_postdata(); else :
					if ( ! $portfolio_sec_helpers ) {
					?>
            	<p class="ta-center msg-info">
					<?php printf( __( 'Ready to publish your first project? <a href="%1$s">Get started here</a>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ), esc_url( admin_url( 'post-new.php?post_type=jetpack-portfolio' ) ) ); ?>
                </p>
            <?php
					}  // $portfolio_sec_helpers endif
				endif; // Query
				else : // If Jetpack or Portfolio module isn't enabled
					if ( ! $portfolio_sec_helpers ) {
			?>
            	<p class="ta-center msg-info"><?php _e( 'You need <b>Jetpack - Portfolio</b> module enabled to use this section. Projects will appear here once you activate the plugin. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ); ?></p>
            <?php
					}  // $portfolio_sec_helpers endif #2
				endif; // JetPack check
			?>

        </div>
        <script type='text/javascript'>
			jQuery( document ).ready( function( $ ) { var $sec_portwrap = $('#sec-portfolio-wrap').masonry(); $sec_portwrap.imagesLoaded( function() { $sec_portwrap.masonry(); }); });
        </script>
        <?php do_action( 'businessx_portfolio_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_portfolio_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_portfolio_sec__after_wrapper' );
	endif; // END Portofolio Section
?>
