<?php
/* ------------------------------------------------------------------------- *
 *  Helper Functions
/* ------------------------------------------------------------------------- */



/*  Check theme version
/* ------------------------------------ */
if( ! function_exists( 'businessx_extensions_ck_theme_v' ) ) {
    function businessx_extensions_ck_theme_v( $version, $sign = '>' ) {
        $theme_name = apply_filters( 'businessx_extensions___get_theme_name', 'businessx');
        $theme_ver = wp_get_theme( $theme_name )->get('Version');
        if( version_compare( $theme_ver, $version, $sign ) ) {
            return true;
        } else {
            return false;
        }
    }
}



/*  Check if Jetpack specific module
 *  is enabled
/* ------------------------------------ */
if( ! function_exists( 'businessx_extensions_jp_active' ) ) {
    function businessx_extensions_jp_active( $module ) {
        $active_modules = get_option( 'jetpack_active_modules' );
        if( $active_modules !== false ) {
            if( in_array( $module, $active_modules, TRUE ) ) { return true; } else { return false;  }
        } else {
            return false;
        }
    }
}



/*  Check if Jetpack specific module
 *  is enabled
/* ------------------------------------ */
if( ! function_exists( 'businessx_extensions_jp_ck_mobile_theme' ) ) {
    function businessx_extensions_jp_ck_mobile_theme() {
        if( businessx_extensions_jp_active( 'minileven' ) ) {
            echo '<div class="notice error is-dismissible">';
        		echo '<p>' . __( 'Jetpack\'s <i> Mobile Theme</i> module is activated.', 'businessx-extensions' )  .'</p>';
                echo '<p>' . __( 'This will cause an error or blank page on mobile devices. Businessx is already a responsive/mobile theme. Please disable the Mobile Theme module.', 'businessx-extensions' ) . '</p>';
            echo '</div>';
        }
    }
}
add_action( 'admin_notices', 'businessx_extensions_jp_ck_mobile_theme', 0 );



/*  Section Parallax
/* ------------------------------------ */
if( businessx_extensions_ck_theme_v( '1.0.4', '>=' ) || ! ( 'Businessx' == businessx_extensions_theme() ) || ! ( 'Businessx' == businessx_extensions_theme( true ) ) ) :
if ( ! function_exists( 'businessx_section_parallax' ) ) {
	function businessx_section_parallax( $enabled, $bgimg, $return = false ) {
		$background			= get_theme_mod( $bgimg, '' );
		$parallax			= get_theme_mod( $enabled, false );
		$output				= '';

		if( $bgimg != '' && $parallax ) {
			$output = ' data-parallax="scroll" data-speed="0.5" data-image-src="' . esc_url( $background ) . '" style="background: none !important;"';
		}

		if( $return ) { return $output; } else { echo $output; }
	}
}
endif;



if( businessx_extensions_ck_theme_v( '1.0.3' ) || ! ( 'Businessx' == businessx_extensions_theme() ) || ! ( 'Businessx' == businessx_extensions_theme( true ) ) ) : // Backwards compatibility
/*  Hero buttons output
/* ------------------------------------ */
if( ! function_exists( 'businessx_hero_btns_output' ) ) {
    function businessx_hero_btns_output() {
    	$type = get_theme_mod( 'hero_section_btns', apply_filters( 'businessx_hero___btns_type_default', 'btns-2-def-op' ) );
    	$btn_1_text = get_theme_mod( 'hero_section_1st_btn', __( 'Call to Action', 'businessx-extensions' ) );
    	$btn_1_link = get_theme_mod( 'hero_section_1st_btn_link', '#' );
    	$btn_1_target = get_theme_mod( 'hero_section_1st_btn_target', false ) ? '_blank' : '_self';
    	$btn_2_text = get_theme_mod( 'hero_section_2nd_btn', __( 'Call to Action', 'businessx-extensions' ) );
    	$btn_2_link = get_theme_mod( 'hero_section_2nd_btn_link', '#' );
    	$btn_2_target = get_theme_mod( 'hero_section_2nd_btn_target', false ) ? '_blank' : '_self';
    	$btns_between = get_theme_mod( 'hero_section_btns_or', __( 'Or', 'businessx-extensions' ) );
    	$output = '';

    	switch( $type ) {
    		// One button - default
    		case 'btns-1-default' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// One button - opaque
    		case 'btns-1-opaque' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// One large - default
    		case 'btns-1-l-default' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st btn-width-50">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// One large - opaque
    		case 'btns-1-l-opaque' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st btn-width-50 btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// Two - default
    		case 'btns-2-default' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-2nd">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Two - opaque
    		case 'btns-2-opaque' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Two - default + opaque
    		case 'btns-2-def-op' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Two - opaque + default
    		case 'btns-2-op-def' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-2nd">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Default
    		default : $output .= '';
    	}

    	return apply_filters( 'businessx_hero___btns_output', $output );

    }
}



/*  Slider buttons output
/* ------------------------------------ */
if( ! function_exists( 'businessx_slider_btns_output' ) ) {
    function businessx_slider_btns_output( $type = 'btns-2-def-op', $btns_between = ' ', $btn_1_text = '', $btn_1_link = '', $btn_1_target = false, $btn_2_text = '', $btn_2_link = '', $btn_2_target = false ) {
    	$btn_1_text 	= ! empty( $btn_1_text ) ? $btn_1_text : esc_html__( 'Call to Action #1', 'businessx-extensions' );
    	$btn_1_link 	= ! empty( $btn_1_link ) ? $btn_1_link : '#';
    	$btn_1_target	= $btn_1_target ? '_blank' : '_self';
    	$btn_2_text 	= ! empty( $btn_2_text ) ? $btn_2_text : esc_html__( 'Call to Action #2', 'businessx-extensions' );
    	$btn_2_link 	= ! empty( $btn_2_link ) ? $btn_2_link : '#';
    	$btn_2_target	= $btn_2_target ? '_blank' : '_self';
    	$output = '';

    	switch( $type ) {
    		// One button - default
    		case 'btns-1-default' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// One button - opaque
    		case 'btns-1-opaque' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// One large - default
    		case 'btns-1-l-default' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st btn-width-50">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// One large - opaque
    		case 'btns-1-l-opaque' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-width-50 btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    		break;

    		// Two - default
    		case 'btns-2-default' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Two - opaque
    		case 'btns-2-opaque' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Two - default + opaque
    		case 'btns-2-def-op' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Two - opaque + default
    		case 'btns-2-op-def' :
    			$output .= '<a target="' . esc_attr( $btn_1_target ) . '" href="' . esc_url( $btn_1_link ) . '" class="ac-btn btn-biggest ac-btn-2nd btn-opaque">' . esc_html( $btn_1_text ) . '</a>';
    			if( $btns_between != '' ) {
    				$output .= '<span class="ac-btns-or fw-bolder">' . esc_html( $btns_between ) . '</span>'; }
    			$output .= '<a target="' . esc_attr( $btn_2_target ) . '" href="' . esc_url( $btn_2_link ) . '" class="ac-btn btn-biggest ac-btn-1st">' . esc_html( $btn_2_text ) . '</a>';
    		break;

    		// Default
    		default : $output .= '';
    	}

    	return apply_filters( 'businessx_slider___btns_output', $output );

    }
}



/*  Slider buttons options
/* ------------------------------------ */
if( ! function_exists( 'businessx_slider_btns_select' ) ) {
    function businessx_slider_btns_select( $just_values = false ) {
    	if( ! $just_values ) {
    		$options = apply_filters( 'businessx_slider_btns___select', $options = array(
    				array(
    					'value' 	=> 'btns-1-default',
    					'title'		=> esc_html__( 'One - Default', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-1-opaque',
    					'title'		=> esc_html__( 'One - Opaque', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-1-l-default',
    					'title'		=> esc_html__( 'One Large - Default', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-1-l-opaque',
    					'title'		=> esc_html__( 'One Large - Opaque', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-2-default',
    					'title'		=> esc_html__( 'Two - Default', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-2-opaque',
    					'title'		=> esc_html__( 'Two - Opaque', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-2-def-op',
    					'title'		=> esc_html__( 'Two - Default + Opaque', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    				array(
    					'value' 	=> 'btns-2-op-def',
    					'title'		=> esc_html__( 'Two - Opaque + Default', 'businessx-extensions' ),
    					'disabled'	=> false
    				),
    		) );
    	} else {
    		$options = apply_filters( 'businessx_slider_btns___select_values', $options = array(
    			'btns-1-default', 'btns-1-opaque', 'btns-1-l-default', 'btns-1-l-opaque', 'btns-2-default', 'btns-2-opaque', 'btns-2-def-op', 'btns-2-op-def'
    		) );
    	}

    	return $options;
    }
}
endif; // Backwards compatibility END
