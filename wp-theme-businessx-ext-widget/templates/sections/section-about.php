<?php
/* ------------------------------------------------------------------------- *
 *  About Us Section Wrapper
/* ------------------------------------------------------------------------- */

	$about_sec__bg_overlay	= get_theme_mod( 'about_bg_overlay', false );
	$about_sec__hide 		= get_theme_mod( 'about_section_hide' ) == 0 ? true : false;
	$about_sec__title 		= get_theme_mod( 'about_section_title', esc_html__( 'About Us Heading', 'businessx-extensions' ) );
	$about_sec__description 	= get_theme_mod( 'about_section_description', esc_html__( 'This is a description for the About Us section. You can set it up in the Customizer where you can also add items for it.', 'businessx-extensions' ) );
	$about_sec__hide_btn	= get_theme_mod( 'about_section_hide_btn' ) == 0 ? true : false;
	$about_sec__btn_anchor	= get_theme_mod( 'about_section_btn_anchor', __( 'More Info About Us', 'businessx-extensions' ) );
	$about_sec__btn_url		= get_theme_mod( 'about_section_btn_anchor_url', '#' );
	$about_sec__target		= get_theme_mod( 'about_section_btn_target', false ) ? '_blank' : '_self';
	$about_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $about_sec__hide ) : ?>
<?php do_action( 'businessx_about_sec__before_wrapper' ); ?>
<section id="section-about" class="grid-wrap sec-about"<?php businessx_section_parallax( 'about_bg_parallax', 'about_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_about_sec__inner_wrapper_top' ); ?>
	<?php if( $about_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_about_sec__inner_container_top' ); ?>
    	<?php if( $about_sec__title != '' || $about_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $about_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $about_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $about_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $about_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <div class="grid-items clearfix <?php businessx_anim_classes(); ?>">
			<?php
            // Display about
            if ( is_active_sidebar( 'section-about' ) && ! is_paged() ) {
				dynamic_sidebar( 'section-about' );
			} else {
				if ( ! $about_sec_helpers ) {
					echo '<div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > About Us Section</b> and add items by clicking on <b>Add or edit about boxes</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div>';
				}
			}
            ?>
        </div>
        <?php if( $about_sec__hide_btn ) { ?>
        <div class="about-button clearfix">
        	<a href="<?php echo esc_url( $about_sec__btn_url ); ?>" target="<?php echo esc_attr( $about_sec__target ); ?>" class="ac-btn btn-biggest btn-opaque"><?php echo esc_html( $about_sec__btn_anchor ); ?></a>
        </div><?php } ?>
        <?php do_action( 'businessx_about_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_about_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_about_sec__after_wrapper' );
	endif; // END About Us Section
?>
