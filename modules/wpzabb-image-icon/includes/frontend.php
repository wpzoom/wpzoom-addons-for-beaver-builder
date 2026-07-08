<?php if( $settings->image_type != 'none' && $settings->image_type != '' ) { ?>
<div class="wpzabb-module-content wpzabb-imgicon-wrap"><?php /* Module Wrap */ ?>
	<?php /*Icon Html */ ?>
	<?php if( $settings->image_type == 'icon' ) { ?>
		<span class="wpzabb-icon-wrap">
			<span class="wpzabb-icon">
				<i class="<?php echo esc_attr( $settings->icon ); ?>"></i>
			</span>
		</span>
	<?php } // Icon Html End ?>

	<?php if( $settings->image_type == 'photo' ) { // Photo Html ?>
		<?php
			$classes  = $module->get_classes();
			$src      = $module->get_src();
			$alt      = $module->get_alt();
		?>
		<div class="wpzabb-image<?php if ( ! empty( $settings->image_style ) ) echo ' wpzabb-image-crop-' . esc_attr( $settings->image_style ); ?>" itemscope itemtype="http://schema.org/ImageObject">
			<div class="wpzabb-image-content">
				<img class="<?php echo esc_attr( $classes ); ?>" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" itemprop="image"/>
			</div>
		</div>

	<?php } // Photo Html End ?>
	</div><?php /* End Module Wrap */ ?>
<?php } ?>