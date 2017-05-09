<?php
/* ------------------------------------------------------------------------- *
 *
 *  Customizer
 *  ________________
 *
 *	This file adds the needed functions/files for the Customizer
 *
/* ------------------------------------------------------------------------- */



/*  Get all theme mods
/* ------------------------------------ */
require_once ( BUSINESSX_EXTS_PATH . 'inc/customizer/theme-mods.php' );



/*  Customizer JS/CSS
/* ------------------------------------ */
if( ! function_exists( 'businessx_extensions_customizer_js_css' ) ) {
	function businessx_extensions_customizer_js_css() {
		global $businessx_extensions_cs_mods;

		// Customizer Hacks
		wp_enqueue_script( 'businessx-extensions-customizer-js', BUSINESSX_EXTS_URL . 'js/customizer/customizer-ext.js', array(), '20160412', true );
		wp_localize_script( 'businessx-extensions-customizer-js', 'businessx_customizer_js_data',
			array(
				'businessx_extensions_sections_nonce' => wp_create_nonce( 'businessx_extensions_sections_nonce' ),
				'businessx_extensions_sections_bk_nonce' => wp_create_nonce( 'businessx_extensions_sections_bk_nonce' ),
				'businessx_extensions_sections_rt_nonce' => wp_create_nonce( 'businessx_extensions_sections_rt_nonce' ),
				'bxext_create_frontpage' => wp_create_nonce( 'bxext_create_frontpage' ),
				'bxext_dismiss_create_frontpage' => wp_create_nonce( 'bxext_dismiss_create_frontpage' ),
		) );

		// Settings Manager
		wp_enqueue_script( 'businessx-extensions-customizer-settings', BUSINESSX_EXTS_URL . 'js/customizer/customizer-ext-settings.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20160412', true );
		wp_localize_script( 'businessx-extensions-customizer-settings', 'bx_ext_customizer_settings', $businessx_extensions_cs_mods );

	}
}
add_action( 'customize_controls_enqueue_scripts', 'businessx_extensions_customizer_js_css' );

if( ! function_exists( 'businessx_extensions_customizer_preview_js' ) ) {
	function businessx_extensions_customizer_preview_js() {
		$sections = businessx_extensions_sections();

		wp_enqueue_script( 'businessx-extensions-customize-preview', BUSINESSX_EXTS_URL . 'js/customizer/customize-ext-preview.js', array( 'customize-preview' ), '20160412', true );
		wp_localize_script( 'businessx-extensions-customize-preview', 'bx_ext_customizer_settings', $sections );
	}
}
add_action( 'customize_preview_init', 'businessx_extensions_customizer_preview_js' );
