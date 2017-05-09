<?php
/* ------------------------------------------------------------------------- *
 *
 *  Testimonials Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title without the before/after args.
 *	$testimonial - returns testimonial text.
 *	$avatar - returns avatar url
 *	$target - returns _blank; you can use this filter to change it businessx_extensions_testimonials_item___btn_target( $target = '_blank' ).
 *	$btn_text - returns button text.
 *	$btn_url - returns button url.
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php if( $avatar != '' ) { ?><figure class="client-avatar"><img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $title ); ?>" /></figure><?php } ?>
<div class="testimonial-contents ta-center clearfix">
    <h3 class="hs-secondary-small"><?php echo esc_html( $title ); ?></h3>
    <p class="testimonial-excerpt"><?php echo esc_html( $testimonial ); ?></p>
    <div class="testimonial-button">
        <a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_url( $btn_url ); ?>" class="ac-btn btn-small fw-regular"><?php echo esc_html( $btn_text ); ?></a>
    </div>
</div>