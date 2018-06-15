<div id="fl-wpzabb-cloud-templates-form" class="fl-settings-form wpzabb-cloud-templates-fl-settings-form">

	<h3 class="fl-settings-form-header"><?php _e( 'Template Cloud', 'wpzabb' ); ?></h3>

	<form id="wpzabb-cloud-templates-form" action="<?php WPZABBBuilderAdminSettings::render_form_action( 'wpzabb-cloud-templates' ); ?>" method="post">

		<?php if ( FLBuilderAdminSettings::multisite_support() && ! is_network_admin() ) : ?>
		<label>
			<input class="fl-override-ms-cb" type="checkbox" name="fl-override-ms" value="1" <?php if(get_option('_fl_builder_wpzabb_cloud_templates')) echo 'checked="checked"'; ?> />
			<?php _e('Override network settings?', 'wpzabb'); ?>
		</label>
		<?php endif; ?>

		<div class="fl-settings-form-content">

			<!-- Append all templates -->
			<div id="wpzabb-cloud-templates-tabs">

				<div id="wpzabb-cloud-templates-inner" class="wp-filter">

					<div class="filter-count">
						<span class="count"><?php echo WPZABB_Cloud_Templates::get_cloud_templates_count('page-templates'); ?></span>
					</div>
					<ul class="wpzabb-filter-links">
						<li><a href="#wpzabb-cloud-templates-page-templates" data-count="<?php echo WPZABB_Cloud_Templates::get_cloud_templates_count('page-templates'); ?>"> <?php _e('Page Templates', 'wpzabb'); ?> </a></li>
						<li><a href="#wpzabb-cloud-templates-sections" data-count="<?php echo WPZABB_Cloud_Templates::get_cloud_templates_count('sections'); ?>"> <?php _e('Sections', 'wpzabb'); ?> </a></li>
					</ul>
					<div class="wpzabb-fetch-templates">
						<span class="button button-secondary wpzabb-cloud-process" data-operation="fetch">
					    	<i class="dashicons dashicons-update " style="padding: 3px;"></i>
					    	<span class="msg"> <?php _e('Refresh', 'wpzabb'); ?> </span>
					   	</span>
					</div>

				</div>
				<div class="wpzabb-cloud-templates-tabs-container">
					<div id="wpzabb-cloud-templates-page-templates">
						<?php
							//	Print Templates HTML
							WPZABB_Cloud_Templates::template_html( 'page-templates' );
						?>
					</div>
					<div id="wpzabb-cloud-templates-sections">
						<?php
							//	Print Templates HTML
							WPZABB_Cloud_Templates::template_html( 'sections' );
						?>
					</div>
				</div>
			</div>


			<br/>

		</div>
	</form>
</div>
