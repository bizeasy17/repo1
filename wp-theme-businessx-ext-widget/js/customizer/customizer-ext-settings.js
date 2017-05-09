/* Customizer Settings Manager */
( function( api ) {

	var	api = wp.customize, 
		bx_ext_styles_template = wp.template( 'businessx-ext-czr-settings-output' ),
		bx_ext_simple_settings = _.map( bx_ext_customizer_settings, function( element, index ) { return index } ),
		bx_ext_settings_keys = bx_ext_simple_settings,
		bx_ext_settings_values = bx_ext_simple_settings;
		

	// Update function
	function bx_ext_update_css() {
		var new_settings,
			settings = _.object( bx_ext_settings_keys, bx_ext_customizer_settings );

		_.each( bx_ext_settings_values, function( new_value ) {
			settings[ new_value ] = api( new_value )();
		} );

		new_settings = bx_ext_styles_template( settings );

		api.previewer.send( 'bx-ext-update-settings', new_settings );
	}


	// Update the CSS whenever a color setting is changed.
	_.each( bx_ext_settings_values, function( new_value ) {
		api( new_value, function( new_value ) {
			new_value.bind( bx_ext_update_css );
		} );
	} );
	
} )( wp.customize );