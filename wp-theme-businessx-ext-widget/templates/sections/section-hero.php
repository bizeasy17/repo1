<?php
/* ------------------------------------------------------------------------- *
 *  Hero Section Wrapper
/* ------------------------------------------------------------------------- */

	$hero_sec__hide 		= get_theme_mod( 'hero_section_hide' ) == 0 ? true : false;
	$hero_sec__title 		= get_theme_mod( 'hero_section_title', esc_html__( 'Hero section title goes here.', 'businessx-extensions' ) );
	$hero_sec__description 	= get_theme_mod( 'hero_section_description', esc_html__( 'You can edit this section by going to Customizer > Sections > Hero Section', 'businessx-extensions' ) );
?>
<?php if( $hero_sec__hide ) : ?>
<?php do_action( 'businessx_hero_sec__before_wrapper' ); ?>
<section id="section-hero" class="sec-hero"<?php businessx_section_parallax( 'hero_bg_parallax', 'hero_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_hero_sec__inner_wrapper_top' ); ?>
	<div class="sec-hero-overlay">
    	<?php do_action( 'businessx_hero_sec__inner_container_top' ); ?>
    	
		<?php if( $hero_sec__title != '' || $hero_sec__description != '' ) : ?>
    	<div class="sec-hs-elements ta-center">
        	<?php if( $hero_sec__title != '' ) : ?>
       		<h2 class="hs-primary-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $hero_sec__title ); ?></h2>
            <?php endif; if( $hero_sec__description != '' ) : ?>
            <p class="sec-hs-description fs-largest fw-regular <?php businessx_anim_classes(); ?>"><?php echo esc_html( $hero_sec__description ); ?></p>
            <?php endif; ?>
            <div class="sec-hs-buttons <?php businessx_anim_classes(); ?>">
				<?php echo businessx_hero_btns_output(); ?>
			</div>
        </div>
        <?php endif; ?>

        <?php do_action( 'businessx_hero_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_hero_sec__inner_wrapper_bottom' ); ?>
</section>
<?php 
	do_action( 'businessx_hero_sec__after_wrapper' );
	endif; // END Portofolio Section 
?>