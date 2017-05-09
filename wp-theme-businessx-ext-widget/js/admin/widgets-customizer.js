/*
	This JS files loads in the Customizer and Widgets page.
	It's used for Sections widgets.
	It adds tabs, media uploader, select options, icons search etc...
*/

(function( $ ) {
	$(document).ready(function () {
		
		// Hide sidebars/widgets - widgets.php
		if( $('body').hasClass('widgets-php') ) {
			$('div[id*=section-]').each(function(index,value) {
				$(this).parent('.widgets-holder-wrap').hide()
			});
			
			$(document).on('click', '#available-widgets .widget .widget-top', function(event) {
				var list = '.widgets-chooser > ul > li', 
					current = $(this).parent('.widget').find( list );
					

				current.each(function(index, element) {
					if( $(this).text().indexOf('Section') >= 0 ) { $(this).remove(); }
                })
				
				var newlist = $( list );
				newlist.first().addClass('widgets-chooser-selected');
			});
			
			$('#available-widgets .widget').each(function(index,value) {
				var thisID = $(this).attr('id');
				if( thisID.indexOf('bx-item') >= 0 ) { $(this).remove() }
			});
		}
		
		// Widget Tabs
		$(document).on('click', 'a.bx-wt-tab-toggle', function(event) {
			var bx_current = $(this);
			var bx_widgetID = bx_current.parents('.widget').attr('id');
			var bx_tab_wrap = bx_current.parents('div.bx-widget-tabs');
			var bx_ative = bx_tab_wrap.find('.bx-wt-active-link').length;
			
			bx_tab_wrap.find('a.bx-wt-tab-toggle').addClass('bx-tab-not-active');
			bx_tab_wrap.find('div.bx-wt-tab-contents').addClass('bx-tab-not-active');
			
			bx_current.removeClass('bx-tab-not-active');
			bx_current.next('div').removeClass('bx-tab-not-active');
			
			if( bx_ative >= 1 ) {	
				bx_tab_wrap.find('a.bx-wt-tab-toggle.bx-tab-not-active').removeClass('bx-wt-active-link');
				bx_tab_wrap.find('div.bx-wt-tab-contents.bx-tab-not-active').removeClass('bx-wt-active-tab');
			}
			
			bx_current.toggleClass('bx-wt-active-link');
			bx_current.next('div').toggleClass('bx-wt-active-tab');
			
			event.preventDefault();
		});

		// Select type	
		$(document).on('change', '.bx-select-type', function(event) {
			event.preventDefault();
			
			var bx_widget 		= $(this).parents('.widget');
			var bx_select_class	= $(this).data('bx-select-class');
			var bx_select 		= bx_widget.find('[class*='+bx_select_class+']');
			var bx_elements 	= $.makeArray( bx_select );
			var bx_selected 	= $(this).val();
			
			$.each(bx_elements, function( index, value ) {
				if( value.className == bx_selected ) {
					bx_select.hide();
					bx_widget.find('.' + bx_selected ).show();
				}
			});
		});

        $(document).on('click', '.bx-iu-image-upload', function(event) {
            event.preventDefault();
            var clicked = $(this).closest('div');
            var custom_uploader = wp.media({ 
				multiple: false
            })
            .on('select', function() {
				var attachment = custom_uploader.state().get('selection').first(), 
				sizes = attachment.get( 'sizes' ),
				size, full_size;
				
				if ( sizes ) {
					size = sizes['post-thumbnail'] || sizes.medium; 
					full_size = sizes['post-thumbnail'] || sizes.full;
				}
				
				size = size || attachment.toJSON();
				full_size = full_size || attachment.toJSON();

				clicked.find('.bx-iu-image').attr('src', size.url).css('display','block');
                clicked.find('.bx-iu-image-url').val(full_size.url).trigger('change');
                clicked.find('.bx-iu-image-upload').css('display','none');
                clicked.find('.bx-iu-image-remove').css('display','inline-block');
            }) 
            .open();
        });


        $(document).on('click', '.bx-iu-image-remove', function(event) {
			event.preventDefault();
            $(this).closest('div').find('.bx-iu-image').removeAttr('src').css('display','none');
            $(this).closest('div').find('.bx-iu-image-url').val('').trigger('change');
            $(this).closest('div').find('.bx-iu-image-remove').css('display','none');
            $(this).closest('div').find('.bx-iu-image-upload').css('display','inline-block');
        });
		
		$(document).on('click', 'div.widget[id*=bx-item] .widget-title, div.widget[id*=bx-item] .widget-action', function (event) {
			bx_widgetInit($(this).parents('.widget[id*=bx-item]'));
			event.preventDefault();
        });

		// If a widget is added do this
		$(document).on( 'widget-added', function(event, bx_widgetID) {
			if ( bx_widgetID.is('[id*=bx-item]' )) {
                bx_widgetInit(bx_widgetID);
            }
			event.preventDefault();
		});
		
		// If a widget is updated do this
		$(document).on('widget-updated', function(event, bx_widgetID) {
            if (bx_widgetID.is('[id*=bx-item]')) {
                bx_widgetInit( bx_widgetID );
            }
			event.preventDefault();
        });
		
		// Initialise widget function
		function bx_widgetInit( bx_widgetID ) {
			var thisWidgetID = bx_widgetID;
			var fieldCurrent = thisWidgetID.find( 'input.bx-is-autocomplete' );
			
			// Color Picker
			bx_widgetID.find('.bx-widget-color-piker').wpColorPicker({
				/*change: _.throttle(function() {
					if( $('body').hasClass('wp-customizer') ) { $(this).trigger( 'change' ); }
				}, 2000),*/
				change: _.throttle(function() {
					if( $('body').hasClass('wp-customizer') ) { $(this).trigger( 'change' ); }
				}, 200),
				palettes: false
			});
			
			// Icons Autocomplet
			fieldCurrent.autocomplete({
      			source: businessx_ext_widgets_customizer['bx_icons_array'],
				select: function( event, ui ) {
					var bx_icon = $(this).parent().find('.bx-is-autocomplete-icon i')
					bx_icon.removeAttr('class').addClass('fa ' +  ui.item.value);
					$(this).trigger('change');	
				}
    		});
		}
		
		
	});	
})(jQuery);