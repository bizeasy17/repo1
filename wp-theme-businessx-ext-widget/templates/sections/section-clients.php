<?php
/* ------------------------------------------------------------------------- *
 *  Clients Section Wrapper
/* ------------------------------------------------------------------------- */

	$clients_sec__bg_overlay	= get_theme_mod( 'clients_bg_overlay', false );
	$clients_sec__hide 			= get_theme_mod( 'clients_section_hide' ) == 0 ? true : false;
	$clients_sec__title 		= get_theme_mod( 'clients_section_title', esc_html__( 'Clients Heading', 'businessx-extensions' ) );
	$clients_sec__description 	= get_theme_mod( 'clients_section_description', esc_html__( 'This is a description for the Clients section. You can set it up in the Customizer where you can also add items for it.', 'businessx-extensions' ) );
	$clients_sec__autoscroll 	= get_theme_mod( 'clients_section_autoscroll' ) == 0 ? 'false' : 'true';
	$clients_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $clients_sec__hide ) : ?>
<?php do_action( 'businessx_clients_sec__before_wrapper' ); ?>
<section id="section-clients" class="grid-wrap sec-clients"<?php businessx_section_parallax( 'clients_bg_parallax', 'clients_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_clients_sec__inner_wrapper_top' ); ?>
	<?php if( $clients_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_clients_sec__inner_container_top' ); ?>
    	<?php if( $clients_sec__title != '' || $clients_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $clients_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $clients_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $clients_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $clients_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>

        <?php
			if( ! is_active_sidebar( 'section-clients' ) ) {
				if ( ! $clients_sec_helpers ) {
					echo '<div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > Clients Section</b> and add items by clicking on <b>Add or edit clients</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div>';
				}
			}
		?>

        <div class="grid-col grid-4x-col">
        	<div class="owl-carousel bx-clients-carousel <?php businessx_anim_classes(); ?>">
				<?php
                // Display clients
                if ( is_active_sidebar( 'section-clients' ) && ! is_paged() ) { dynamic_sidebar( 'section-clients' ); };
                ?>
            </div>

            <nav class="sec-clients-nav clearfix <?php businessx_anim_classes(); ?>">
				<div class="sec-clients-nav-btns">
					<a href="#" class="sec-clients-nav-btn-prev"><?php businessx_icon( 'angle-left' ) ?></a>
                    <a href="#" class="sec-clients-nav-btn-next"><?php businessx_icon( 'angle-right' ) ?></a>
                </div>
        	</nav>
        </div>
        <?php do_action( 'businessx_clients_sec__inner_container_bottom' ); ?>
    </div>

    <script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {

			/* Create the carousel */
			function bx_createClients() {
				var ap;
				if ( 'undefined' !== typeof wp && wp.customize  ) { ap = false; } else { ap = <?php echo esc_html( $clients_sec__autoscroll ) ?>; }

				$( '.bx-clients-carousel' ).owlCarousel({
					responsiveClass: true,
					nav: false,
					dots: false,
					loop: false,
					autoplay: ap,
					autoplayTimeout: 3000,
					rewind: true,
					responsive: {
						0:{
							items: 1,
							nav: false,
							dots: false,
						},
						600:{
							items: 2,
							nav: false,
							dots: false,
						},
						1000:{
							items: 4,
							nav: false,
							dots: false,
						},
					},
				});
			};

			/* Navigation Arrows */
			$('.sec-clients .sec-clients-nav-btn-next').click(function(event) { event.preventDefault();  $( '.bx-clients-carousel' ).trigger('next.owl.carousel', [200]); });
			$('.sec-clients .sec-clients-nav-btn-prev').click(function(event) { event.preventDefault();  $( '.bx-clients-carousel' ).trigger('prev.owl.carousel', [200]); });

			/* Initialize */
			bx_createClients();

			<?php if( is_customize_preview() ) : ?>

			/* Destroy it */
			function bx_destroyClients() {
				var selector = '.bx-clients-carousel'
				$(selector).removeClass('owl-loaded owl-drag');
				$(selector + ' .owl-stage').children().unwrap();
				$(selector + ' .owl-stage-outer').children().unwrap();
				$(selector).find('.owl-stage').remove();
				$(selector).find('.owl-nav').remove();
				$(selector).find('.owl-dots').remove();
				$(selector + ' .sec-client-logo-wrap').each(function(index, element) {
					$(element).removeClass('owl-item').removeClass('active').removeAttr('style'); });
				$(selector).removeData();
			}

			/* Customizer Selective Refresh */
			if ( 'undefined' !== typeof wp && wp.customize  ) {
				wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {
					if ( 'section-clients' === sidebarPartial.sidebarId ) {
						bx_destroyClients();
						bx_createClients();
					}
				});
			}

			<?php endif; ?>

		});
	</script>
    <?php do_action( 'businessx_clients_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_clients_sec__after_wrapper' );
	endif; // END Clients Section
?>
