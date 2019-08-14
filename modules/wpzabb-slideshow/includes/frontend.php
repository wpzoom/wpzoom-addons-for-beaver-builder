<div class="wpzabb-slideshow">

	<ul class="wpzabb-slideshow-slides">
		<?php

		for ( $i = 0; $i < count( $settings->slides ); $i++ ) :

			if ( ! is_object( $settings->slides[ $i ] ) ) {
				continue;
			}

			$slide   = $settings->slides[ $i ];
			$classes = $module->get_classes( $slide );
			$src     = trim( $module->get_src( $slide ) );
			$alt     = $module->get_alt( $slide );

		?>
		<li class="wpzabb-slideshow-slide wpzabb-slideshow-slide-<?php echo $i + 1; ?>">
			<div class="wpzabb-slideshow-slide-outer-wrap">
				<?php if( !empty( $src ) ) : ?>
					<div class="wpzabb-slideshow-slide-image" itemscope itemtype="http://schema.org/ImageObject">
						<img class="<?php echo esc_attr( $classes ); ?>" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
					</div>
				<?php endif; ?>

				<div class="wpzabb-slideshow-slide-details">
					<h4 class="wpzabb-slideshow-slide-title">
						<?php if( !empty( $slide->link ) ) : ?>
							<a href="<?php echo esc_url( $slide->link ); ?>" title="<?php echo esc_attr( $slide->title ); ?>" target="<?php echo $slide->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $slide->link_target, $slide->link_nofollow, 1 ); ?>>
						<?php endif; ?>

						<?php echo esc_html( $slide->title ); ?>

						<?php if( !empty( $slide->link ) ) : ?>
							</a>
						<?php endif; ?>
					</h4>

					<div class="wpzabb-slideshow-slide-content">
						<?php echo wp_kses_post( $slide->content ); ?>
					</div>

					<?php if ( $slide->button == 'yes' ) : ?>
						<div class="wpzabb-slideshow-slide-button">
							<a href="<?php echo esc_url( $slide->button_url ); ?>" title="<?php echo esc_attr( $slide->button_label ); ?>" target="<?php echo $slide->button_url_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $slide->button_url_target, $slide->button_url_nofollow, 1 ); ?>><?php echo $slide->button_label; ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</li>
		<?php endfor; ?>
	</ul>
</div>