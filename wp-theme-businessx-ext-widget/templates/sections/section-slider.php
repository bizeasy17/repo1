<?php
/* ------------------------------------------------------------------------- *
 *  Slider Section Wrapper
/* ------------------------------------------------------------------------- */

	$slider_sec__hide 		= get_theme_mod( 'slider_section_hide' ) == 0 ? true : false;
	$slider_sec__autoplay 	= get_theme_mod( 'slider_autoplay_enable' ) == 0 ? 'false' : 'true';
	$slider_sec__delay		= get_theme_mod( 'slider_autoplay_delay', '5000' );
	$slider_sec__arrows		= get_theme_mod( 'slider_arrows_disable', false );
?>
<?php
	if( $slider_sec__hide ) :
	do_action( 'businessx_slider_sec__before_wrapper' );
?>

<section id="section-slider" class="sec-slider">

	<?php if( ! $slider_sec__arrows ) { ?>
	<a href="#" class="ss-prev"><?php businessx_icon( 'angle-left' ); ?></a>
	<a href="#" class="ss-next"><?php businessx_icon( 'angle-right' ); ?></a>
	<?php } ?>

	<div class="sec-slider-wrap owl-carousel" id="ac-slider-section">
    	<?php
		// Display slides
		if ( is_active_sidebar( 'section-slider' ) && ! is_paged() ) { dynamic_sidebar( 'section-slider' ); } else {
		?>

        <div class="sec-slider-slide">
        	<div class="sec-slider-overlay" style="background: linear-gradient(to bottom, rgba(5,20,30,0.9) 0%, rgba(5,20,30,0.1) 100%);">
            	<div class="sec-hs-elements ta-center">
                    <h2 class="hs-primary-large animated"><?php _e( 'You need to add some slides to make things work.', 'businessx-extensions' ); ?></h2>
                    <p class="sec-hs-description fs-largest fw-regular"><?php _e( 'Open Customizer, click on Sections > Slider Section > Add or Edit slides', 'businessx-extensions' ); ?></p>
                </div>
            </div>
        </div>

        <?php } ?>

    </div>
    <script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {

			/* Create slider */
			function bx_createSlider() {
				var ap;
				if ( 'undefined' !== typeof wp && wp.customize  ) { ap = false; } else { ap = <?php echo esc_html( $slider_sec__autoplay ) ?>; }

				$('#ac-slider-section').owlCarousel({
					responsiveClass:true,
					items: 1,
					autoplay: ap,
					autoplayTimeout: <?php echo absint( $slider_sec__delay ); ?>,
					loop: false,
					rewind: true,
					animateOut: 'fadeOut',
					nav: false,
				});
			}

			<?php if( ! $slider_sec__arrows ) { ?>
			/* Navigation Arrows */
			$('.sec-slider .ss-next').click(function(event) { event.preventDefault(); $('#ac-slider-section').trigger('next.owl.carousel', [200]); });
			$('.sec-slider .ss-prev').click(function(event) { event.preventDefault(); $('#ac-slider-section').trigger('prev.owl.carousel', [200]); });
			<?php } ?>

			/* Initialize */
			bx_createSlider();

			<?php if( is_customize_preview() ) : ?>

			/* Destroy it */
			function bx_destroySlider() {
				var selector = '#ac-slider-section';
				$(selector).removeClass('owl-loaded owl-drag');
				$(selector + ' .owl-stage').children().unwrap();
				$(selector + ' .owl-stage-outer').children().unwrap();
				$(selector).find('.owl-stage').remove();
				$(selector).find('.owl-nav').remove();
				$(selector).find('.owl-dots').remove();
				$(selector + ' .sec-slider-slide').each(function(index, element) {
                    $(element).removeClass('owl-item').removeClass('active').removeAttr('style'); });
				$(selector).removeData();
			}

			/* Height resizer */
			function bx_sliderHeightResizer() {
				var slideClass	= $( ".sec-slider-slide" ),
					main_window = $( window ),
					new_height	= ( $('body').hasClass( 'menu-ff' ) || $('body').hasClass( 'menu-nn' ) ) ? main_window.height() - $('.main-header').outerHeight() : main_window.height();
				slideClass.css( 'height', new_height );
			}

			/* Customizer Selective Refresh */
			if ( 'undefined' !== typeof wp && wp.customize  ) {
				wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {
					if ( 'section-slider' === sidebarPartial.sidebarId ) {
						bx_destroySlider();
						bx_createSlider();
						bx_sliderHeightResizer();
					}
				});
			}

			<?php endif; ?>

		});
	</script>
</section>

<?php
	do_action( 'businessx_slider_sec__after_wrapper' );
	endif; // END Slider Section
?>
