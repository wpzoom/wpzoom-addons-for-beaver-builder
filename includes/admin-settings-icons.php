<div id="fl-wpzabb-icons-form" class="fl-settings-form wpzabb-fl-settings-form">

	<h3 class="fl-settings-form-header"><?php _e( 'Reload Icons', 'wpzabb' ); ?></h3>
	
	<form id="wpzabb-icons-form" action="<?php WPZABBBuilderAdminSettings::render_form_action( 'wpzabb-icons' ); ?>" method="post">
		
		<div class="fl-settings-form-content">

			<p><?php echo sprintf( 
					__( 'Clicking the button below will reinstall %s icons on your website. If you are facing issues to load %s icons then you are at right place to troubleshoot it.', 'wpzabb' ), 
					WPZABB_PREFIX, 
					WPZABB_PREFIX 
				); ?></p>
			<span class="button wpzabb-reload-icons">
				<i class="dashicons dashicons-update" style="padding: 3px;"></i>
				<?php _e( 'Reload Icons', 'wpzabb' ); ?>
			</span>

		</div>
	</form>
</div>
