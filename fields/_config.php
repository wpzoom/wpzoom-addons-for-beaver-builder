<?php

/*
 * Custom Fields Config File
 * Description: This is custom fields config file. Require your custom field's "main" file here.
 *
*/

// require_once 'wpzabb-fields.php';

require_once 'wpzabb-gradient/wpzabb-gradient.php';

if( !class_exists('WPZABB_Custom_Field_Scripts') ) {
	class WPZABB_Custom_Field_Scripts
	{
		function __construct() {	
			add_action( 'wp_enqueue_scripts', array( $this, 'custom_field_scripts' ) );
		}
	

		function custom_field_scripts() {
		    if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

		        /* wpzabb-gradient field */
				wp_enqueue_style( 'wpzabb-gradient', BB_WPZOOM_ADDON_URL . 'fields/wpzabb-gradient/css/wpzabb-gradient.css', array(), '' );
				wp_enqueue_script( 'wpzabb-gradient', BB_WPZOOM_ADDON_URL . 'fields/wpzabb-gradient/js/wpzabb-gradient.js', array(), '', true );
			}
		}
	}

	$WPZABB_Custom_Field_Scripts = new WPZABB_Custom_Field_Scripts();
}