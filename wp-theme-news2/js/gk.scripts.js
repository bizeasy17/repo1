/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
(function () {
    "use strict";

    // IE checker
    function gkIsIE() {
        var myNav = navigator.userAgent.toLowerCase();
        return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
    }

    jQuery.cookie = function (key, value, options) {

        // key and at least value given, set cookie...
        if (arguments.length > 1 && String(value) !== "[object Object]") {
            options = jQuery.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires,
                    t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=',
                options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || {};
        var result, decode = options.raw ? function (s) {
                return s;
            } : decodeURIComponent;
        return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
    };

    // Array filter
    if (!Array.prototype.filter) {
        Array.prototype.filter = function (fun /*, thisp */ ) {
            if (this === null) {
                throw new TypeError();
            }

            var t = Object(this);
            var len = t.length >>> 0;
            if (typeof fun !== "function") {
                throw new TypeError();
            }

            var res = [];
            var thisp = arguments[1];

            for (var i = 0; i < len; i++) {
                if (i in t) {
                    var val = t[i]; // in case fun mutates this
                    if (fun.call(thisp, val, i, t))
                        res.push(val);
                }
            }

            return res;
        };
    }

    /**
     *
     * Template scripts
     *
     **/

    // onDOMLoadedContent event
    jQuery(document).ready(function () {
        // Back to Top Scroll
        jQuery('#gk-back-to-top').click(function () {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 400);
            return false;
        });
        
        // Thickbox use
        jQuery(document).ready(function () {
            if (typeof tb_init !== "undefined") {
                tb_init('div.wp-caption a'); //pass where to apply thickbox
            }
        });
        
        jQuery(document).ready(function() {
        	jQuery('.gk-video-wrap').fitVids();	
        });
        
        jQuery(document).ready(function() {
        	// NSP suffix showcase
        	jQuery('.showcase').each(function(i, wrapper) {
        		wrapper = jQuery(wrapper);
        		if(wrapper.find('.gk-nsp-image-wrapper').length > 0 && wrapper.find('.gk-nsp-header').length > 0) {
        			wrapper.find('.gk-nsp-art').each(function(i, art) {
        				jQuery(art).find('.gk-nsp-image-wrapper').append(jQuery(art).find('.gk-nsp-header'));
        			});
        		}
        	});
        });
        // style area
        if (jQuery('#gk-style-area')) {
            jQuery('#gk-style-area div').each(function () {
                jQuery(this).find('a').each(function () {
                    jQuery(this).click(function (e) {
                        e.stopPropagation();
                        e.preventDefault();
                        changeStyle(jQuery(this).attr('href').replace('#', ''));
                    });
                });
            });
        }
        // Function to change styles

        function changeStyle(style) {
            var file = $GK_TMPL_URL + '/css/' + style;
            jQuery('head').append('<link rel="stylesheet" href="' + file + '" type="text/css" />');
            jQuery.cookie($GK_TMPL_NAME + '_style', style, {
                expires: 365,
                path: '/'
            });
        }

        // Responsive tables
        jQuery('article section table').each(function (i, table) {
            table = jQuery(table);

            if (table.hasClass('hosting')) {
                var heads = table.find('thead th:not(:first-child)');
            } else {
                var heads = table.find('thead th');
            }
            var cells = table.find('tbody td');
            var heads_amount = heads.length;
            // if there are the thead cells
            if (heads_amount) {
                var cells_len = cells.length;
                for (var j = 0; j < cells_len; j++) {
                    var head_content = jQuery(heads.get(j % heads_amount)).text();
                    jQuery(cells.get(j)).html('<span class="gk-table-label">' + head_content + '</span>' + jQuery(cells.get(j)).html());
                }
            }
        });
        
        // NSP pagination in highlights suffix
        if(jQuery('.gk-nsp').length > 0) {
        	jQuery('.gk-nsp').each(function(i, mod) {
        		mod = jQuery(mod);
        		var pagination = mod.find('.gk-nsp-arts-nav');
        		if(mod.hasClass('highlights') && pagination.length > 0) {
        			var title_area = new jQuery('<div class="nsp-title-area"></div>');
        			pagination.append(title_area);
        			var current_art = 1;
        			var titles = mod.find('.gk-nsp-art .gk-nsp-header');
        			title_area.append(jQuery(titles[1]).clone());
        			// prev button event
        			pagination.find('.gk-nsp-prev').click( function() {
        				current_art = (current_art > 0) ? current_art - 1 : titles.length - 1;
        				title_area.html('');
        				title_area.append(jQuery(titles[current_art]).clone());
        			});
        			// next button event
        			pagination.find('.gk-nsp-next').click(function() {
        				current_art = (current_art < titles.length - 1) ? current_art + 1 : 0;
        				title_area.html('');
        				title_area.append(jQuery(titles[current_art]).clone());
        			});
        		}
        	});
        }
		
        
        var resize_boundary = parseInt(jQuery('body').attr('data-tablet-width'), 10);
    	var small_resize_boundary = parseInt(jQuery('body').attr('data-tablet-small-width'), 10);
    	var mobile_resize_boundary = parseInt(jQuery('body').attr('data-mobile-width'), 10);
    	
    	var col_page_content = jQuery('.gk-page-wrap');
    	var col_sidebar_left = jQuery('#gk-sidebar-left');
    	var col_sidebar_right = jQuery('#gk-sidebar-right');
    	var col_inset = jQuery('#gk-inset');
    	var col_content = jQuery('#gk-content-wrap').children('div');
    	var col_areas = jQuery('.gk-equal-columns');
    
    	var columnResize = function() {
    		col_page_content.css('min-height', '0');
    		col_content.css('min-height', '0');
    		if(col_sidebar_left) col_sidebar_left.css('min-height', '0');
    		if(col_sidebar_right) col_sidebar_right.css('min-height', '0');
    		if(col_inset) col_inset.css('min-height', '0');
    		
    		// Desktop devices
    		if(jQuery(window).width() > resize_boundary) {
    			var col_page_content_h = col_page_content.outerHeight();
    			var col_content_h = col_content.outerHeight();
    			var col_sidebar_left_h = (col_sidebar_left) ? col_sidebar_left.outerHeight() : 0;
    			var col_sidebar_right_h = (col_sidebar_right) ? col_sidebar_right.outerHeight() : 0;
    			var col_inset_h = (col_inset) ? col_inset.outerHeight() : 0;
    			
    			// check main columns - left, right sidebars
    			var max_sidebars = col_page_content_h;
    			if(max_sidebars < col_sidebar_left_h) max_sidebars = col_sidebar_left_h;
    			if(max_sidebars < col_sidebar_right_h) max_sidebars = col_sidebar_right_h;
    			col_page_content.css('min-height', max_sidebars + "px");
    			if(col_sidebar_left) col_sidebar_left.css('min-height', (max_sidebars + 1) + "px");
    			if(col_sidebar_right) col_sidebar_right.css('min-height', max_sidebars + "px");
    			// check content columns - content + inset
    			var max_content = col_content_h;
    			if(max_content < col_inset_h) max_content = col_inset_h;
    			col_content.css('min-height', max_content + "px");
    			if(col_inset) col_inset.css('min-height', max_content + "px");
    			
    			col_areas.each(function(i, area) {
    				area = jQuery(area);
    				var cols = area.children('div');
    				if(cols.length > 1) {
    					cols.css('min-height', '0');
    					var max_h = 0;
    					
    					cols.each(function(i, column) {
    						column = jQuery(column);
    						if(column.outerHeight() > max_h) {
    							max_h = column.outerHeight();
    						}
    					});
    					
    					cols.css('min-height', max_h + "px");
    				}
    			});
    		// Tablet devices
    		} else if(jQuery(window).width() <= resize_boundary && jQuery(window).width() > small_resize_boundary) {
    			var col_page_content_h = col_page_content.outerHeight();
    			var col_content_h = col_content.outerHeight();
    			var col_sidebar_left_h = (col_sidebar_left) ? col_sidebar_left.outerHeight() : 0;
    			var col_inset_h = (col_inset) ? col_inset.outerHeight() : 0;
    			
    			// check main columns - left, right sidebars
    			var max_sidebars = col_page_content_h;
    			if(max_sidebars < col_sidebar_left_h) max_sidebars = col_sidebar_left_h;
    			col_page_content.css('min-height', max_sidebars + "px");
    			if(col_sidebar_left) col_sidebar_left.css('min-height', (max_sidebars + 1) + "px");
    			// check content columns - content + inset
    			var max_content = col_content_h;
    			if(max_content < col_inset_h) max_content = col_inset_h;
    			col_content.css('min-height', max_content + "px");
    			if(col_inset) col_inset.css('min-height', max_content + "px");
    			
    			col_areas.each(function(i, area) {
    				area = jQuery(area);
    				var cols = area.children('div');
    				if(cols.length > 1) {
    					cols.css('min-height', '0');
    					var max_h = 0;
    					
    					cols.each(function(i, column) {
    						column = jQuery(column);
    						if(column.outerHeight() > max_h) {
    							max_h = column.outerHeight();
    						}
    					});
    					
    					cols.css('min-height', max_h + "px");
    				}
    			});
    		// Small tablet devices
    		} else if(jQuery(window).width() <= small_resize_boundary && jQuery(window).width() > mobile_resize_boundary) {
    			col_areas.each(function(i, area) {
    				area = jQuery(area);
    				var cols = area.children('div');
    				if(cols.length > 1) {
    					cols.css('min-height', '0');
    					var max_h = 0;
    					
    					cols.each(function(i, column) {
    						column = jQuery(column);
    						if(column.outerHeight() > max_h) {
    							max_h = column.outerHeight();
    						}
    					});
    					
    					cols.css('min-height', max_h + "px");
    				}
    			});
    		}
    	}
    	
    	columnResize();
    	
    	setTimeout(function() { columnResize(); }, 1000);
    	setTimeout(function() { columnResize(); }, 2500);
    	setTimeout(function() { columnResize(); }, 5000);
    	setTimeout(function() { columnResize(); }, 10000);
    	setTimeout(function() { columnResize(); }, 15000);
    
    	jQuery(window).resize(function() {
    		columnResize();
    	});
                
    });
    
    // fixed menu
    //
    // create container for the fixed menu
    var menuWrap = new jQuery('<div id="gk-fixed-menu"></div>');
    
    menuWrap.html('<div class="gk-page"></div>');
    jQuery('body').append(menuWrap);
    var menupos = jQuery('#gk-top-nav').position().top + 80;
    var menucontent = jQuery('#gk-top-nav');
    var logo = jQuery('#gk-logo');
    var toolbar = jQuery('#gk-toolbar');

    if(logo.length > 0) {
    	menuWrap.find(' > div').prepend(logo.clone(true).attr('id', 'gk-logo-small'));
    }

    jQuery(window).scroll(function() {
        var currentPosition = jQuery(window).scrollTop();
       
        if(currentPosition > menupos && !menuWrap.hasClass('active')) {
            menuWrap.addClass('active');
            menuWrap.find('> div').append(menucontent);
        } else if(currentPosition < menupos && menuWrap.hasClass('active')) {
            menuWrap.removeClass('active');
            if(toolbar.length > 0) {
            	toolbar.before(menucontent);
            } else {
            	jQuery('#gk-top-bar').append(menucontent);
            }
        }
    });
  
 })();