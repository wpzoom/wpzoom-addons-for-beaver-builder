<?php

/**
 * WPZABB_UI_Panels setup
 *
 * @since 1.0
 */

class WPZABB_UI_Panels {

	/**
	*  Constructor
	*/
	public function __construct() {
		
		//	Render JS & CSS
		add_filter( 'fl_builder_render_css', array( $this, 'wpzabb_render_css' ), 10, 3 );
		add_filter( 'fl_builder_render_js', array( $this, 'wpzabb_render_js' ), 10, 3 );

		$this->init();
	}

	public function init() {
		add_filter( 'fl_builder_template_selector_data', array( $this, 'wpzabb_fl_builder_template_selector_data' ), 10, 2 );
	}

	/**
	 * 	Filter Templates
	 * 	Add additional information in templates array
	 *
	 * @return array
	 */
	function wpzabb_fl_builder_template_selector_data( $template_data, $template ) {
		$template_data['tags']   = isset( $template->tags ) ? $template->tags : array();
		$template_data['author'] = isset( $template->author ) ? $template->author : '';
		return $template_data;
	}

	/**
	 * Render Global wpzabb-layout-builder js
	 */
	function wpzabb_render_js($js, $nodes, $global_settings) {
		$temp = file_get_contents(BB_WPZOOM_ADDON_DIR . 'assets/js/wpzabb-frontend.js') . $js;
		$js = $temp;
		return $js;
	}

	/**
	 * Render Global wpzabb-layout-builder css
	 */
	function wpzabb_render_css($css, $nodes, $global_settings) {

	    $css .= file_get_contents(BB_WPZOOM_ADDON_DIR . 'assets/css/wpzabb-frontend.css');

		return $css;
	}

}

new WPZABB_UI_Panels();
