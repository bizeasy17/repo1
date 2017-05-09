<?php
/* ------------------------------------------------------------------------- *
 *  Enqueues scripts for Administration area
/* ------------------------------------------------------------------------- */
if ( ! function_exists( 'businessx_extensions_admin_scripts' ) ) {
	function businessx_extensions_admin_scripts() {
		global $businessx_icons_simple, $businessx_sections, $wp_customize;
		$current_screen = get_current_screen();
		$sections_position = get_theme_mod( 'businessx_sections_position' );

		if( $current_screen->id === "widgets" || isset( $wp_customize ) ) : // Show only on the Widgets page and Customizer

			// CSS Styles
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'businessx-extensions-widgets-customizer', BUSINESSX_EXTS_URL . 'css/widgets-customizer.css', array(), '20160412', 'all' );
			wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/icons/css/font-awesome.min.css', array(), NULL, 'all' );

			// JS Scripts
			wp_enqueue_media();
			wp_enqueue_script(
				'businessx-extensions-widgets-customizer',
				BUSINESSX_EXTS_URL . 'js/admin/widgets-customizer.js',
				array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-autocomplete', 'wp-color-picker' ),
				'20160412', FALSE
			);

			// Add some data
			wp_localize_script(
				'businessx-extensions-widgets-customizer',
				'businessx_ext_widgets_customizer',
				apply_filters( 'businessx_extensions___widgets_customizer', array(
					/* A list of all the icons for autocomplete */
					'bx_icons_array'		=> (array) $businessx_icons_simple,
					/* Admin */
					'bx_admin_url' 			=> esc_url( admin_url() ),
					'bx_ajax_url' 			=> esc_url( admin_url('admin-ajax.php') ),
					/* Sections array */
					'bx_sections'			=> (array) $businessx_sections,
					'bx_sections_pos'		=> (array) $sections_position,
					/* Sections related*/
					'bx_add_items_to'		=> esc_html__( 'Adding items to:', 'businessx-extensions' ),
					/* Customizer Messages */
					'bx_wrong_page'			=> esc_html__( 'The current page does not contain this section. We will go back now!', 'businessx-extensions' ),
					'bx_drag_drop_msg'		=> esc_html__( 'Drag and drop for position', 'businessx-extensions' ),
					'bx_updated_pos_msg'	=> esc_html__( 'Updated Sections Position', 'businessx-extensions' ),
					'bx_backup_bubble'		=> esc_html__( 'Click on this button if you want to backup the position of your current widgets, including sections items (available for this theme).', 'businessx-extensions' ),
					'bx_backup_alert_fail'	=> esc_html__( 'You need to save your settings before you backup!', 'businessx-extensions' ),
					'bx_backup_alert_succ'	=> esc_html__( 'Widgets & sections items positions backed up!', 'businessx-extensions' ),
					'bx_restore_alert_succ'	=> esc_html__( 'Backup restored! Refreshing the page now...', 'businessx-extensions' ),
					/* Sections btns */
					'bx_tabs_btb_colors'	=> esc_html__( 'Colors', 'businessx-extensions' ),
					'bx_tabs_btb_bg'		=> esc_html__( 'Background', 'businessx-extensions' ),
					'bx_tabs_available'		=> esc_html__( 'Available', 'businessx-extensions' ),
					'bx_tabs_item_title'	=> esc_html__( 'Item title', 'businessx-extensions' ),
					'bx_tabs_unavailable'	=> esc_html__( 'Unavailable', 'businessx-extensions' ),
						// Features
						'bx_anw_btn_features'		=> esc_html__( 'Add a Feature', 'businessx-extensions' ),
						'bx_sec_btn_features'		=> esc_html__( 'Add or edit features', 'businessx-extensions' ),
						// FAQ
						'bx_anw_btn_faq'			=> esc_html__( 'Add a Question', 'businessx-extensions' ),
						'bx_sec_btn_faq'			=> esc_html__( 'Add or edit questions', 'businessx-extensions' ),
						// Clients
						'bx_anw_btn_clients'		=> esc_html__( 'Add a Client', 'businessx-extensions' ),
						'bx_sec_btn_clients'		=> esc_html__( 'Add or edit clients', 'businessx-extensions' ),
						// Actions
						'bx_anw_btn_actions'		=> esc_html__( 'Add an Action', 'businessx-extensions' ),
						'bx_sec_btn_actions'		=> esc_html__( 'Add or edit actions', 'businessx-extensions' ),
						// About Us
						'bx_anw_btn_about'			=> esc_html__( 'Add an About Box', 'businessx-extensions' ),
						'bx_sec_btn_about'			=> esc_html__( 'Add or edit about boxes', 'businessx-extensions' ),
						// Testimonials
						'bx_anw_btn_testimonials'	=> esc_html__( 'Add a Testimonial', 'businessx-extensions' ),
						'bx_sec_btn_testimonials'	=> esc_html__( 'Add or edit testimonials', 'businessx-extensions' ),
						// Team
						'bx_anw_btn_team'			=> esc_html__( 'Add a Member', 'businessx-extensions' ),
						'bx_sec_btn_team'			=> esc_html__( 'Add or edit members', 'businessx-extensions' ),
						// Pricing
						'bx_anw_btn_pricing'		=> esc_html__( 'Add a Package', 'businessx-extensions' ),
						'bx_sec_btn_pricing'		=> esc_html__( 'Add or edit packages', 'businessx-extensions' ),
						// Slider
						'bx_anw_btn_slider'			=> esc_html__( 'Add a Slide', 'businessx-extensions' ),
						'bx_sec_btn_slider'			=> esc_html__( 'Add or edit slides', 'businessx-extensions' ),
					/* Hide Items btns for */
					'bx_sec_item_btn_hide'	=> array( 'portfolio', 'hero', 'blog' ), /* ==REMOVE== */
				) )
			);
		endif;

	}
}
add_action( 'admin_enqueue_scripts', 'businessx_extensions_admin_scripts' );
