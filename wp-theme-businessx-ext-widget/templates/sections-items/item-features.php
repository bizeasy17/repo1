<?php
/* ------------------------------------------------------------------------- *
 *
 *  Features Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title without the before/after args.
 *	$title_output - ^^ with the before/after args.
 *	$excerpt - returns the excerpt/paragraphs.
 *	$allowed_html - sets what html tags you can use in $excerpt; you can use this filter businessx_extensions_features_item___allowed_html( $allowed_html = array() ).
 *	$figure_type - what type of figure do you want to display, icon (ft-icon) or image (ft-image).
 *	$figure_icon - returns icon name.
 *	$figure_image - returns image url.
 *	$btn_anchor - returns button text.
 *	$btn_target - returns button target type.
 *	$btn_url - returns button url.
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php 
if( $show_figure ) {
	if( $figure_type == 'ft-icon' && $figure_icon != '' ) {
		echo '<figure class="sec-feature-figure">' . businessx_icon( $figure_icon, FALSE, FALSE ) . '</figure>';
	} elseif( $figure_type == 'ft-image' && $figure_image != '' ) {
		echo '<figure class="sec-feature-figure-img"><img src="' . esc_url( $figure_image ) . '" alt="' . esc_attr( $title ) . '" /></figure>';	
	}
} 
?>
<div class="contents-wrap clearfix">
<?php if( $title != '' ) { ?><h3 class="hs-secondary-small"><?php echo $title_output; ?></h3><?php } ?>
<?php if( $excerpt != '' ) { ?><p><?php businessx_content_filter( $excerpt, $allowed_html, TRUE ); ?></p><?php } ?>
<?php if( $btn_anchor != '' ) { echo '<a href="' . esc_url( $btn_url ) . '" target="' . $btn_target . '" class="ac-btn-alt fw-bolder">' . esc_html( $btn_anchor ) . '</a>'; } ?>
</div>