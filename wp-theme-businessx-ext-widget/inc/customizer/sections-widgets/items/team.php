<?php
/* ------------------------------------------------------------------------- *
 *
 *  Team Section Item
 *  ________________
 *
 *	Adds a Team member - name, avatar, position, description, social buttons
 *	________________
 *
/* ------------------------------------------------------------------------- */

if( ! class_exists( 'Businessx_Extensions_Team_Item' ) ) {
	class Businessx_Extensions_Team_Item extends Businessx_Extensions_Base {

		protected $defaults;
		protected $allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'em' => array(),
			'strong' => array(),
		);
			
		
		/*  Constructor
		/* ------------------------------------ */
		function __construct() {
			
			// Variables
			$this->widget_title = __( 'BX: Team Member' , 'businessx-extensions' );
			$this->widget_id = 'team';
			
			// Settings
			$widget_ops = array( 
				'classname' => 'sec-team-member', 
				'description' => esc_html__( 'Adds a Team member - name, avatar, position, description, social buttons', 'businessx-extensions' ),
				'customize_selective_refresh' => true 
			);

			// Control settings
			$control_ops = array( 'width' => NULL, 'height' => NULL, 'id_base' => 'bx-item-' . $this->widget_id );
			
			// Create the widget
			parent::__construct( 'bx-item-' . $this->widget_id, $this->widget_title, $widget_ops, $control_ops );
			
			// Set some widget defaults
			$this->defaults = array (
				'title'			=> '',
				'position'		=> '',
				'description'	=> '',
				'avatar'		=> '',
				'social_links'	=> '',
			);

		}
		
		
		/*  Front-end display
		/* ------------------------------------ */
		public function widget( $args, $instance ) {
			// Turn $args array into variables.
			extract( $args );

			// $instance Defaults
			$instance_defaults = $this->defaults;
	
			// Parse $instance
			$instance = wp_parse_args( $instance, $instance_defaults );
			
			// Options
			$title 			= apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base ); set_query_var( 'title', $title );
			$position		= ! empty( $instance[ 'position' ] ) ? $instance[ 'position' ] : ''; set_query_var( 'position', $position );
			$description	= ! empty( $instance[ 'description' ] ) ? $instance[ 'description' ] : ''; set_query_var( 'description', $description );
			$avatar			= ! empty( $instance[ 'avatar' ] ) ? $instance[ 'avatar' ] : ''; set_query_var( 'avatar', $avatar );
			$social_links	= ! empty( $instance[ 'social_links' ] ) ? $instance[ 'social_links' ] : array(); set_query_var( 'social_links', $social_links );
			
			// Some variables
			$wid = $this->number; set_query_var( 'wid', $wid ); 
			if ( ! empty( $title ) ) {
				$title_output = $args['before_title'] . $title . $args['after_title']; set_query_var( 'title_output', $title_output );
			}
			$allowed_html = apply_filters( 'businessx_extensions_team_item___allowed_html', $allowed_html = $this->allowed_html ); set_query_var( 'allowed_html', $allowed_html );

			// Add more widget classes
			$css_class = array();
			$css_class[] = 'grid-col';
			$css_class[] = 'grid-1x-col';
			$css_class = apply_filters( 'businessx_extensions_team_item___css_classes', $css_class );
			$css_classes = join(' ', $css_class);
			
			if ( ! empty( $css_classes ) ) {
				if( strpos($args['before_widget'], 'class') === false ) {
					$args[ 'before_widget' ] = str_replace( '>', 'class="'. esc_attr( $css_classes ) . '"', $args[ 'before_widget' ] );
				} else {
					$args[ 'before_widget' ] = str_replace( 'class="', 'class="'. esc_attr( $css_classes ) . ' ', $args[ 'before_widget' ] );
				}
			}

			// Widget template output
			echo $args['before_widget'];
			
				ob_start();
				
				businessx_extensions_get_template_part( 'sections-items/item', 'team' );
				
				echo ob_get_clean();
			
			echo $args['after_widget'];

		}
		
		
		/*  Update Widget
		/* ------------------------------------ */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			
			// Variables
			$allowed_html = apply_filters( 'businessx_extensions_team_item___allowed_html', $allowed_html = $this->allowed_html );
			
			// Fields
			$instance[ 'title' ] 		= sanitize_text_field( $new_instance[ 'title' ] );
			$instance[ 'position' ]		= sanitize_text_field( $new_instance[ 'position' ] );
			$instance[ 'avatar' ] 		= esc_url_raw( $new_instance[ 'avatar' ] );
			
			// Social Links
			$instance[ 'social_links' ] = businessx_sanitize_array_map( 'esc_url_raw', $new_instance[ 'social_links' ] );
			
			// Text Area
			if ( current_user_can('unfiltered_html') ) {
				$instance[ 'description' ] =  businessx_content_filter( $new_instance[ 'description' ], $allowed_html );
			} else {
				$instance[ 'description' ] = wp_kses_post( stripslashes( $new_instance[ 'description' ] ) );
			}
			
			// Return
			return $instance;
		}
		
		
		/*  Widget's Form
		/* ------------------------------------ */
		public function form( $instance ) {
			// Parse $instance
			$instance_defaults = $this->defaults;
			$instance = wp_parse_args( $instance, $instance_defaults );
			extract( $instance, EXTR_SKIP );
			
			// Variables
			$title 			= $instance[ 'title' ];
			$position		= $instance[ 'position' ];
			$description	= $instance[ 'description' ];
			$avatar			= $instance[ 'avatar' ];
			$social_links 	= $instance[ 'social_links' ];
			$md5s 			= substr(md5(rand()), 0, 7);
			
			// Form output
			
			/* Title */
			parent::text_input( $title, 'title', __( 'Member name:', 'businessx-extensions' ), '', 'p-widget-title' );
			
			/* Position */
			parent::text_input( $position, 'position', __( 'Position/Job:', 'businessx-extensions' ) );
			
			/* Excerpt */
			parent::text_area( $description, 'description', __( 'Description:', 'businessx-extensions' ), '', '', __( 'Allowed html tags: <a>, <strong>, <em>.', 'businessx-extensions' ) );
			
			/* Avatar */
			parent::select_image( $avatar, 'avatar', '', __( 'Avatar - suggested size: 250x250px', 'businessx-extensions' ) );
			
			/* Tabs */
			?>
            <div class="bx-widget-tabs bx-bs">
            
            	<div class="bx-wt-tab-wrap bx-bs">
                    <a href="#" class="bx-wt-tab-toggle bx-bs"><?php _e( 'Social Profiles', 'businessx-extensions' ); ?></a>
                    <div class="bx-wt-tab-contents bx-bs">
                    
                        <p><?php _e( 'Enter your social profile URL, for example: http://twitter.com/acosmin/', 'businessx-extensions' ); ?></p>
                        <p><?php _e( 'If the theme has an icon for your social profile, it will display it.', 'businessx-extensions' ); ?></p>
                        
                        <ul class="bx-team-repeatable-profiles <?php echo $md5s; ?> bx-clearfix">
							<?php if ( $social_links ) :
                                    $new_counter = max( array_keys( $social_links ) );
                                    foreach ( $social_links as $key => $value ) : ?>
                                    <li class="bx-team-repeatable-profile bx-clearfix">
                                        <input type="url" name="<?php echo $this->get_field_name( 'social_links' ); ?>[<?php echo absint( $key ); ?>][url]"  value="<?php echo esc_url( $value['url'] ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'social_links' ); ?>[<?php echo absint( $key ); ?>][url]" />
                                        <span class="bx-team-repeatable-helper"><a class="bx-team-remove-profile" href="#"><span class="dashicons dashicons-trash"></span></a></span>
                                        <span class="bx-team-repeatable-helper"><span class="dashicons dashicons-sort"></span></span>
                                    </li>
								<?php 
									endforeach;
                                else : 
									$new_counter = 0; ?>
                                    <li class="bx-team-repeatable-profile bx-clearfix">
                                        <input type="url" name="<?php echo $this->get_field_name( 'social_links' ); ?>[0][url]"  value="" class="widefat" id="<?php echo $this->get_field_id( 'social_links' ); ?>[0][url]" />
                                        <span class="bx-team-repeatable-helper"><a class="bx-team-remove-profile" href="#"><span class="dashicons dashicons-trash"></span></a></span>
                                        <span class="bx-team-repeatable-helper"><span class="dashicons dashicons-sort"></span></span>
                                    </li>
							<?php endif; ?>
                        </ul>
                        
                        <p><a id="bx-team-add-profile-<?php echo $md5s; ?>" class="button bx-team-add-profile" href="#"><?php _e( 'Add another profile', 'businessx-extensions' ); ?></a></p>
                        
                    </div>
            	</div>
                    
			</div> <!-- Tabs -->
                
			<script>
				jQuery(document).ready(function ($) {
			
					var counter = <?php echo absint( $new_counter ); ?>;
			
					$('#bx-team-add-profile-<?php echo $md5s; ?>').on('click', function (event) {
						var currentParent = $(this).parents('.widget-content');
						var new_counter = 1 + counter++;
			
						var id_input = '<li class="bx-team-repeatable-profile bx-clearfix">'
								+ '<input type="url" class="widefat" name="<?php echo $this->get_field_name( 'social_links' ); ?>[' + new_counter + '][url]" id="<?php echo $this->get_field_id( 'social_links' ); ?>[' + new_counter + '][url]" />'
								+ '<span class="bx-team-repeatable-helper"><a class="bx-team-remove-profile" href="#"><span class="dashicons dashicons-trash"></span></a></span>'
								+ '<span class="bx-team-repeatable-helper"><span class="dashicons dashicons-sort"></span></span></li>';
			
						$(id_input).appendTo($(this).parents().find('.widget-content ul.<?php echo $md5s; ?>'));
						
						currentParent.find('.p-widget-title input').trigger('change');
						event.preventDefault();
					});
			
					$('.bx-team-repeatable-profiles').on("click", ".bx-team-remove-profile", function (event) {
						var currentParent = $(this).parents('.widget-content');
						$(this).parents('li.bx-team-repeatable-profile').remove();
						currentParent.find('.p-widget-title input').trigger('change');
						event.preventDefault();
					})
			
					$('.bx-team-repeatable-profiles').sortable({
						helper: 'clone',
						items: '> li.bx-team-repeatable-profile',
						cursor: 'move',
						update: function( event, ui ) { ui.item.find('input').trigger('change'); }
					});
			
				});
			</script>
            <?php
		}
		
		
	} // Businessx_Extensions_Team_Item .END
	
	// Register this widget
	register_widget( 'Businessx_Extensions_Team_Item' );
	
}