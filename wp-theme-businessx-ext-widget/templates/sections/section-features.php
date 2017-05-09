<?php
/* ------------------------------------------------------------------------- *
 *  Features Section Wrapper
/* ------------------------------------------------------------------------- */

	$features_sec__bg_overlay	= get_theme_mod( 'features_bg_overlay', false );
	$features_sec__hide 		= get_theme_mod( 'features_section_hide' ) == 0 ? true : false;
	$features_sec__title 		= get_theme_mod( 'features_section_title', esc_html__( 'Features Heading', 'businessx-extensions' ) );
	$features_sec__description 	= get_theme_mod( 'features_section_description', esc_html__( 'This is a description for the Features section. You can set it up in the Customizer where you can also add items for it.', 'businessx-extensions' ) );
	$features_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $features_sec__hide ) : ?>
<?php do_action( 'businessx_features_sec__before_wrapper' ); ?>
<section id="section-features" class="grid-wrap sec-features"<?php businessx_section_parallax( 'features_bg_parallax', 'features_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_features_sec__inner_wrapper_top' ); ?>
	<?php if( $features_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_features_sec__inner_container_top' ); ?>
    	<?php if( $features_sec__title != '' || $features_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $features_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $features_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $features_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $features_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <div class="grid-items clearfix <?php businessx_anim_classes(); ?>">
			<?php
            // Display features
            if ( is_active_sidebar( 'section-features' ) && ! is_paged() ) {
				dynamic_sidebar( 'section-features' );
			} else {
				if ( ! $features_sec_helpers ) {
					echo '<div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > Features Section</b> and add items by clicking on <b>Add or edit features</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div>';
				}
			}
            ?>
        </div>
        <?php do_action( 'businessx_features_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_features_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_features_sec__after_wrapper' );
	endif; // END Features Section
?>
