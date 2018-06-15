<?php

/**
 * WPZABB_UI_Panels setup
 *
 * @since 1.1.0.4
 */

class WPZABB_UI_Panels {

	/**
	*  Constructor
	*/
	public function __construct() {
		
		// Enqueue CSS & JS
		add_action( 'wp_enqueue_scripts', array( $this, 'wpzabb_panel_css_js' ) );

		add_action( 'wp_footer', array( $this, 'render_live_preview'), 9 );
		
		//	Render JS & CSS
		add_filter( 'fl_builder_render_css', array( $this, 'fl_wpzabb_render_css' ), 10, 3 );
		add_filter( 'fl_builder_render_js', array( $this, 'fl_wpzabb_render_js' ), 10, 3 );

		// skip brainstorm registration for updater
		add_filter( 'wpzoom_skip_author_registration', array( $this, 'wpzabb_skip_brainstorm_menu' ) );
		add_filter( 'wpzoom_skip_braisntorm_menu', array( $this, 'wpzabb_skip_brainstorm_menu' ) );

		// Registration page URL for WPZABB
		add_filter( 'wpzoom_registration_page_url_wpzabb', array( $this, 'wpzabb_wpzoom_registration_page_url' ) );
		add_filter( 'wpzoom_license_form_heading_wpzabb', array( $this, 'wpzabb_wpzoom_license_form_heading'), 10, 3 );

		$this->config();
		$this->init();
	}

	function toggle_wpzabb_ui() {

		//	Added ui panel
		add_action( 'wp_footer', array( $this, 'render_ui'), 9 );
		
		//	Added buttons in ui panel
		add_filter( 'fl_builder_ui_bar_buttons', array( $this, 'builder_ui_bar_buttons') );

		//	Excluded WPZABB templates
		add_filter( 'fl_builder_row_templates_data', 	array( $this, 'wpzabb_templates_data'), 50 );
		add_filter( 'fl_builder_module_templates_data', array( $this, 'wpzabb_templates_data'), 50 );

		//	Added search html in BB 'add content' panel
		add_action( 'fl_builder_ui_panel_before_rows', array( $this, 'wpzabb_panel_before_row_layouts') );
	}

	public function init() {

		// add_filter( 'init', array( $this, 'config') );
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

	function wpzabb_wpzoom_registration_page_url( $url ) {

		if ( is_multisite() && FL_BUILDER_LITE === false ) {
			return network_admin_url( '/settings.php?page=wpzabb-builder-multisite-settings#wpzabb-license' );
		} else {
			return admin_url( 'options-general.php?page=wpzabb-builder-settings#wpzabb-license' );
		}
	}

	function wpzabb_wpzoom_license_form_heading( $form_heading, $license_status_class, $license_status ) {

		$branding_name       = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding( 'wpzabb-plugin-name' );
		$branding_short_name = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding( 'wpzabb-plugin-short-name' );

		if ( $license_status_class == 'wpzoom-license-not-active-wpzabb' ) {
			if( empty( $branding_name ) && empty( $branding_short_name ) ) {
				$license_string = '<a href="https://store.brainstormforce.com/purchase-history/" target="_blank">license key</a>';
			} else {
				$license_string = 'license key';
			}
			$form_heading = $form_heading . '<p>Enter your '.$license_string.' to enable remote updates and support.</p>';
		}

		return $form_heading;
	}

	/**
	 * Skip Brainstorm Registration screen for WPZABB users
	 */
	function wpzabb_skip_brainstorm_menu( $products ) {

		if ( function_exists( 'wpzoom_extract_product_id' ) ) {
			$priduct_id = wpzoom_extract_product_id( BB_WPZOOM_ADDON_DIR );
			$products[] = $priduct_id;
		}

		return $products;
	}

	/**
	 * Render Global wpzabb-layout-builder js
	 */
	function fl_wpzabb_render_js($js, $nodes, $global_settings) {
		$temp = file_get_contents(BB_WPZOOM_ADDON_DIR . 'assets/js/wpzabb-frontend.js') . $js;
		$js = $temp;
		return $js;
	}

	/**
	 * Render Global wpzabb-layout-builder css
	 */
	function fl_wpzabb_render_css($css, $nodes, $global_settings) {

	    $css .= file_get_contents(BB_WPZOOM_ADDON_DIR . 'assets/css/wpzabb-frontend.css');
		$css .= include( BB_WPZOOM_ADDON_DIR . 'assets/dynamic-css/wpzabb-theme-dynamic-css.php');

		return $css;
	}

	function config() {
		
		$is_templates_exist = WPZOOM_BB_Addon_Pack_Helper::is_templates_exist();
		if( $is_templates_exist ) {
			$this->load_templates();
		}

		$wpzabb = WPZABB_Init::$wpzabb_options['fl_builder_wpzabb'];
		if( !empty($wpzabb) && is_array($wpzabb) ) {

			// Load UI Panel if option exist
			if( array_key_exists( 'load_panels', $wpzabb ) ) {
				if( $wpzabb['load_panels'] == 1 ) {
					$this->toggle_wpzabb_ui();
				}
			}

		//	Initially load the WPZABB UI Panel
		} else {
			$this->toggle_wpzabb_ui();
		}
	}

	/**
	 * Load cloud templates
	 *
	 * @since 1.2.0.2
	 */
	function load_templates() {

		if ( ! method_exists( 'FLBuilder', 'register_templates' ) ) {
			return;
		}

		$templates = get_site_option( '_wpzabb_cloud_templats', false );

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
							FLBuilder::register_templates( $template_data['dat_url_local']  );
						}
					}
				}
			}
		}
	}



	function wpzabb_panel_before_row_layouts() {
		?>
			<!-- Search Module -->
			<div id="fl-builder-blocks-rows" class="fl-builder-blocks-section">
				<input type="text" id="module_search" placeholder="<?php _e('Search Module...', 'wpzabb'); ?>" style="width: 100%;">
				<div class="filter-count"></div>
			</div><!-- Search Module -->
		<?php 
	}

	/**
	 *	1. Return all templates 'excluding' WPZABB templates. If variable $status is set to 'exclude'. Default: 'exclude'
	 *	2. Return ONLY WPZABB templates. If variable $status is NOT set to 'exclude'.
	 *
	 * @since 1.1.0.4
	 */
	static public function wpzabb_templates_data( $templates, $status = 'exclude' ) {

		if ( isset( $templates['categorized'] ) && count( $templates['categorized'] ) > 0 ) {

			foreach( $templates['categorized'] as $ind => $cat ) {

				foreach( $cat['templates'] as $cat_id => $cat_data ) {

					// Return all templates 'excluding' WPZABB templates
					if( $status == 'exclude' ) {
						if( 
							( isset( $cat_data['author'] ) && $cat_data['author'] == 'brainstormforce' )
						) {
							unset( $templates['categorized'][$ind]['templates'][$cat_id] );
						}
					
					// Return ONLY WPZABB templates
					} else {
						if( 
							( isset( $cat_data['author'] ) && $cat_data['author'] != 'brainstormforce' ) 
						) {
							unset( $templates['categorized'][$ind]['templates'][$cat_id] );
						}
					}
				}

				//	Delete category if not templates found
				if( count( $templates['categorized'][$ind]['templates'] ) <= 0 ) {
					unset( $templates['categorized'][$ind] );
				}
			}
	    }

	    return $templates;
	}

	/**
	 * 	Add Buttons to panel
	 *
	 * Row button added to the panel
	 * @since 1.0
	 */
	function builder_ui_bar_buttons( $buttons ) {

		if( is_callable('FLBuilderUserAccess::current_user_can') ) {
			$simple_ui    = ! FLBuilderUserAccess::current_user_can( 'unrestricted_editing' );
		} else {
			$simple_ui    = ! FLBuilderModel::current_user_has_editing_capability();
		}

		$has_presets  = WPZOOM_BB_Addon_Pack_Helper::is_templates_exist( 'presets' );
		$has_sections = WPZOOM_BB_Addon_Pack_Helper::is_templates_exist( 'sections' );

		$buttons['add-ultimate-presets'] = array(
			'label' => __( 'Presets', 'wpzabb' ),
			'show'	=> ( ! $simple_ui && $has_presets ),
		);
		
		$buttons['add-ultimate-rows'] = array(
			'label' => __( 'Sections', 'wpzabb' ),
			'show'	=> ( ! $simple_ui && $has_sections ),
		);
		
		// Move button 'Add Content' at the start
		$add_content = $buttons['add-content'];
		unset($buttons['add-content']);
		$buttons['add-content'] = $add_content;

		return $buttons;
	}

	/**
	 * 	Load Rows Panel
	 *
	 * Row panel showing sections - rows & modules
	 * @since 1.0
	 */
	function render_ui() {

		global $wp_the_query;

		
		if ( FLBuilderModel::is_builder_active() ) {

			if( is_callable('FLBuilderUserAccess::current_user_can') ) {
				$has_editing_cap 	= FLBuilderUserAccess::current_user_can( 'unrestricted_editing' ); 
				$simple_ui    		= ! $has_editing_cap;
			} else {
				$has_editing_cap 	= FLBuilderModel::current_user_has_editing_capability();
				$simple_ui    		= ! $has_editing_cap;
			}
			
			//	Panel
			$post_id            = $wp_the_query->post->ID;
			$categories         = FLBuilderModel::get_categorized_modules();

			/** 
			 * Renders categorized row & module templates in the UI panel.
			 **/
			$is_row_template    = FLBuilderModel::is_post_user_template( 'row' );
			$is_module_template = FLBuilderModel::is_post_user_template( 'module' );
			$row_templates      = FLBuilderModel::get_template_selector_data( 'row' );
			$module_templates   = FLBuilderModel::get_template_selector_data( 'module' );

			if( WPZOOM_BB_Addon_Pack_Helper::is_templates_exist( 'sections' ) ) {
				include BB_WPZOOM_ADDON_DIR . 'includes/ui-panel-sections.php';
			}

			if( WPZOOM_BB_Addon_Pack_Helper::is_templates_exist( 'presets' ) ) {
				include BB_WPZOOM_ADDON_DIR . 'includes/ui-panel-presets.php';
			}
		}
	}

	function render_live_preview() {
		if ( FLBuilderModel::is_builder_active() ) {
			/* Live Preview */
			$wpzabb = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb();

			if ( is_array( $wpzabb ) && array_key_exists( 'wpzabb-live-preview', $wpzabb ) && $wpzabb['wpzabb-live-preview'] == 1 ) {
				
				/* Live Preview HTML */
				$live_preview = '<span class="wpzabb-live-preview-button fl-builder-button-primary fl-builder-button" >Live Preview</span>';

			    echo $live_preview;
			}
		}
	}

	/**
	 * Enqueue Panel CSS and JS
	 */
	function wpzabb_panel_css_js() {
		if ( FLBuilderModel::is_builder_active() ) {
			wp_enqueue_script('wpzabb-panel-js', BB_WPZOOM_ADDON_URL . 'assets/js/wpzabb-panel.js', array('jquery'), '', true);
		}
	}

}

new WPZABB_UI_Panels();
