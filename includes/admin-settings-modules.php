<div id="fl-wpzabb-modules-form" class="fl-settings-form wpzabb-modules-fl-settings-form">

	<h3 class="fl-settings-form-header"><?php echo sprintf( __( '%s Modules', 'wpzabb' ), WPZABB_PREFIX ); ?></h3>

	<div id="wpzabb-modules-form" class="wpzabb-lite-modules" action="<?php WPZABBBuilderAdminSettings::render_form_action( 'wpzabb-modules' ); ?>" method="post">
		<div class="fl-settings-form-content">
			<?php $modules_array   = WPZOOM_BB_Addon_Pack_Helper::get_all_modules(); ?>
			<?php foreach ( $modules_array as $slug => $name ) : ?>
				<p><label><?php echo $name; ?></label></p>
			<?php endforeach; ?>
		</div>
	</div>
</div>