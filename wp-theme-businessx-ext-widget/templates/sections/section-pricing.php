<?php
/* ------------------------------------------------------------------------- *
 *  Pricing Section Wrapper
/* ------------------------------------------------------------------------- */

	$pricing_sec__bg_overlay	= get_theme_mod( 'pricing_bg_overlay', false );
	$pricing_sec__hide 			= get_theme_mod( 'pricing_section_hide' ) == 0 ? true : false;
	$pricing_sec__title 		= get_theme_mod( 'pricing_section_title', esc_html__( 'Pricing Heading', 'businessx-extensions' ) );
	$pricing_sec__description 	= get_theme_mod( 'pricing_section_description', esc_html__( 'This is a description for the Pricing section. You can set it up in the Customizer where you can also add items for it.', 'businessx-extensions' ) );
	$pricing_sec_helpers		= get_theme_mod( 'disable_helpers', false );
	// Columns setup
	$pricing_sec__cols			= get_theme_mod( 'pricing_section_columns', apply_filters( 'businessx_pricing_columns_type', 'grid-2x3-col' ) );
	if( $pricing_sec__cols == 'grid-2x3-col' ) { $psc = ' pricing-3cols'; }
	elseif( $pricing_sec__cols == 'grid-2x-col' ) { $psc = ' pricing-2cols'; }
	elseif( $pricing_sec__cols == 'grid-1x-col' ) { $psc = ' pricing-4cols'; }
?>
<?php if( $pricing_sec__hide ) : ?>
<?php do_action( 'businessx_pricing_sec__before_wrapper' ); ?>
<section id="section-pricing" class="grid-wrap sec-pricing<?php echo $psc; ?>"<?php businessx_section_parallax( 'pricing_bg_parallax', 'pricing_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_pricing_sec__inner_wrapper_top' ); ?>
	<?php if( $pricing_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-2 clearfix">
    	<?php do_action( 'businessx_pricing_sec__inner_container_top' ); ?>
    	<?php if( $pricing_sec__title != '' || $pricing_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $pricing_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $pricing_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $pricing_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $pricing_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <div class="grid-items clearfix <?php businessx_anim_classes(); ?>">
			<?php
            // Display pricing
            if ( is_active_sidebar( 'section-pricing' ) && ! is_paged() ) {
				dynamic_sidebar( 'section-pricing' );
			} else {
				if ( ! $pricing_sec_helpers ) {
					echo '<div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > Pricing Section</b> and add items by clicking on <b>Add or edit package</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div>';
				}
			}
            ?>
        </div>
        <?php do_action( 'businessx_pricing_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_pricing_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_pricing_sec__after_wrapper' );
	endif; // END Pricing Section
?>
