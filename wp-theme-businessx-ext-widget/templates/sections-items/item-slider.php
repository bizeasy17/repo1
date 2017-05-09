<?php
/* ------------------------------------------------------------------------- *
 *
 *  Slider Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title without the before/after args.
 *	$paragraph - returns a paragraph bellow the title.
 *	$overlay - reutrns a style attribute with a gradient overlay, already escaped.
 *	$btn_show - show or hide buttons.
 *	$btn_type - button design, normal or opaque.
 *	$btn_between - text between buttons.
 *	$btn_1_text & $btn_2_text - returns button text.
 *	$btn_1_target & $btn_2_target - returns button target type.
 *	$btn_1_url & $btn_2_url - returns button url.
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<div class="sec-slider-overlay"<?php echo $overlay; ?>>
    <div class="sec-hs-elements ta-center">
        <?php if( $title != '' ) { ?><h2 class="hs-primary-large animated"><?php echo esc_html( $title ); ?></h2><?php } ?>
        <?php if( $paragraph != '' ) { ?><p class="sec-hs-description fs-largest fw-regular"><?php echo esc_html( $paragraph ); ?></p><?php } ?>
        <?php if( $btn_show ) { ?>
        <div class="sec-hs-buttons">
            <?php echo businessx_slider_btns_output( $btn_type, $btn_between, $btn_1_text, $btn_1_url, $btn_1_target, $btn_2_text, $btn_2_url, $btn_2_target ); ?>
        </div>
        <?php } ?>
    </div>
</div>