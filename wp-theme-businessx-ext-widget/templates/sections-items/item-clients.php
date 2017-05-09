<?php
/* ------------------------------------------------------------------------- *
 *
 *  Clients Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title.
 *	$logo - returns the logo url.
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php if( $logo != '' ) { echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( $title ) . '" />'; } ?>