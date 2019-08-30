<div class="wpzabb-slideshow">

	<ul class="wpzabb-slideshow-slides slides">
		<?php

		for ( $i = 0; $i < count( $settings->slides ); $i++ ) :

			if ( ! is_object( $settings->slides[ $i ] ) ) {
				continue;
			}

			$slide   = $settings->slides[ $i ];
			$classes = $module->get_classes( $slide );
			$img     = trim( $module->get_src( $slide ) );
			$thumb = !empty( $img ) ? ' data-thumb="' . esc_url( $img ) . '"' : '';
			$vid = $module->get_video_embed( $slide );
			$vid_id = $module->get_video_id( $slide );
			$vid_url = $module->get_video_url( $slide );
			$vid_type = $module->get_video_type( $slide );

		?>
		<li class="wpzabb-slideshow-slide wpzabb-slideshow-slide-<?php echo $i + 1; ?>"<?php echo $thumb; ?>>
			<div class="wpzabb-slideshow-slide-outer-wrap">
				<?php if ( !empty( $img ) ) : ?>
					<div class="wpzabb-slideshow-slide-image" itemscope itemtype="http://schema.org/ImageObject">
						<img class="<?php echo esc_attr( $classes ); ?>" src="<?php echo esc_url( $img ); ?>" />
					</div>
				<?php endif; ?>

				<?php if ( false !== $vid && ! empty( $vid ) ) : ?>
					<div class="wpzabb-slideshow-slide-video" itemscope itemtype="http://schema.org/VideoObject"
					     data-type="<?php echo $vid_type; ?>"
					     data-id="<?php echo $vid_id; ?>"
					     data-url="<?php echo esc_url( $vid_url ); ?>"
					     data-autoplay="<?php echo 'yes' == $slide->autoplay ? 'true' : 'false'; ?>"
					     data-loop="<?php echo 'yes' == $slide->loop ? 'true' : 'false'; ?>"
					     data-startmuted="<?php echo 'yes' == $slide->startmuted ? 'true' : 'false'; ?>"
					     data-playing="<?php echo 'yes' == $slide->autoplay ? 'true' : 'false'; ?>"
					     data-muted="<?php echo 'yes' == $slide->startmuted ? 'true' : 'false'; ?>">
						<?php echo $vid; ?>

						<div class="wpzabb-slideshow-slide-video-controls">
							<?php if ( 'yes' == $slide->playpause ) : ?>
								<a title="<?php _e( 'Play', 'wpzabb' ); ?>" class="play-video dashicons dashicons-controls-play"></a>
								<a title="<?php _e( 'Pause', 'wpzabb' ); ?>" class="pause-video dashicons dashicons-controls-pause"></a>
							<?php endif; ?>

							<?php if ( 'yes' == $slide->muteunmute ) : ?>
								<a title="<?php _e( 'Mute', 'wpzabb' ); ?>" class="mute-video dashicons dashicons-controls-volumeon"></a>
								<a title="<?php _e( 'Unmute', 'wpzabb' ); ?>" class="unmute-video dashicons dashicons-controls-volumeoff"></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="wpzabb-slideshow-slide-details">
					<div class="wpzabb-slideshow-slide-details-wrap">
						<h4 class="wpzabb-slideshow-slide-title">
							<?php if ( !empty( $slide->link ) ) : ?>
								<a href="<?php echo esc_url( $slide->link ); ?>" title="<?php echo esc_attr( $slide->title ); ?>" target="<?php echo $slide->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $slide->link_target, $slide->link_nofollow, 1 ); ?>>
							<?php endif; ?>

							<?php echo esc_html( $slide->title ); ?>

							<?php if ( !empty( $slide->link ) ) : ?>
								</a>
							<?php endif; ?>
						</h4>

						<div class="wpzabb-slideshow-slide-content">
							<?php echo wp_kses_post( $slide->content ); ?>
						</div>

						<?php if ( 'yes' == $slide->button ) : ?>
							<div class="wpzabb-slideshow-slide-button">
								<a href="<?php echo esc_url( $slide->button_url ); ?>" title="<?php echo esc_attr( $slide->button_label ); ?>" target="<?php echo $slide->button_url_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $slide->button_url_target, $slide->button_url_nofollow, 1 ); ?>><?php echo $slide->button_label; ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</li>
		<?php endfor; ?>
	</ul>
</div>