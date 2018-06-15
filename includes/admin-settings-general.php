<div id="fl-wpzabb-form" class="fl-settings-form wpzabb-fl-settings-form">

	<h3 class="fl-settings-form-header"><?php _e( 'General Settings', 'wpzabb' ); ?></h3>

	<form id="wpzabb-form" action="<?php WPZABBBuilderAdminSettings::render_form_action( 'wpzabb' ); ?>" method="post">

		<div class="fl-settings-form-content">

			<?php

				$wpzabb                = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb();
				$branding_name       = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding( 'wpzabb-plugin-name' );
				$branding_short_name = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding( 'wpzabb-plugin-short-name' );

				$is_load_templates = $is_load_panels = $wpzabb_live_preview = '';
				if( is_array($wpzabb) ) {
					$is_load_panels      = ( array_key_exists( 'load_panels', $wpzabb ) && $wpzabb['load_panels'] == 1 )  ? ' checked' : '';
					$wpzabb_live_preview   = ( array_key_exists( 'wpzabb-live-preview', $wpzabb ) && $wpzabb['wpzabb-live-preview'] == 1 )  ? ' checked' : '';
				} ?>

			<!-- Load Panels -->
			<div class="wpzabb-form-setting">
				<h4><?php _e( 'Enable UI Design', 'wpzabb' ); ?></h4>
				<p class="wpzabb-admin-help">
					<?php _e('Enable this setting for applying UI effects such as - Section panel, Search box etc. to frontend page builder. ', 'wpzabb'); ?>
					<?php
					if( empty( $branding_name ) && empty( $branding_short_name ) ) :
						_e('Read ', 'wpzabb'); ?><a target="_blank" href="https://www.ultimatebeaver.com/docs/how-to-enable-disable-beaver-builders-ui/"><?php _e('this article', 'wpzabb'); ?></a><?php _e(' for more information.', 'wpzabb');
					endif;
					?>
				</p>
				<label>					
					<input type="checkbox" class="wpzabb-enabled-panels" name="wpzabb-enabled-panels" value="" <?php echo $is_load_panels; ?> ><?php _e( 'Enable UI Design', 'wpzabb' ); ?>
				</label>
			</div>

			<!-- Load Panels -->
			<div class="wpzabb-form-setting">
				<h4><?php _e( 'Enable Live Preview', 'wpzabb' ); ?></h4>
				<p class="wpzabb-admin-help"><?php _e('Enable this setting to see live preview of a page without leaving the editor.', 'wpzabb'); ?></p>
				<label>					
					<input type="checkbox" class="wpzabb-live-preview" name="wpzabb-live-preview" value="" <?php echo $wpzabb_live_preview; ?> ><?php _e( 'Enable Live Preview', 'wpzabb' ); ?>
				</label>
			</div>
		</div>

		<p class="submit">
			<input type="submit" name="fl-save-wpzabb" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'wpzabb' ); ?>" />
		</p>

		<?php wp_nonce_field('wpzabb', 'fl-wpzabb-nonce'); ?>

	</form>
</div>
