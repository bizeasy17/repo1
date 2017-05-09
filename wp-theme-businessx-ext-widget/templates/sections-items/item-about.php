<?php
/* ------------------------------------------------------------------------- *
 *
 *  Actions Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$title - outputs the title without the before/after args.
 *	$title_output - ^^ with the before/after args.
 *	$excerpt - outputs the paragraphs you wrote.
 *	$allowed_html - sets what html tags you can use in $excerpt; you can use this filter businessx_extensions_about_item___allowed_html( $allowed_html = array() );
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php if( $title != '' ) { ?>
<h3 class="hs-secondary-large fw-light"><?php echo esc_html( $title_output ); ?></h3>
<?php }; if( $excerpt != '' ) { echo wpautop( businessx_content_filter( $excerpt, $allowed_html, FALSE ) ); } ?>