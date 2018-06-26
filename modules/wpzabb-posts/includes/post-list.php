<div <?php $module->render_post_class(); ?> itemscope itemtype="<?php FLPostGridModule::schema_itemtype(); ?>">

	<?php FLPostGridModule::schema_meta(); ?>
	<?php $module->render_featured_image( array( 'above-title', 'beside', 'beside-right' ) ); ?>

	<?php if ( in_array( $settings->image_position, array( 'above-title', 'beside', 'beside-right' ) ) || ! $module->has_featured_image( array( 'beside-content', 'beside-content-right' ) ) ) : ?>
	<div class="wpzabb-post-list-text">
	<?php endif; ?>

		<div class="wpzabb-post-list-header">

			<h2 class="wpzabb-post-list-title" itemprop="headline">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php do_action( 'wpzabb_builder_post_feed_before_meta', $settings, $module ); ?>

			<?php if ( $settings->show_author || $settings->show_date || $settings->show_comments ) : ?>
			<div class="wpzabb-post-list-meta">
				<?php if ( $settings->show_author ) : ?>
					<span class="wpzabb-post-list-author">
						<?php

						printf(
							_x( 'By %s', '%s stands for author name.', 'fl-builder' ),
							'<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '"><span>' . get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) . '</span></a>'
						);

						?>
					</span>
				<?php endif; ?>
				<?php if ( $settings->show_date ) : ?>
					<?php if ( $settings->show_author ) : ?>
						<span class="fl-sep"><?php echo $settings->info_separator; ?></span>
					<?php endif; ?>
					<span class="wpzabb-post-list-date">
						<?php FLBuilderLoop::post_date( $settings->date_format ); ?>
					</span>
				<?php endif; ?>
				<?php if ( $settings->show_comments ) : ?>
					<?php if ( $settings->show_author || $settings->show_date ) : ?>
						<span class="fl-sep"><?php echo $settings->info_separator; ?></span>
					<?php endif; ?>
					<span class="wpzabb-post-list-comments">
						<?php comments_popup_link( __( '0 Comments', 'fl-builder' ), __( '1 Comment', 'fl-builder' ), __( '% Comments', 'fl-builder' ) ); ?>
					</span>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $settings->show_terms && $module->get_post_terms() ) : ?>
			<div class="wpzabb-post-list-meta">
				<div class="wpzabb-post-list-terms">
					<span class="fl-terms-label"><?php echo $settings->terms_list_label; ?></span>
					<?php echo $module->get_post_terms(); ?>
				</div>
			</div>
			<?php endif; ?>

			<?php do_action( 'wpzabb_builder_post_feed_after_meta', $settings, $module ); ?>

		</div>

	<?php if ( $module->has_featured_image( 'above' ) ) : ?>
	</div>
	<?php endif; ?>

	<?php $module->render_featured_image( array( 'above', 'beside-content', 'beside-content-right' ) ); ?>

	<?php if ( $module->has_featured_image( array( 'above', 'beside-content', 'beside-content-right' ) ) ) : ?>
	<div class="wpzabb-post-list-text">
	<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_feed_before_content', $settings, $module ); ?>

		<?php if ( $settings->show_content || $settings->show_more_link ) : ?>
		<div class="wpzabb-post-list-content" itemprop="text">
			<?php

			if ( $settings->show_content ) {

				if ( 'full' == $settings->content_type ) {
					$module->render_content();
				} else {
					$module->render_excerpt();
				}
			}

			?>
			<?php if ( $settings->show_more_link ) : ?>
			<a class="wpzabb-post-list-more" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $settings->more_link_text; ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_feed_after_content', $settings, $module ); ?>

	</div>

	<div class="fl-clear"></div>
</div>
