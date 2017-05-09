<?php
/* ------------------------------------------------------------------------- *
 *  Frequently Asked Questions Section Wrapper
/* ------------------------------------------------------------------------- */

	$faq_sec__bg_overlay	= get_theme_mod( 'faq_bg_overlay', false );
	$faq_sec__hide 			= get_theme_mod( 'faq_section_hide' ) == 0 ? true : false;
	$faq_sec__title 		= get_theme_mod( 'faq_section_title', esc_html__( 'Frequently Asked Questions', 'businessx-extensions' ) );
	$faq_sec__description 	= get_theme_mod( 'faq_section_description', esc_html__( 'This is a description for the FAQ section. You can set it up in the Customizer where you can also add items for it.', 'businessx-extensions' ) );
	$faq_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $faq_sec__hide ) : ?>
<?php do_action( 'businessx_faq_sec__before_wrapper' ); ?>
<section id="section-faq" class="grid-wrap sec-faq"<?php businessx_section_parallax( 'faq_bg_parallax', 'faq_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_faq_sec__inner_wrapper_top' ); ?>
	<?php if( $faq_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_faq_sec__inner_container_top' ); ?>
    	<?php if( $faq_sec__title != '' || $faq_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $faq_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $faq_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $faq_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $faq_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>

        <?php
			if( ! is_active_sidebar( 'section-faq' ) ) {
				if ( ! $faq_sec_helpers ) {
					echo '<div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > FAQ Section</b> and add items by clicking on <b>Add or edit questions</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div>';
				}
			}
		?>

        <div id="sec-faq-items" class="js-masonry grid-masonry-wrap grid-items clearfix <?php businessx_anim_classes(); ?>"
        	data-masonry-options='{ "columnWidth": ".sec-faq-grid-sizer", "gutter": ".sec-faq-gutter-sizer", "percentPosition": true, "itemSelector": ".grid-col" }'>
            <div class="sec-faq-grid-sizer"></div>
            <div class="sec-faq-gutter-sizer"></div>
			<?php
            // Display questions
            if ( is_active_sidebar( 'section-faq' ) && ! is_paged() ) { dynamic_sidebar( 'section-faq' ); };
            ?>
        </div>
        <?php do_action( 'businessx_faq_sec__inner_container_bottom' ); ?>
    </div>
    <?php if( is_customize_preview() ) : ?>
    <script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			$('#sec-faq-items').masonry();
			if ( 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh  ) {
				wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {
					if ( 'section-faq' === sidebarPartial.sidebarId ) {
						$('#sec-faq-items').masonry( 'reloadItems' );
						$('#sec-faq-items').masonry( 'layout' );
					}
				} );
			}
		});
	</script>
    <?php endif; ?>
    <?php do_action( 'businessx_faq_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_faq_sec__after_wrapper' );
	endif; // END FAQ Section
?>
