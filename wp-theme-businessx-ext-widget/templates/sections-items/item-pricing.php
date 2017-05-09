<?php
/* ------------------------------------------------------------------------- *
 *
 *  Pricing Item
 *  ________________
 *
 *	Variables (using set_query_var()):
 *	$wid - returns widget's id number, you can use it to target specific widgets.
 *	$title - outputs the title without the before/after args.
 *	$title_output - ^^ with the before/after args.
 *	$badge - returns badge text.
 *	$price - returns price.
 *	$period - returns period of time.
 *	$list - returns an array with the package list
 *	$icos - show or hide icons next to list items.
 *	$btn_anchor - returns button text.
 *	$btn_target - returns button target type.
 *	$btn_url - returns button url.
 *	________________
 *
/* ------------------------------------------------------------------------- */
?>

<?php if( $badge != '' ) { ?>
<div class="package-badge ta-center">
    <strong class="badge"><span class="fs-smallest"><?php echo esc_html( $badge ); ?></span></strong>
</div><?php } ?>
<header class="package-info clearfix">
    <h4 class="fw-regular ls-min hb-bottom-smaller alignleft"><?php echo esc_html( $title ); ?></h4>
    <?php if( $price != '' ) { ?>
    <div class="package-pricing alignright">
        <span class="package-price"><?php echo esc_html( $price ); ?></span>
        <span class="package-period fw-regular ls-min"><?php echo esc_html( $period ); ?></span>
    </div><?php } ?>
</header>
<div class="package-contents">
    <?php if( $list != '' ) { ?>
    <ul>
        <?php 
        foreach ( $list as $item ) {
            if( $icos ) {
                if( $item[ 'status'] == 'available' ) { 
                    $ico = businessx_icon( 'check', false ) . ' '; } else { $ico = businessx_icon( 'remove', false ) . ' '; } } else { $ico = ''; }
            
            if( $item[ 'item'] != '' ) { 
                echo '<li>' . $ico .  esc_html( $item[ 'item' ] ) . '</li>'; }
        } 
        ?>
    </ul><?php } ?>
    <?php if( $btn_anchor != '' ) { ?>
    <div class="package-btn">
        <a href="<?php echo esc_url( $btn_url ); ?>" target="<?php echo esc_attr( $btn_target ); ?>" class="ac-btn btn-big btn-width-100"><?php echo esc_html( $btn_anchor ); ?></a>
    </div><?php } ?>
</div>