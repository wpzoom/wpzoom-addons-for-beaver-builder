<?php do_action( 'wpzabb_builder_post_grid_before_meta', $settings, $module ); ?>

<?php if ( $settings->show_author || $settings->show_date || $settings->grid_show_comments ) : ?>
<div class="wpzabb-post-grid-meta">
	<?php if ( $settings->show_author ) : ?>
		<span class="wpzabb-post-grid-author">
		<?php

		printf(
			_x( 'By %s', '%s stands for author name.', 'wpzabb' ),
			'<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '"><span>' . get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) . '</span></a>'
		);

		?>
		</span>
	<?php endif; ?>
	<?php if ( $settings->show_date ) : ?>
		<span class="wpzabb-post-grid-date">
			<?php FLBuilderLoop::post_date( $settings->date_format ); ?>
		</span>
	<?php endif; ?>
	<?php if ( $settings->grid_show_comments ) : ?>
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