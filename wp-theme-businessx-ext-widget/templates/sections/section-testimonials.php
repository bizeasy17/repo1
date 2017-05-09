<?php
/* ------------------------------------------------------------------------- *
 *  Testimonials Section Wrapper
/* ------------------------------------------------------------------------- */

	$testimonials_sec__bg_overlay	= get_theme_mod( 'testimonials_bg_overlay', false );
	$testimonials_sec__hide			= get_theme_mod( 'testimonials_section_hide' ) == 0 ? true : false;
	$testimonials_sec__title 		= get_theme_mod( 'testimonials_section_title', esc_html__( 'Testimonials', 'businessx-extensions' ) );
	$testimonials_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $testimonials_sec__hide ) : ?>
<?php do_action( 'businessx_testimonials_sec__before_wrapper' ); ?>
<section id="section-testimonials" class="grid-wrap sec-testimonials"<?php businessx_section_parallax( 'testimonials_bg_parallax', 'testimonials_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_testimonials_sec__inner_wrapper_top' ); ?>
	<?php if( $testimonials_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_testimonials_sec__inner_container_top' ); ?>
    	<?php if( $testimonials_sec__title != '' ) : ?>
    	<header class="section-header <?php businessx_anim_classes(); ?>">
        	<?php if( $testimonials_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large"><?php echo esc_html( $testimonials_sec__title ); ?></h2>
            <?php endif; ?>
        </header>
        <?php endif; ?>
	</div>

	<?php
		if( ! is_active_sidebar( 'section-testimonials' ) ) {
			if ( ! $testimonials_sec_helpers ) {
				echo '<div class="grid-container grid-1 clearfix"><div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > Testimonials Section</b> and add items by clicking on <b>Add or edit testimonials</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div></div>';
			}
		}
	?>

    <div class="owl-carousel bx-testimonials-carousel <?php businessx_anim_classes(); ?>">
        <?php
        // Display testimonials
        if ( is_active_sidebar( 'section-testimonials' ) && ! is_paged() ) { dynamic_sidebar( 'section-testimonials' ); };
        ?>
    </div>

    <nav class="sec-testimonials-nav clearfix <?php businessx_anim_classes(); ?>">
        <div class="sec-testimonials-nav-btns">
            <a href="#" class="sec-testimonials-nav-btn-prev"><?php businessx_icon( 'angle-left' ) ?></a>
            <a href="#" class="sec-testimonials-nav-btn-next"><?php businessx_icon( 'angle-right' ) ?></a>
        </div>
    </nav>

	<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {

			/* Create Testimonials */
			function bx_createTestimonials() {
				$( '.bx-testimonials-carousel' ).owlCarousel({
					responsiveClass: true,
					center: true,
					items: 2,
					nav: false,
					dots: false,
					loop: false,
					margin: 50,
					responsive: {
						200: {
							items: 1
						},
						765: {
							items: 2
						},
					},
				});
			};

			/* Navigation Arrows */
			$('.sec-testimonials .sec-testimonials-nav-btn-next').click(function(event) { event.preventDefault();  $( '.bx-testimonials-carousel' ).trigger('next.owl.carousel', [200]); });
			$('.sec-testimonials .sec-testimonials-nav-btn-prev').click(function(event) { event.preventDefault();  $( '.bx-testimonials-carousel' ).trigger('prev.owl.carousel', [200]); });

			/* Initialize */
			bx_createTestimonials();

			<?php if( is_customize_preview() ) : ?>

			/* Destroy it */
			function bx_destroyTestimonials() {
				var selector = '.bx-testimonials-carousel'
				$(selector).removeClass('owl-loaded owl-drag');
				$(selector + ' .owl-stage').children().unwrap();
				$(selector + ' .owl-stage-outer').children().unwrap();
				$(selector).find('.owl-stage').remove();
				$(selector).find('.owl-nav').remove();
				$(selector).find('.owl-dots').remove();
				$(selector + ' .sec-testimonial-wrap').each(function(index, element) {
                    $(element).removeClass('owl-item').removeClass('active').removeClass('center').removeClass('cloned').removeAttr('style'); });
				$(selector).removeData();
			}

			/* Customizer Selective Refresh */
			if ( 'undefined' !== typeof wp && wp.customize  ) {
				wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {
					if ( 'section-testimonials' === sidebarPartial.sidebarId ) {
						bx_destroyTestimonials();
						bx_createTestimonials();
					}
				});
			}

			<?php endif; ?>

		});
	</script>

    <?php do_action( 'businessx_testimonials_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_testimonials_sec__after_wrapper' );
	endif; // END Testimonials Section
?>
