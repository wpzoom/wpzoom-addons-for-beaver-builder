<?php
	$image_box_class = 'wpzabb-image-box-wrap';
	$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
	$heading_tag = in_array( $settings->tag, $allowed_tags, true ) ? $settings->tag : 'h3';
?>
<div class="<?php echo esc_attr( $image_box_class ); ?>">

	<div class="wpzabb-images">
		<?php
			$classes  = $module->get_classes();
			$src      = $module->get_src();
			$alt      = $module->get_alt();
			$bg_image = sprintf( 'background-image: url(%s);', esc_url($src) );
		?>
		<figure class="wpzabb-image" style="<?php echo $bg_image; ?>">
			<?php if( !empty( $settings->link ) ) : ?>
				<a href="<?php echo esc_url( $settings->link ); ?>" class="wpzabb-image-overlay-link" title="<?php echo esc_attr( $settings->heading ); ?>" target="<?php echo esc_attr( $settings->link_target ); ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $settings->link_target, 0, 1 ); ?>></a>
			<?php endif; ?>
			<div class="wpzabb-image-image hidden" itemscope itemtype="http://schema.org/ImageObject">
				<img class="<?php echo esc_attr( $classes ); ?>" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" itemprop="image"/>
			</div>
			<figcaption class="wpzabb-image-caption">
				<<?php echo $heading_tag; ?> class="wpzabb-image-heading"><?php echo esc_html( $settings->heading ); ?></<?php echo $heading_tag; ?>>
				<?php if ( !empty( $settings->subheading ) ): ?>
					<span class="wpzabb-image-subheading"><?php echo esc_html( $settings->subheading ); ?></span>
				<?php endif ?>
				<?php if ( !empty( $settings->description ) ): ?>
					<div class="wpzabb-image-description"><?php echo wp_kses_post( $settings->description ); ?></div>
				<?php endif ?>
				<?php $module->render_button(); ?>
			</figcaption>
		</figure>
	</div>

</div>
