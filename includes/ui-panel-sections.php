<?php

/**
 * 	Row and Module Categorized & UnCategorized Templates
 *
 * 	Showing Row and Module Templates from sections-*.dat, presets-*.dat
 */

?>

<div class="fl-builder-panel fl-builder-panel-ultimate-rows">
	<div class="fl-builder-panel-actions">
		<i class="fl-builder-panel-close fa fa-times"></i>
	</div>
	<div class="fl-builder-panel-content-wrap fl-nanoscroller">
		<div class="fl-builder-panel-content fl-nanoscroller-content">
			<div class="fl-builder-blocks">

				<!-- Search Section -->
				<div id="fl-builder-blocks-rows" class="fl-builder-blocks-section">
					<input type="text" id="section_search" placeholder="<?php _e('Search Section...', 'wpzabb'); ?>" style="width: 100%;">
					<div class="filter-count"></div>
				</div><!-- Search Section -->

				<?php do_action( 'wpzabb_fl_builder_ui_panel_before_rows' ); ?>

				<?php

				/** 
				* Renders WPZABB categorized row templates in the UI panel.
				*
				* @see render_ui_panel_row_templates()
				*/
				if ( ! $is_row_template && ! $is_module_template && $has_editing_cap ) {

					$wpzabb_row_templates = WPZABB_UI_Panels::wpzabb_templates_data( $row_templates, 'includes' );

					if ( count( $wpzabb_row_templates ) > 0 ) :

						foreach ( $wpzabb_row_templates['categorized'] as $cat ) : 

							// avoid 'Uncategorized'
							if( trim( $cat['name'] ) != 'Uncategorized' ) : ?>
								<div class="fl-builder-blocks-section">
									<span class="fl-builder-blocks-section-title">
										<?php echo __( $cat['name'] , 'wpzabb' ); ?>
										<i class="fa fa-chevron-down"></i>
									</span>
									<div class="fl-builder-blocks-section-content fl-builder-row-templates">
										<?php foreach ( $cat['templates'] as $template ) :

											//	Get tags
											$tags = '';
											if( is_array( $template['tags'] ) && count( $template['tags'] ) > 0 ) {
												$tags = implode(', ', $template['tags']);
											} ?>

											<span class="fl-builder-block fl-builder-block-template fl-builder-block-row-template" data-id="<?php echo $template['id']; ?>" data-type="<?php echo $template['type']; ?>">
												<?php if ( ! stristr( $template['image'], 'blank.jpg' ) ) : ?>
												<img class="fl-builder-block-template-image" src="<?php echo $template['image']; ?>" />
												<?php endif; ?>
												<span class="fl-builder-block-title" data-tags="<?php echo $tags; ?>" data-cat-name="<?php echo $cat['name']; ?>"><?php echo $template['name']; ?></span>
											</span>

										<?php endforeach; ?>
									</div>
								</div>
							<?php endif;
						endforeach;
					endif;
				} ?>

				<?php do_action( 'wpzabb_fl_builder_ui_panel_after_rows' ); ?>

				<?php if ( WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding( 'wpzabb-enable-template-cloud' ) ) { ?>
				<div class="fl-builder-modules-cta">
					<a href="#" onclick="window.open('<?php echo admin_url(); ?>options-general.php?page=wpzabb-builder-settings#wpzabb-cloud-templates');" target="_blank"><i class="fa fa-external-link-square"></i> <?php echo sprintf( __( 'Note - You can enable, disable and manage %s sections here.', 'wpzabb' ), WPZABB_PREFIX );?></a>
				</div>
				<?php } ?>
				<div class="fl-builder-modules-cta">
					<a href="#" target="_self"><?php echo __( 'Note - Images used in the templates are hotlinked from our server and are subject to copyright. It is strongly recommended that you replace them with your own.', 'wpzabb' );?></a>
				</div>
			</div>
		</div>
	</div>
</div>
