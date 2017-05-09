<?php
/* ------------------------------------------------------------------------- *
 *
 *  FAQ Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title without the before/after args.
 *	$title_output - ^^ with the before/after args.
 *	$excerpt - returns the excerpt/paragraphs.
 *	$allowed_html - sets what html tags you can use in $excerpt; you can use this filter businessx_extensions_faq_item___allowed_html( $allowed_html = array() );
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php if( $title != '' ) { ?><h3 class="hs-secondary-small hb-bottom-small"><?php echo $title_output; ?></h3><?php } ?>
<?php if( $excerpt != '' ) { ?><?php echo wpautop( businessx_content_filter( $excerpt, $allowed_html, FALSE ) ); ?><?php } ?>