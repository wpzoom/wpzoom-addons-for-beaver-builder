<?php
	$image_box_class = 'wpzabb-image-box-wrap ' . $settings->layout . ' content-align-'. $settings->content_align;
?>
<div class="<?php echo $image_box_class; ?>">

	<div class="wpzabb-images">
		<?php
		for ( $i = 0; $i < count( $settings->images ); $i++ ) :

			if ( ! is_object( $settings->images[ $i ] ) ) {
				continue;
			} else {
				$image = $settings->images[ $i ];
			}

			$classes  = $module->get_classes( $image );
			$src      = $module->get_src( $image );
			$alt      = $module->get_alt( $image );
			$bg_image = sprintf( 'background-image: url(%s);', esc_url($src) );
		?>
		<figure class="wpzabb-image" style="<?php echo $bg_image; ?>">
			<?php if( !empty( $image->link ) ) : ?>
				<a href="<?php echo $image->link; ?>" class="wpzabb-image-overlay-link" title="<?php echo $image->heading; ?>" target="<?php echo $image->link_target; ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $image->link_target, 0, 1 ); ?>></a>
			<?php endif; ?>
			<div class="wpzabb-image-image hidden" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo $classes; ?>" src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" itemprop="image"/>
			</div>
			<figcaption class="wpzabb-image-caption">
				<<?php echo $settings->tag; ?> class="wpzabb-image-heading"><?php echo $image->heading ?></<?php echo $settings->tag; ?>>
				<?php if ( !empty( $image->subheading ) ): ?>
					<span class="wpzabb-image-subheading"><?php echo $image->subheading ?></span>
				<?php endif ?>
				<?php if ( !empty( $image->description ) ): ?>
					<div class="wpzabb-image-description"><?php echo $image->description ?></div>
				<?php endif ?>
				<?php $module->render_button( $image ); ?>
			</figcaption>
		</figure>
		<?php endfor; ?>
	</div>

</div>
