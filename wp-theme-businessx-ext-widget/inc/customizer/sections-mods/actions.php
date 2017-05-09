<?php
/* ------------------------------------------------------------------------- *
 *  ______
 *
 *  Actions
 *  ________________
 *
 *	Panel, settings and controls options
 *	_____________________________
 *
 *	All the "businessx_controller_*" are located in the theme:
 *	../acosmin/customizer/customizer.php
 *
 *	They use $wp_customize->add_setting and $wp_customize->add_control to
 *	add settings and controls, all sanitized.
 *
/* ------------------------------------------------------------------------- */



	/*  Add section
	/* ------------------------------------ */
	$wp_customize->add_section( 'businessx_section__actions', array(
		'title'				=> esc_html__( 'Actions Section', 'businessx-extensions' ),
		'panel'				=> 'businessx_panel__sections',
		'priority'			=> absint( businessx_extensions_sec_prio( 'businessx_section__actions' ) ),
	) );
		


		/*  Actions Section options
		/* ------------------------------------ */
		
		// Hide section
		businessx_controller_checkbox(
			'actions_section_hide',
			'businessx_section__actions',
			esc_html__( 'Hide this section', 'businessx-extensions' ) ); 
		/*=====*/