<?php
/* ------------------------------------------------------------------------- *
 *  Team Section Wrapper
/* ------------------------------------------------------------------------- */

	$team_sec__bg_overlay	= get_theme_mod( 'team_bg_overlay', false );
	$team_sec__hide 		= get_theme_mod( 'team_section_hide' ) == 0 ? true : false;
	$team_sec__title 		= get_theme_mod( 'team_section_title', __( 'Team Heading', 'businessx-extensions' ) );
	$team_sec__description 	= get_theme_mod( 'team_section_description', __( 'This is a description for the Team section. You can set it up in the Customizer where you can also add items for it.', 'businessx-extensions' ) );
	$team_sec_helpers		= get_theme_mod( 'disable_helpers', false );
?>
<?php if( $team_sec__hide ) : ?>
<?php do_action( 'businessx_team_sec__before_wrapper' ); ?>
<section id="section-team" class="grid-wrap sec-team"<?php businessx_section_parallax( 'team_bg_parallax', 'team_bg_parallax_img' ); ?>>
	<?php do_action( 'businessx_team_sec__inner_wrapper_top' ); ?>
	<?php if( $team_sec__bg_overlay ) { echo '<div class="grid-overlay"></div>'; } ?>
	<div class="grid-container grid-1 clearfix">
    	<?php do_action( 'businessx_team_sec__inner_container_top' ); ?>
    	<?php if( $team_sec__title != '' || $team_sec__description != '' ) : ?>
    	<header class="section-header">
        	<?php if( $team_sec__title != '' ) : ?>
       		<h2 class="section-title hs-primary-medium hb-bottom-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $team_sec__title ); ?></h2>
            <div class="divider"></div>
            <?php endif; if( $team_sec__description != '' ) : ?>
            <p class="section-description fs-large <?php businessx_anim_classes(); ?>"><?php echo esc_html( $team_sec__description ); ?></p>
            <?php endif; ?>
        </header>
        <?php endif; ?>
        <div class="grid-items clearfix <?php businessx_anim_classes(); ?>">
			<?php
            // Display team
            if ( is_active_sidebar( 'section-team' ) && ! is_paged() ) {
				dynamic_sidebar( 'section-team' );
			} else {
				if ( ! $team_sec_helpers ) {
					echo '<div class="grid-col grid-4x-col ta-center">' . __( 'You can find options for this section in: <b>Customizer > Sections > Team Section</b> and add items by clicking on <b>Add or edit members</b>. You can also disable this message from <b>Customizer > Settings > Extensions > Disable helpers/placeholders</b>.', 'businessx-extensions' ) . '</div>';
				}
			}
            ?>
        </div>
        <?php do_action( 'businessx_team_sec__inner_container_bottom' ); ?>
    </div>
    <?php do_action( 'businessx_team_sec__inner_wrapper_bottom' ); ?>
</section>
<?php
	do_action( 'businessx_team_sec__after_wrapper' );
	endif; // END Team Section
?>
