<?php

/**
 * Global Filters from wpzabb settings global values to override defaullts in WPZABB
 *
 * @package Next
 */

if ( !class_exists( 'WPZABBGlobalSettingsOptions' ) ) {
	/**
	* 
	*/
	class WPZABBGlobalSettingsOptions 	{
		public $wpzabb_setting_options;

		function __construct() {

			$this->wpzabb_setting_options = WPZABB_Global_Styling::get_wpzabb_global_settings();

			//FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb_global', true );

			add_filter( 'wpzabb/global/theme_color', array( $this, 'wpzabb_global_theme_color' ) );
			add_filter( 'wpzabb/global/text_color', array( $this, 'wpzabb_global_text_color' ) );
			
			add_filter( 'wpzabb/global/link_color', array( $this, 'wpzabb_global_link_color' ) );
			add_filter( 'wpzabb/global/link_hover_color', array( $this, 'wpzabb_global_link_hover_color' ) );
			
			add_filter( 'wpzabb/global/button_font_family', array( $this, 'wpzabb_global_button_font_family' ) );
			add_filter( 'wpzabb/global/button_font_size', array( $this, 'wpzabb_global_button_font_size' ) );
			add_filter( 'wpzabb/global/button_line_height', array( $this, 'wpzabb_global_button_line_height' ) );
			add_filter( 'wpzabb/global/button_letter_spacing', array( $this, 'wpzabb_global_button_letter_spacing' ) );
			add_filter( 'wpzabb/global/button_text_transform', array( $this, 'wpzabb_global_button_text_transform' ) );


			add_filter( 'wpzabb/global/button_text_color', array( $this, 'wpzabb_global_button_text_color' ) );
			add_filter( 'wpzabb/global/button_text_hover_color', array( $this, 'wpzabb_global_button_text_hover_color' ) );
			add_filter( 'wpzabb/global/button_bg_color', array( $this, 'wpzabb_global_button_bg_color' ) );
			add_filter( 'wpzabb/global/button_bg_hover_color', array( $this, 'wpzabb_global_button_bg_hover_color' ) );
			
			add_filter( 'wpzabb/global/button_border_radius', array( $this, 'wpzabb_global_button_border_radius' ) );
			add_filter( 'wpzabb/global/button_padding', array( $this, 'wpzabb_global_button_padding' ) );
			add_filter( 'wpzabb/global/button_vertical_padding', array( $this, 'wpzabb_global_button_vertical_padding' ) );
			add_filter( 'wpzabb/global/button_horizontal_padding', array( $this, 'wpzabb_global_button_horizontal_padding' ) );
		}

		function wpzabb_get_global_option( $option, $color = false, $opc = false ){
			$wpzabb_setting_options = $this->wpzabb_setting_options;
			
			if ( isset( $wpzabb_setting_options->enable_global ) && ( $wpzabb_setting_options->enable_global == 'no' ) ) {
				return '';
			}elseif ( isset( $wpzabb_setting_options->$option ) && !empty( $wpzabb_setting_options->$option ) ) {
			 	
			 	if ( $color ) {
			 		$wpzabb_setting_options->$option = WPZABB_Helper::wpzabb_colorpicker( $wpzabb_setting_options, $option, $opc );
			 	}
			 	return $wpzabb_setting_options->$option;
			}

			return ''; 
		}
		/**
		 * Theme Color -
		 */
		function wpzabb_global_theme_color() {
			$color = $this->wpzabb_get_global_option( 'theme_color', true );

			return $color;
		}



		/**
		 * Text Color -
		 */
		function wpzabb_global_text_color() {
			$color = $this->wpzabb_get_global_option( 'theme_text_color', true );

			return $color;
		}



		/**
		 * Link Color -
		 */
		function wpzabb_global_link_color() {
			$color = $this->wpzabb_get_global_option( 'theme_link_color', true );

			return $color;
		}



		/**
		 * Link Hover Color -
		 */
		function wpzabb_global_link_hover_color() {
			$color = $this->wpzabb_get_global_option( 'theme_link_hover_color', true );

			return $color;
		}


		/**
		 * Button Font Family
		 */
		function wpzabb_global_button_font_family() {
			//$btn_font_family['family'] = brainstorm_get_option( 'next-button-typography', array( 1 => 'font-family' ), 'Open Sans Condensed' );
			//$btn_font_family['weight'] = brainstorm_get_option( 'next-button-typography', array( 1 => 'font-weight' ), 'bold' );

			return $btn_font_family;
		}

		/**
		 * Button Font Size -
		 */
		function wpzabb_global_button_font_size() {
			$font_size = $this->wpzabb_get_global_option( 'btn_font_size' );
			
			return $font_size;
		}

		/**
		 * Button Line Height -
		 */
		function wpzabb_global_button_line_height() {
			$line_height = $this->wpzabb_get_global_option( 'btn_line_height' );
			
			return $line_height;
		}


		/**
		 * Button Letter Spacing -
		 */
		function wpzabb_global_button_letter_spacing() {
			$letter_spacing = $this->wpzabb_get_global_option( 'btn_letter_spacing' );
			
			return $letter_spacing;
		}


		/**
		 * Button Text Transform -
		 */
		function wpzabb_global_button_text_transform() {
			$text_transform = $this->wpzabb_get_global_option( 'btn_text_transform' );
			
			return $text_transform;
		}


		/**
		 * Button Text Color -
		 */
		function wpzabb_global_button_text_color() {
			$color = $this->wpzabb_get_global_option( 'btn_text_color', true );

			return $color;
		}


		/**
		 * Button Text Hover Color -
		 */
		function wpzabb_global_button_text_hover_color() {
		    $color = $this->wpzabb_get_global_option( 'btn_text_hover_color', true );

		    return $color;
		}


		/**
		 * Button Background Color -
		 */
		function wpzabb_global_button_bg_color() {
		    $color = $this->wpzabb_get_global_option( 'btn_bg_color', true, true );

		    return $color;
		}


		/**
		 * Button Background Hover Color -
		 */
		function wpzabb_global_button_bg_hover_color() {
		    $color = $this->wpzabb_get_global_option( 'btn_bg_hover_color', true, true );

		    return $color;
		}


		/**
		 * Button Border Radius -
		 */
		function wpzabb_global_button_border_radius() {
			$border_radius = $this->wpzabb_get_global_option( 'btn_border_radius' );

			return $border_radius;
		}



		/**
		 * Button Padding -
		 */
		function wpzabb_global_button_padding() {
			$padding = '';

			$v_padding = $this->wpzabb_get_global_option( 'btn_vertical_padding' );
			$h_padding = $this->wpzabb_get_global_option( 'btn_horizontal_padding' );

			if ( $v_padding != '' && $h_padding != '' ) {
				$padding = $v_padding . 'px ' . $h_padding . 'px';
			}

			return $padding;
		}

		function wpzabb_global_button_vertical_padding() {
			$v_padding = '';

			$v_padding = $this->wpzabb_get_global_option( 'btn_vertical_padding' );

			return $v_padding;
		}

		function wpzabb_global_button_horizontal_padding() {
			$h_padding = '';

			$h_padding = $this->wpzabb_get_global_option( 'btn_horizontal_padding' );

			return $h_padding;
		}

	}

	new WPZABBGlobalSettingsOptions();
}


