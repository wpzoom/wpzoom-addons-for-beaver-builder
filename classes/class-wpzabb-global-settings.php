<?php

/**
 * White labeling for the builder.
 *
 * @since 1.8
 */
final class WPZABBGlobalSetting {

	/**
	 * @return void
	 */
	static public function init() {
		add_filter( 'fl_builder_ui_js_strings', __CLASS__ . '::add_js_string' );
	}

	/**
	 * WPZABB Global js String
	 */
	static public function add_js_string( $js_strings ) {

		if ( WPZABB_PREFIX == 'WPZABB') {
			$js_strings['wpzabbGlobalSettings']  = esc_attr__('WPZABB - Global Settings', 'wpzabb');
			$js_strings['wpzabbKnowledgeBase' ]  = esc_attr__('WPZABB - Knowledge Base', 'wpzabb');
			$js_strings['wpzabbContactSupport' ] = esc_attr__('WPZABB - Contact Support', 'wpzabb');
		} else {
			$js_strings['wpzabbGlobalSettings'] = sprintf(
						esc_attr__( '%s - Global Settings', 'wpzabb' ),
						WPZABB_PREFIX
					);

			$js_strings['wpzabbKnowledgeBase'] = sprintf(
						esc_attr__( '%s - Knowledge Base', 'wpzabb' ),
						WPZABB_PREFIX
					);

			$js_strings['wpzabbContactSupport'] = sprintf(
						esc_attr__( '%s - Contact Support', 'wpzabb' ),
						WPZABB_PREFIX
					);
		}

		$wpzabb = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding();
		if ( is_array( $wpzabb ) ) {
			$wpzabb_knowledge_base_url             = ( array_key_exists( 'wpzabb-knowledge-base-url' , $wpzabb ) && $wpzabb['wpzabb-knowledge-base-url' ] != ''  ) ? $wpzabb['wpzabb-knowledge-base-url' ] : 'https://www.ultimatebeaver.com/docs/';
			$wpzabb_contact_support_url            = ( array_key_exists( 'wpzabb-contact-support-url' , $wpzabb ) && $wpzabb['wpzabb-contact-support-url' ] != '' ) ? $wpzabb['wpzabb-contact-support-url' ] : 'https://www.ultimatebeaver.com/contact/';
			$js_strings['wpzabbKnowledgeBaseUrl']  = $wpzabb_knowledge_base_url;
			$js_strings['wpzabbContactSupportUrl'] = $wpzabb_contact_support_url;
		}else{
			$js_strings['wpzabbKnowledgeBaseUrl']  = 'https://www.ultimatebeaver.com/docs/';
			$js_strings['wpzabbContactSupportUrl'] = 'https://www.ultimatebeaver.com/contact/';
		}
		return $js_strings;
	}
}

WPZABBGlobalSetting::init();
