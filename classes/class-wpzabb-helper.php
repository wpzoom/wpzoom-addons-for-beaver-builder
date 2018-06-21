<?php

/**
 * Custom modules
 */
if( !class_exists( "WPZOOM_BB_Addon_Pack_Helper" ) ) {
	
	class WPZOOM_BB_Addon_Pack_Helper {

		/*
		* Constructor function that initializes required actions and hooks
		* @Since 1.0
		*/
		function __construct() {

			$this->set_constants();
		}

		function set_constants() {
			$branding         = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding();
			$branding_name    = 'WPZABB';
			$branding_modules = __('WPZOOM Modules', 'wpzabb');

			//	Branding - %s
			if (
				is_array( $branding ) &&
				array_key_exists( 'wpzabb-plugin-short-name', $branding ) &&
				$branding['wpzabb-plugin-short-name'] != ''
			) {
				$branding_name = $branding['wpzabb-plugin-short-name'];
			}

			//	Branding - %s Modules
			if ( $branding_name != 'WPZABB') {
				$branding_modules = sprintf( __( '%s Modules', 'wpzabb' ), $branding_name );
			}

			define( 'WPZABB_PREFIX', $branding_name );
			define( 'WPZABB_CAT', $branding_modules );			
		}
		
		static public function module_cat( $cat = '' ) {
		    return ( class_exists( 'FLBuilderUIContentPanel' ) && ! empty($cat) ) ? $cat : WPZABB_CAT;
		}

		static public function get_builder_wpzabb() {
			$wpzabb = WPZABB_Init::$wpzabb_options['fl_builder_wpzabb'];

			$defaults = array(
				'load_panels'			=> 1,
				'wpzabb-live-preview'		=> 1,
				'load_templates' 		=> 1,
				'wpzabb-google-map-api'	=> '',
				'wpzabb-colorpicker'		=> 1,
				'wpzabb-row-separator'    => 1
			);

			//	if empty add all defaults
			if( empty( $wpzabb ) ) {
				$wpzabb = $defaults;
			} else {

				//	add new key
				foreach( $defaults as $key => $value ) {
					if( is_array( $wpzabb ) && !array_key_exists( $key, $wpzabb ) ) {
						$wpzabb[$key] = $value;
					} else {
						$wpzabb = wp_parse_args( $wpzabb, $defaults );
					}
				}
			}

			return apply_filters( 'wpzabb_get_builder_wpzabb', $wpzabb );
		}

		static public function get_builder_wpzabb_branding( $request_key = '' ) {
			$wpzabb = WPZABB_Init::$wpzabb_options['fl_builder_wpzabb_branding'];

			$defaults = array(
				'wpzabb-enable-template-cloud' => 1,
			);


			//	if empty add all defaults
			if( empty( $wpzabb ) ) {
				$wpzabb = $defaults;
			} else {

				//	add new key
				foreach( $defaults as $key => $value ) {
					if( is_array( $wpzabb ) && !array_key_exists( $key, $wpzabb ) ) {
						$wpzabb[$key] = $value;
					} else {
						$wpzabb = wp_parse_args( $wpzabb, $defaults );
					}
				}
			}

			$wpzabb = apply_filters( 'wpzabb_get_builder_wpzabb_branding', $wpzabb );
			
			/**
			 * Return specific requested branding value
			 */
			if( !empty( $request_key ) ) {
				if( is_array($wpzabb) ) {
					$wpzabb = ( array_key_exists( $request_key, $wpzabb ) ) ? $wpzabb[ $request_key ] : '';
				}				
			}

			return $wpzabb;
		}

		static public function get_all_modules() {
			$modules_array = array(
				'wpzabb-spacer-gap'               	=> 'Spacer / Gap',
				'wpzabb-separator'          => 'Simple Separator',
				'wpzabb-image-icon'         => 'Image / Icon',
				'wpzabb-image-box'         	=> 'Image Box',
				'wpzabb-button'             => 'Button',
				'wpzabb-testimonials'       => 'Testimonials',
				'wpzabb-team-members'       => 'Team Members',
				'wpzabb-heading'            => 'Heading',
				'wpzabb-map'            	=> 'Map'
			);
			
			return $modules_array;
		}

		static public function get_builder_wpzabb_modules() {
			$wpzabb 			= WPZABB_Init::$wpzabb_options['fl_builder_wpzabb_modules'];
			$all_modules 		= self::get_all_modules();
			$is_all_modules 	= true;

			/* Delte below after test */
			//$wpzabb 			= self::get_all_modules();
			/* Delte above after test */

			//	if empty add all defaults
			if( empty( $wpzabb ) ) {
				$wpzabb 		= self::get_all_modules();
				$wpzabb['all'] 	= 'all';
			} else {
				if ( !isset( $wpzabb['unset_all'] ) ) {
					//	add new key
					foreach( $all_modules as $key => $value ) {
						if( is_array( $wpzabb ) && !array_key_exists( $key, $wpzabb ) ) {
							$wpzabb[$key] = $key;
							// $is_all_modules = false;
							// break;
						}
					}
				}
			}

			if ( $is_all_modules == false && isset( $wpzabb['all'] ) ) {
				unset( $wpzabb['all'] );
			}

			return apply_filters( 'wpzabb_get_builder_wpzabb_modules', $wpzabb );
		}
		
		/**
		 *	Template status
		 *
		 *	Return the status of pages, sections, presets or all templates. Default: all
		 *	@return boolean
		 */
		public static function is_templates_exist( $templates_type = 'all' ) {

			$templates       = get_site_option( '_wpzabb_cloud_templats', false );
			$exist_templates = array(
				'page-templates' => 0,
				'sections'       => 0,
				'presets'        => 0
			);

			if( is_array( $templates ) && count( $templates ) > 0 ) {
				foreach( $templates as $type => $type_templates ) {

					//	Individual type array - [page-templates], [layout] or [row]
					if( $type_templates ) {
						foreach( $type_templates as $template_id => $template_data ) {
							
							/**
							 *	Check [status] & [dat_url_local] exist
							 */
							if(
								isset( $template_data['status'] ) && $template_data['status'] == true &&
								isset( $template_data['dat_url_local'] ) && !empty( $template_data['dat_url_local'] )
							) {
								$exist_templates[$type] = ( count( $exist_templates[$type] ) + 1 );
							}
						}
					}
				}
			}

			switch ( $templates_type ) {
				case 'page-templates':
								$_templates_exist = ( $exist_templates['page-templates'] >= 1 ) ? true : false;
				break;

				case 'sections':
								$_templates_exist = ( $exist_templates['sections'] >= 1 ) ? true : false;
				break;

				case 'presets':
								$_templates_exist = ( $exist_templates['presets'] >= 1 ) ? true : false;
				break;

				case 'all':
					default:
								$_templates_exist = ( $exist_templates['page-templates'] >= 1 || $exist_templates['sections'] >= 1 || $exist_templates['presets'] >= 1 ) ? true : false;
				break;
			}

			return $_templates_exist;
		}

		/**
		 *	Get link rel attribute
		 *
	 	 *  @since 1.6.1
		 *	@return string
		 */
		static public function get_link_rel( $target, $is_nofollow = 0, $echo = 0 )  {

			$attr = '';
			if( '_blank' == $target ) {
				$attr.= 'noopener';
			}

			if( 1 == $is_nofollow ) {
				$attr.= ' nofollow';
			}

			if( '' == $attr ) {
				return;
			}

			$attr = trim($attr);
			if ( ! $echo  ) {
				return 'rel="'.$attr.'"';
			}
			echo 'rel="'.$attr.'"';
		}
		
	}
	new WPZOOM_BB_Addon_Pack_Helper();
}
