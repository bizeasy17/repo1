<?php
/* ------------------------------------------------------------------------- *
 *  Actions Section Wrapper
/* ------------------------------------------------------------------------- */

$actions_sec__hide = get_theme_mod( 'actions_section_hide' ) == 0 ? true : false;

if( $actions_sec__hide ) :
	do_action( 'businessx_actions_sec__before_wrapper' );

		// Display questions
		if ( is_active_sidebar( 'section-actions' ) && ! is_paged() ) {
			dynamic_sidebar( 'section-actions' );
		} else {
			?>
            <section id="section-actions" class="grid-wrap sec-action elements-left">
                <div class="grid-container grid-1 clearfix <?php businessx_anim_classes(); ?>">

                    <div class="grid-col grid-4x-col ta-center elements-meta">
                        <h2 class="hs-primary-medium"><?php _e( 'Actions Section', 'businessx-extensions' ); ?></h2>
                        <div class="elements-excerpt fs-large">
                            <?php _e( 'You can find options for this section in: <b>Customizer > Sections > Actions Section</b> and add items by clicking on <b>Add or edit actions</b>.', 'businessx-extensions' ); ?>
                        </div>
                         <div class="elements-buttons">
                        <a href="#" class="ac-btn btn-big btn-1">Button Example</a>
                    </div>
                    </div><!-- END .elements-meta -->

                </div><!-- END .grid-container -->
            </section>
            <?php
		}

	do_action( 'businessx_actions_sec__after_wrapper' );
endif; // END Actions Section
?>
