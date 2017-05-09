/* Customizer JS */
jQuery( document ).ready( function( $ ) {

	/* Add a backup button */
	var bxHeaderActions = '#customize-header-actions';

	$( bxHeaderActions ).prepend(
		'<a href="#" class="customize-controls-close bx-backup-sections"><span class="bx-backup-pulse"></span><span class="bx-backup-bubble">'
		+ businessx_ext_widgets_customizer['bx_backup_bubble'] +
		'</span></a>' );

	$(document).on( 'click', '.bx-backup-sections', function( event ) {
		event.preventDefault();
		if( $( bxHeaderActions + ' .save' ).is(':disabled') === true ) {
			$.ajax({
				url: businessx_ext_widgets_customizer.bx_ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'businessx_extensions_sections_bk',
					businessx_extensions_sections_bk_nonce: businessx_customizer_js_data.businessx_extensions_sections_bk_nonce,
				}
			})
			.done( function( data ) {
				$( '.bx-backup-pulse' ).hide();
				alert(businessx_ext_widgets_customizer['bx_backup_alert_succ']);
			});
		} else {
			alert(businessx_ext_widgets_customizer['bx_backup_alert_fail']);
		}

	});

	$(document).on( 'widget-added', function() {
		$( '.bx-backup-pulse' ).show();
	});
	$(document).on( 'widget-updated', function() {
		$( '.bx-backup-pulse' ).show();
	});

	/* Restore a backup button */
	$(document).on( 'click', '.bx-restore-sections', function( event ) {
		event.preventDefault();
		if( $( bxHeaderActions + ' .save' ).is(':disabled') === true ) {
			$.ajax({
				url: businessx_ext_widgets_customizer.bx_ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'businessx_extensions_sections_rt',
					businessx_extensions_sections_rt_nonce: businessx_customizer_js_data.businessx_extensions_sections_rt_nonce,
				}
			})
			.done( function( data ) {
				alert(businessx_ext_widgets_customizer['bx_restore_alert_succ']);
				location.reload(true);
			});
		} else {
			alert(businessx_ext_widgets_customizer['bx_backup_alert_fail']);
		}

	});

	/* Arrange position for each sections */
	var bx_fps = '#accordion-panel-businessx_panel__sections > .accordion-sub-container',
		bx_fps2 = '#sub-accordion-panel-businessx_panel__sections',
		bx_frontPageSections = ( $( bx_fps2 ).length > 0 ) ? $( bx_fps2 ) : $( bx_fps ),
		bx_DragDropMsg = '<li class="bx_drag_and">' + businessx_ext_widgets_customizer.bx_drag_drop_msg + '</li>';

	/* Make an array of the current sections and positions */
	function bx_SectionsArray() {
		var newItemsArray = bx_frontPageSections.sortable( 'toArray' );
		for( var i = 0; i < newItemsArray.length; i++ ) {
			newItemsArray[ i ] = newItemsArray[ i ].replace( 'accordion-section-', '' );
		}
		return newItemsArray;
	}

	/* Add a visual loading spinner when an action takes place */
	bx_frontPageSections.find('.panel-meta' ).after( bx_DragDropMsg );

	/* If the Ajax query is done, setup priorities and refresh the previewer */
	function bx_IfAjaxIsDone() {
		$.each( bx_SectionsArray(), function( key, value ) {
			wp.customize.section( value ).priority( key );
		});
		bx_frontPageSections.find('.bx_drag_and img').remove();
		wp.customize.previewer.refresh();
	}

	/* Set a theme mod with sections position via Ajax */
	function bx_SetSecPosition( theArray ) {
		$.ajax({
			url: businessx_ext_widgets_customizer.bx_ajax_url,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'businessx_extensions_sections_position',
				businessx_extensions_sections_nonce: businessx_customizer_js_data.businessx_extensions_sections_nonce,
				items: theArray
			}
		})
		.done( function( data ) {
			bx_IfAjaxIsDone();
			console.log( businessx_ext_widgets_customizer[ 'bx_updated_pos_msg' ] );
		});
	}

	/* Sort sections position on page */
	bx_frontPageSections.sortable({
		helper: 'clone',
		items: '> li.control-section',
		cancel: 'li.ui-sortable-handle.open',
		delay: 150,
		create: function( event, ui ) {
			/* 	When the sortable list is created make sure we have the right positions.
				Also, in case we add a new section via plugin. */
			var array1 = bx_SectionsArray(),
				array2 = businessx_ext_widgets_customizer[ 'bx_sections_pos' ],
				is_same = array1.length == array2.length && array1.every(function(element, index) {
					return element === array2[index];
				});

			if( ! is_same ) {
				bx_SetSecPosition( bx_SectionsArray() );
			}
		},
		update: function( event, ui ) {
			/* If a sections is moved, save position in a theme mod */
			bx_frontPageSections.find( '.bx_drag_and' )
				.prepend( '<img src="' + businessx_ext_widgets_customizer.bx_admin_url + '/images/spinner.gif" />' );
			bx_SetSecPosition( bx_SectionsArray() );

			$('.wp-full-overlay-sidebar-content').scrollTop(0);
		},
	});

	/* Sections specific JS */
	var bx_allsections = businessx_ext_widgets_customizer[ 'bx_sections' ];

	wp.customize.section.each( function ( section ) {
		$.each( bx_allsections, function( index, value ) {

			var currentCheck			= $('#sub-accordion-section-businessx_section__slider').length,
				currentSectionID	 	= ( currentCheck > 0 ) ?
				'#sub-accordion-section-businessx_section__' + value : '#accordion-section-businessx_section__' + value,
				currentSectionTab		= $( '#accordion-section-businessx_section__' + value ),
				currentSectionSlctID	= $( currentSectionID ),
				checkParallaxOption 	= currentSectionSlctID.find( '#customize-control-' + value + '_bg_parallax input:checkbox' ),
				checkHiddenOption 		= currentSectionSlctID.find( '#customize-control-' + value + '_section_hide input:checkbox' ),
				customizeCtrlBg			= 'li[id*="customize-control-' + value + '_bg"]',
				customizeCtrlColor		= 'li[id*="customize-control-' + value + '_color"]',
				customizeCtrlBgImg		= $( '#customize-control-' + value + '_bg_image' ),
				customizeCtrlBgPrx		= $( '#customize-control-' + value + '_bg_parallax_img' ),
				theActivaClass			= 'active',
				addNewSecWidget			= '#bx-section-add-some-' + value,
				addNewWidgetSlct		= $( addNewSecWidget ),
				hiddenSectionClass		= 'bx-hidden-section',
				sectionDescription		= ' .customize-section-description-container',
				bxSectionValue			= 'businessx_section__' + value,
				bxSectionSidebar		= 'sidebar-widgets-section-' + value,
				bxSectionsItems			= $( '#accordion-panel-businessx_panel__sections_items' ),

				styleBtnsTempl			= '<li class="customize-control bx-cz-tabs"><button data-bx-cz-tab-show="color" type="button" class="button bx-cz-tab-colors"><span class="dashicons bx-cz-tc"></span>' + businessx_ext_widgets_customizer[ 'bx_tabs_btb_colors' ] + '</button><button data-bx-cz-tab-show="bg" type="button" class="button bx-cz-tab-background"><span class="dashicons bx-cz-tb"></span>' + businessx_ext_widgets_customizer[ 'bx_tabs_btb_bg' ] + '</button></li>',

				addWidgetBtnsTempl		= '<li class="customize-control bx-add-items-wrap" style="display: list-item"><button type="button" class="button bx-add-items" id="bx-section-add-some-' + value + '"><span class="dashicons bx-add"></span>' + businessx_ext_widgets_customizer[ 'bx_sec_btn_' + value ] + '</button></li>',

				goBackBtnsTempl			= '<li class="customize-control" style="display: list-item"><button type="button" class="button bx-add-items" id="bx-section-go-back-' + value + '"><span class="dashicons bx-edit"></span>' + businessx_ext_widgets_customizer[ 'bx_anw_btn_go_back' ] + '</button></li>';

			if( section.id.indexOf( bxSectionValue ) >= 0 ) {
				// Adds "Tabs" buttons to show/hide color or background options
				// Needs background color control to attach the buttons
				currentSectionSlctID
					.find( '#customize-control-' + value + '_color_background' )
					.before( styleBtnsTempl );

				// The actual "Tabs" action on click, show or hide
				$(document).on('click', currentSectionID + ' .bx-cz-tabs button', function(event) {
					$(this).addClass( theActivaClass );
					var clickedData = $(this).attr( 'data-bx-cz-tab-show' );

					if( clickedData == 'color' ) {
						$(this).parent().find( '.bx-cz-tab-background' ).removeClass( theActivaClass );
						currentSectionSlctID.find( customizeCtrlBg ).hide();
						currentSectionSlctID.find( customizeCtrlColor ).show();

					} else if ( clickedData == 'bg' ) {
						$(this).parent().find( '.bx-cz-tab-colors' ).removeClass( theActivaClass );
						currentSectionSlctID.find( customizeCtrlColor ).hide();
						currentSectionSlctID.find( customizeCtrlBg ).show();

						// Parallax Effect check
						if( checkParallaxOption.is( ':checked' ) ) {
							customizeCtrlBgImg.hide();
							customizeCtrlBgPrx.show();
						} else {
							customizeCtrlBgImg.show();
							customizeCtrlBgPrx.hide();
						}

						checkParallaxOption.on( 'change', function() {
							if( $(this).is( ':checked' ) ){
								customizeCtrlBgImg.hide();
								customizeCtrlBgPrx.show();
							} else {
								customizeCtrlBgImg.show();
								customizeCtrlBgPrx.hide();
							}
						});
					}
				});
			}

			// Hide widgets for specific sidebars
			var widgetsSectionSide = ( currentCheck > 0 ) ?
			'#sub-accordion-section-sidebar-widgets-section-' + value + ' .add-new-widget' :
			'#accordion-section-sidebar-widgets-section-' + value + ' .add-new-widget';

			$(document).on('click', widgetsSectionSide, function(event) {
				$( '#available-widgets-filter' ).addClass( 'bx-search-change' ).find( 'input' ).attr( 'disabled', true );
				$( '#available-widgets-list' ).children().hide();
				$( 'div[id*=widget-tpl-bx-item-' + value +'-]' ).show().addClass( 'bx-display-block' );
				event.preventDefault();
			});

			// Section specific actions, just for adding widgets
			if( section.id == bxSectionValue ) { // Just in the actual section
				var bxSimpleSecName = section.id.replace( 'businessx_section__', '' );

				// Add "Add some ..." button
				if( $.inArray( bxSimpleSecName, businessx_ext_widgets_customizer[ 'bx_sec_item_btn_hide' ] ) === -1 ) {
					$( currentSectionID + sectionDescription ).after( addWidgetBtnsTempl );
				}

				// If section is hidden
				if( checkHiddenOption.is( ':checked' ) ) {
					currentSectionTab.addClass( hiddenSectionClass );
					$( addNewWidgetSlct.selector ).attr( 'disabled', true );
				} else {
					currentSectionTab.removeClass( hiddenSectionClass );
					$( addNewWidgetSlct.selector ).attr( 'disabled', false );
				}
				checkHiddenOption.on( 'change', function() {
					if( $(this).is( ':checked' )  ) {
						currentSectionTab.addClass( hiddenSectionClass );
						$( addNewWidgetSlct.selector ).attr( 'disabled', true );
					} else {
						currentSectionTab.removeClass( hiddenSectionClass );
						$( addNewWidgetSlct.selector ).attr( 'disabled', false );
					}
				});

				// Change the title for adding widgets in sections
				$( '#accordion-section-sidebar-widgets-section-' + bxSimpleSecName ).find('.customize-action').text( businessx_ext_widgets_customizer[ 'bx_add_items_to' ] );

				$(document).on('click', addNewSecWidget, function( event ) {
					wp.customize.section( bxSectionSidebar ).focus();
					bxSectionsItems.addClass( 'bx-display-important' ); // Review
					event.preventDefault();
				});
			}

			if( section.id == bxSectionSidebar ) { // Just in the selected sidebar
				// Move sidebar to panel
				wp.customize.section( bxSectionSidebar ).panel( 'businessx_panel__sections_items' );

				var accordionSec = ( currentCheck > 0 ) ? '#sub-accordion-section-' : '#accordion-section-';

				// Go back action for widgets
				$(document).on('click', accordionSec + section.id + ' .customize-section-title .customize-section-back', function( event ) {
					var newCurrentPanel = section.panel().replace('_items','');

					if( wp.customize.panel( newCurrentPanel ).active() ) { // Check if the parent panel is active first
						wp.customize.section( bxSectionValue ).focus();
					} else {
						alert( businessx_ext_widgets_customizer[ 'bx_wrong_page' ] );
						if( currentCheck > 0 ){
							$('#sub-accordion-section-title_tagline').find('button.customize-section-back').click().click();
						} else {
							$('#accordion-section-title_tagline').find('button.customize-section-back').click().click();
						}
						event.preventDefault();
					}

					bxSectionsItems.removeClass( 'bx-display-important' ); // Review
					$( '#available-widgets-list' ).children().show();
					$( 'div[id*=widget-tpl-bx-item-' + value +'-]' ).hide().removeClass( 'bx-display-block' );
					$( '#available-widgets-filter' ).removeClass( 'bx-search-change' ).find( 'input' ).attr( 'disabled', false );
				});

				// Change button text
				$(document).on( 'click', currentSectionID + ' .bx-add-items', function( event ) {
					$( '.add-new-widget' ).attr( 'data-bx-anw-new-title', businessx_ext_widgets_customizer[ 'bx_anw_btn_' + value ] );
				});
			}

		});
	}); // END wp.customize.section.each

	/* Controls specific JS */
	wp.customize.control.each( function ( control ) {
		$.each( bx_allsections, function( index, value ) {

			// Hide all section colors/background controls on load
			if( control.selector.indexOf( value + '_bg' ) >= 0 || control.selector.indexOf( value + '_color' ) >= 0 ) {
				$( control.selector ).hide();
			}

		});
	}); // END wp.customize.control.each;

	// CHANGES
	if( $('#businessx-frontpage-modal').length > 0 ) {
		window.tb_show( bxext_frontpage_vars.modal_title, '#TB_inline?width=570&height=330&inlineId=businessx-frontpage-modal');
		$('#TB_window').css( 'z-index', '500002').addClass( 'bxext-stp-modal-window' );
		$('#TB_overlay').css( 'z-index', '500001' ).addClass( 'bxext-stp-modal-overlay' );
		$('#TB_overlay.bxext-stp-modal-overlay').off( 'click' );
	}

	$('#bxext-insert-frontpage').on('click', function(event){
		$.ajax({
			url: businessx_ext_widgets_customizer.bx_ajax_url,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'bxext_create_frontpage',
				bxext_create_frontpage: businessx_customizer_js_data.bxext_create_frontpage,
			}
		})
		.done( function( data ) {
			console.log( 'Inserted Front Page' );
			window.tb_remove();
			location.reload(true);
		});
	});

	// Use `.bxext-stp-modal-window #TB_closeWindowButton` to dismiss on X click 
	$('#bxext-dismiss-frontpage').on('click', function(event){
		$.ajax({
			url: businessx_ext_widgets_customizer.bx_ajax_url,
			type: 'post',
			dataType: 'json',
			data: {
				action: 'bxext_dismiss_create_frontpage',
				bxext_create_frontpage: businessx_customizer_js_data.bxext_dismiss_create_frontpage,
			}
		})
		.done( function( data ) {
			console.log( 'Dismissed Front Page Modal' );
			window.tb_remove();
			location.reload(true);
		});
	});

});
