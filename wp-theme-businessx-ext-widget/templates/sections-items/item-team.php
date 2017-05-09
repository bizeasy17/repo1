<?php
/* ------------------------------------------------------------------------- *
 *
 *  Team Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title without the before/after args.
 *	$title_output - ^^ with the before/after args.
 *	$description - returns a description bellow the title.
 *	$position - returns team member's position
 *	$avatar - returns an url for your avatar
 *	$allowed_html - sets what html tags you can use in $description; you can use this filter businessx_extensions_team_item___allowed_html( $allowed_html = array() );
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php 
if( $avatar != '' ) {
	echo '<figure class="sec-team-member-avatar"><img src="' . esc_url( $avatar ) . '" alt="' . esc_attr( $title ) . '" /></figure>';
}
?>
<?php if( $title != '' ) { ?><h3 class="hs-secondary-small"><?php echo $title_output; ?></h3><?php } ?>
<?php if( $position != '' ) { ?><h4 class="fw-regular ls-min hb-bottom-abs-small"><?php echo $position; ?></h4><?php } ?>
<?php if( $description != '' ) { ?><p><?php businessx_content_filter( $description, $allowed_html, TRUE ); ?></p><?php } ?>
<?php
	if( $social_links != '' ) {
		echo '<div class="sec-team-member-social clearfix">';
		foreach ( $social_links as $profile ) {
			if( $profile[ 'url' ] != '' ) {
				echo '<a href="' . esc_url( $profile[ 'url' ] ) . '" target="_blank" class="social-btn simple-social"><i></i></a>';	
			}
		}
		echo '</div>';
	}
?>