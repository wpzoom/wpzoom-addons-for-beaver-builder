<?php

/**
 * WPZABB initial setup
 *
 * @since 1.1.0.4
 */
class WPZABB_Init {

	public static $wpzabb_options;

	/**
	*  Constructor
	*/

	public function __construct() {

		//register_activation_hook( __FILE__, array( __CLASS__, '::reset' ) );

		if ( class_exists( 'FLBuilder' ) ) {

			/**
			 *	For Performance
			 *	Set WPZABB static object to store data from database.
			 */
			self::set_wpzabb_options();

			add_filter( 'fl_builder_settings_form_defaults', array( $this, 'wpzabb_global_settings_form_defaults' ), 10, 2 );	
			// Load all the required files of wpzoom-bb-addon-pack
			self::includes();
			add_action( 'init', array( $this, 'init' ) );			

			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 100 );

			$basename = plugin_basename( BB_WPZOOM_ADDON_FILE );
			// Filters
			add_filter( 'plugin_action_links_' . $basename, array( $this, 'wpzabb_render_plugin_action_links' ) );

		} else {

			// disable WPZABB activation ntices in admin panel
			define( 'WPZOOM_WPZABB_NOTICES', false );

			// Display admin notice for activating beaver builder
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'network_admin_notices', array( $this, 'admin_notices' ) );
		}

	}

	function wpzabb_render_plugin_action_links( $actions ) {

		return $actions;
	}

	function includes() {

		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-helper.php';

 		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-cloud-templates.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-admin-settings.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-admin-settings-multisite.php';

		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-global-settings.php';

		require_once BB_WPZOOM_ADDON_DIR . 'classes/wpzabb-global-functions.php';

		// Attachment Fields

		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-attachment.php';

		//	fields
		require_once BB_WPZOOM_ADDON_DIR . 'fields/_config.php';

		require_once BB_WPZOOM_ADDON_DIR . 'classes/wpzabb-global-settings-form.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/helper.php';
		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-ui-panel.php';

		// Load the appropriate text-domain
		$this->load_plugin_textdomain();

	}

	/**
	*	For Performance
	*	Set WPZABB static object to store data from database.
	*/
	static function set_wpzabb_options() {
		self::$wpzabb_options = array(
			'fl_builder_wpzabb'          => FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb', true ),
			'fl_builder_wpzabb_branding' => FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb_branding', false ),
			'wpzabb_global_settings'     => get_option('_wpzabb_global_settings'),

			'fl_builder_wpzabb_modules' => FLBuilderModel::get_admin_settings_option( '_fl_builder_wpzabb_modules', false ),
		);
	}

	function wpzabb_global_settings_form_defaults( $defaults, $form_type ) {

		if ( class_exists( 'FLCustomizer' ) && 'wpzabb-global' == $form_type ) {

        	$defaults->enable_global = 'no';
	    }

	    return $defaults; // Must be returned!
	}

	function init() {
		
		if ( apply_filters( 'wpzabb_global_support', true ) && class_exists( 'FLBuilderAJAX' ) ) {
			require_once BB_WPZOOM_ADDON_DIR . 'classes/wpzabb-global-settings.php';
			require_once BB_WPZOOM_ADDON_DIR . 'classes/wpzabb-global-integration.php';
		}


		if ( class_exists( 'FLCustomizer' ) ) {
			$wpzabb_global_style = WPZABB_Global_Styling::get_wpzabb_global_settings();
			
			if ( ( isset( $wpzabb_global_style->enable_global ) && ( $wpzabb_global_style->enable_global == 'no' ) ) ) {
				require_once BB_WPZOOM_ADDON_DIR . 'classes/wpzabb-bbtheme-global-integration.php';
			}
		}

		//	Nested forms
		require_once BB_WPZOOM_ADDON_DIR . 'objects/fl-nested-form-button.php';

		require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-iconfonts.php';
		//require_once BB_WPZOOM_ADDON_DIR . 'classes/class-wpzabb-model-helper.php';

		// Ultimate Modules
		$this->load_modules();
	}

	function load_plugin_textdomain() {
		//Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wpzabb' );

		//Setup paths to current locale file
		$mofile_global = trailingslashit( WP_LANG_DIR ) . 'plugins/wpzoom-bb-addon-pack/' . $locale . '.mo';
		$mofile_local  = trailingslashit( BB_WPZOOM_ADDON_DIR ) . 'languages/' . $locale . '.mo';

		if ( file_exists( $mofile_global ) ) {
			//Look in global /wp-content/languages/plugins/wpzoom-bb-addon-pack/ folder
			return load_textdomain( 'wpzabb', $mofile_global );
		}
		else if ( file_exists( $mofile_local ) ) {
			//Look in local /wp-content/plugins/wpzoom-bb-addon-pack/languages/ folder
			return load_textdomain( 'wpzabb', $mofile_local );
		} 

		//Nothing found
		return false;
	}

	function load_scripts() {

		if( FLBuilderModel::is_builder_active() ) {
			
			wp_enqueue_style( 'wpzabb-builder-css', BB_WPZOOM_ADDON_URL . 'assets/css/wpzabb-builder.css', array() );
			wp_enqueue_script('wpzabb-builder-js',  BB_WPZOOM_ADDON_URL . 'assets/js/wpzabb-builder.js', array('jquery'), '', true);

			if ( apply_filters( 'wpzabb_global_support', true ) ) {
				
				wp_localize_script( 'wpzabb-builder-js', 'wpzabb_global', array( 'show_global_button' => true ) );
				
				$wpzabb = WPZABB_Global_Styling::get_wpzabb_global_settings();

				if( isset( $wpzabb->enable_global ) && ( $wpzabb->enable_global == 'no' ) ) {
					wp_localize_script( 'wpzabb-builder-js', 'wpzabb_presets', array( 'show_presets' => true ) );
				}
			}
		}

		wp_dequeue_style( 'bootstrap-tour' );
		wp_dequeue_script( 'bootstrap-tour' );
		
	}

	function admin_notices() {

		if ( file_exists( plugin_dir_path( 'bb-plugin-agency/fl-builder.php' ) ) 
			|| file_exists( plugin_dir_path( 'beaver-builder-lite-version/fl-builder.php' ) ) ) {

			$url = network_admin_url() . 'plugins.php?s=Beaver+Builder+Plugin';
		} else {
			$url = network_admin_url() . 'plugin-install.php?s=billyyoung&tab=search&type=author';
		}

		echo '<div class="notice notice-error">';
	    echo "<p>The <strong>WPZOOM Addons Pack for Beaver Builder</strong> " . __( 'plugin requires', 'wpzabb' )." <strong><a href='".$url."'>Beaver Builder</strong></a>" . __( ' plugin installed & activated.', 'wpzabb' ) . "</p>";
	    echo '</div>';
  	}

  	function load_modules() {

  		$enable_modules = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_modules();
		foreach ( $enable_modules as $file => $name ) {

			if ( $name == 'false' ) {
				continue;
			}

			$module_path	= $file . '/' .$file . '.php';
			$child_path		= get_stylesheet_directory() . '/wpzoom-bb-addon-pack/modules/'.$module_path;
			$theme_path		= get_template_directory() . '/wpzoom-bb-addon-pack/modules/'.$module_path;
			$addon_path		= BB_WPZOOM_ADDON_DIR . 'modules/' . $module_path;

			// Check for the module class in a child theme.
			if( is_child_theme() && file_exists($child_path) ) {
				require_once $child_path;
			}

			// Check for the module class in a parent theme.
			else if( file_exists($theme_path) ) {
				require_once $theme_path;
			}

			// Check for the module class in the builder directory.
			else if( file_exists($addon_path) ) {
				require_once $addon_path;
			}
		}
  	}
}

/**
 * Initialize the class only after all the plugins are loaded.
 */

function init_wpzabb() {
	$WPZABB_Init = new WPZABB_Init();
}

add_action( 'plugins_loaded', 'init_wpzabb' );
