<?php if ( 'columns' == $settings->layout ) : ?>
<div class="wpzabb-post-column">
<?php endif; ?>

<div <?php $module->render_post_class(); ?> itemscope itemtype="<?php FLPostGridModule::schema_itemtype(); ?>">

	<?php FLPostGridModule::schema_meta(); ?>
	<?php $module->render_featured_image( 'above-title' ); ?>

	<div class="wpzabb-post-grid-text">

		<h2 class="wpzabb-post-grid-title" itemprop="headline">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		</h2>

		<?php do_action( 'wpzabb_builder_post_grid_before_meta', $settings, $module ); ?>

		<?php if ( $settings->show_author || $settings->show_date || $settings->show_comments_grid ) : ?>
		<div class="wpzabb-post-grid-meta">
			<?php if ( $settings->show_author ) : ?>
				<span class="wpzabb-post-grid-author">
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
				<span class="wpzabb-post-grid-date">
					<?php FLBuilderLoop::post_date( $settings->date_format ); ?>
				</span>
			<?php endif; ?>
			<?php if ( $settings->show_comments_grid ) : ?>
				<?php if ( $settings->show_author || $settings->show_date ) : ?>
					<span class="fl-sep"><?php echo $settings->info_separator; ?></span>
				<?php endif; ?>
				<span class="wpzabb-post-list-comments">
					<?php comments_popup_link( '0 <i class="far fa-comment"></i>', '1 <i class="far fa-comment"></i>', '% <i class="far fa-comment"></i>' ); ?>
				</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( $settings->show_terms && $module->get_post_terms() ) : ?>
		<div class="wpzabb-post-grid-meta">
			<div class="wpzabb-post-grid-terms">
				<span class="fl-terms-label"><?php echo $settings->terms_list_label; ?></span>
				<?php echo $module->get_post_terms(); ?>
			</div>
		</div>
		<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_grid_after_meta', $settings, $module ); ?>

	<?php if ( $module->has_featured_image( 'above' ) ) : ?>
	</div>
	<?php endif; ?>

	<?php $module->render_featured_image( 'above' ); ?>

	<?php if ( $module->has_featured_image( 'above' ) ) : ?>
	<div class="wpzabb-post-grid-text">
	<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_grid_before_content', $settings, $module ); ?>

		<?php if ( $settings->show_content || $settings->show_more_link ) : ?>
		<div class="wpzabb-post-grid-content">
			<?php if ( $settings->show_content ) : ?>
			<?php $module->render_excerpt(); ?>
			<?php endif; ?>
			<?php if ( $settings->show_more_link ) : ?>
			<a class="wpzabb-post-grid-more" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $settings->more_link_text; ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php do_action( 'wpzabb_builder_post_grid_after_content', $settings, $module ); ?>

	</div>
</div>

<?php if ( 'columns' == $settings->layout ) : ?>
</div>
<?php endif; ?>
