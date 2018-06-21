<div class="wpzabb-module-content <?php echo $module->get_classname(); ?>">
	<?php 
	if ( isset( $settings->threed_button_options ) && ( $settings->threed_button_options == "animate_top" || $settings->threed_button_options == "animate_bottom" || $settings->threed_button_options == "animate_left" || $settings->threed_button_options == "animate_right" ) ) {
	?>
		<p class="perspective">
	<?php
	}
	?>
	
		<a href="<?php echo $settings->link; ?>" target="<?php echo $settings->link_target; ?>" class="wpzabb-button wpzabb-creative-button <?php echo 'wpzabb-creative-'.$settings->style.'-btn' ?> <?php echo $module->get_button_style(); ?> <?php echo ( isset( $settings->a_class ) ) ? $settings->a_class : '' ; ?> " <?php echo ( isset( $settings->a_data ) ) ? $settings->a_data : '' ; ?> role="button"<?php echo $module->get_rel(); ?>>
			<?php if ( ! empty( $settings->icon ) && ( 'before' == $settings->icon_position || ! isset( $settings->icon_position ) ) ) : 

			if ( $settings->style == 'flat' && isset( $settings->flat_button_options ) && ( $settings->flat_button_options == "animate_to_right" || $settings->flat_button_options == "animate_to_left" || $settings->flat_button_options == "animate_from_top" || $settings->flat_button_options == "animate_from_bottom" ) ) {
				$add_class_to_icon = "";
			}else{
				$add_class_to_icon = "wpzabb-button-icon-before wpzabb-creative-button-icon-before";
			}
			?>
				<i class="wpzabb-button-icon wpzabb-creative-button-icon <?php echo $add_class_to_icon;?> fa <?php echo $settings->icon; ?>"></i>
			<?php endif; ?>
			<span class="wpzabb-button-text wpzabb-creative-button-text"><?php echo $settings->text; ?></span>
			<?php if ( ! empty( $settings->icon ) && 'after' == $settings->icon_position ) : 

			if ( $settings->style == 'flat' && isset( $settings->flat_button_options ) && ( $settings->flat_button_options == "animate_to_right" || $settings->flat_button_options == "animate_to_left" || $settings->flat_button_options == "animate_from_top" || $settings->flat_button_options == "animate_from_bottom" ) ) {
				$add_class_to_icon = "";
			}else{
				$add_class_to_icon = "wpzabb-button-icon-after wpzabb-creative-button-icon-after";
			}
			?>
				<i class="wpzabb-button-icon wpzabb-creative-button-icon <?php echo $add_class_to_icon;?> fa <?php echo $settings->icon; ?>"></i>
			<?php endif; ?>

		</a>
	<?php 
	if ( isset( $settings->threed_button_options ) && ( $settings->threed_button_options == "animate_top" || $settings->threed_button_options == "animate_bottom" || $settings->threed_button_options == "animate_left" || $settings->threed_button_options == "animate_right" ) ) {
	?>
		</p>
	<?php
	}
	?>
</div>