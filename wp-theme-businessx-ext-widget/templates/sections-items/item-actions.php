<?php
/* ------------------------------------------------------------------------- *
 *
 *  About Section Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title.
 *	$excerpt - outputs the paragraphs you wrote.
 *	$allowed_html - sets what html tags you can use in $excerpt; you can use this filter businessx_extensions_actions_item___allowed_html( $allowed_html = array() );
 *	$image - if an image is uploaded it returns its url.
 *	$overlay - show/hide overlay.
 *	$overlay_opacity - overlay opacity, from 0 to 1.
 *	$opacity - outputs a style attribute with overlay an opacity element ( $overlay_opacity ).
 *	$alignment - select between two options, left or right.
 *	$show_btn_1 & $show_btn_2 - show or hide each button, boolean.
 *	$btn_1_title & $btn_2_title - returns a string with the buttons title.
 *	$btn_1_url & $btn_2_url - returns a url for each button.
 *	$btn_1_target & $btn_2_target - returns a target (blank/self) for each button.
 *	________________
 *
/* ------------------------------------------------------------------------- */

// Some variables
$action_classes = ( $image != '' ) ? 'grid-2x-col' : 'grid-4x-col ta-center';
$align = ( $alignment == 'left' ) ? '' : ' style="float:right; margin-right: 0;"';
$button_1_target = ( $btn_1_target ) ? '_blank' : '_self'; 
$button_2_target = ( $btn_2_target ) ? '_blank' : '_self'; 
?>

<?php if( $overlay && $overlay_opacity != 0 ) { echo '<div class="grid-overlay"' . $opacity . '></div>'; } ?>
<div class="grid-container grid-1 clearfix <?php businessx_anim_classes(); ?>">

	<?php if( $image != '' ) { ?>
    <div class="grid-col grid-2x-col elements-thumb" <?php echo $align; ?>>
        <img class="sec-ribbon-item-tmb" src="<?php echo esc_url( $image ) ?>" alt="<?php _e( 'Thumbnail', 'businessx-extensions' ); ?>" />
    </div><!-- END .elements-thumb -->
    <?php } ?>
    
    <div class="grid-col <?php echo $action_classes; ?> elements-meta last-col">
        <h2 class="hs-primary-medium"><?php echo esc_html( $title ); ?></h2>
        <div class="elements-excerpt fs-large">
            <?php if( $excerpt != '' ) { ?><?php echo wpautop( businessx_content_filter( $excerpt, $allowed_html, FALSE ) ); ?><?php } ?>
        </div>
        <?php if( $show_btn_1 || $show_btn_2 ) { ?>
        <div class="elements-buttons">
            <?php if( $show_btn_1 && $btn_1_title != '' ) { ?>
            <a href="<?php echo esc_url( $btn_1_url ); ?>" target="<?php echo $button_1_target; ?>" class="ac-btn btn-big btn-1"><?php echo esc_html( $btn_1_title ); ?></a><?php } ?>
            <?php if( $show_btn_2 && $btn_2_title != '' ) { ?>
            <?php if( $show_btn_1 ) { ?><span class="ac-btns-or fw-bolder"><?php echo esc_html( $or ); ?></span><?php } ?>
            <a href="<?php echo esc_url( $btn_2_url ); ?>" target="<?php echo $button_2_target; ?>" class="ac-btn btn-big btn-2"><?php echo esc_html( $btn_2_title ); ?></a><?php } ?>
        </div>
        <?php } ?>
    </div><!-- END .elements-meta -->
    
</div><!-- END .grid-container -->