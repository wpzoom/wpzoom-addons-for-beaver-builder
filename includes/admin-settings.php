<div class="wrap <?php WPZABBBuilderAdminSettings::render_page_class(); ?>">

	<h2 class="fl-settings-heading">
		<?php WPZABBBuilderAdminSettings::render_page_heading(); ?>
	</h2>
	
	<?php WPZABBBuilderAdminSettings::render_update_message(); ?>

	<div class="fl-settings-nav">
		<ul>
			<?php WPZABBBuilderAdminSettings::render_nav_items(); ?>
		</ul>
	</div>

	<div class="fl-settings-content">
		<?php WPZABBBuilderAdminSettings::render_forms(); ?>
	</div>
</div>
