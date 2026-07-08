<?php
$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
$heading_tag = in_array( $settings->tag, $allowed_tags, true ) ? $settings->tag : 'h1';
$separator_tag = isset( $settings->separator_text_tag_selection ) && in_array( $settings->separator_text_tag_selection, $allowed_tags, true ) ? $settings->separator_text_tag_selection : 'span';
?>
<div class="wpzabb-module-content wpzabb-heading-wrap wpzabb-heading-align-<?php echo esc_attr( $settings->alignment ); ?> <?php echo ( $settings->separator_style == 'line_text' ) ? esc_attr( $settings->responsive_compatibility ) : ''; ?>">

	<?php if( $settings->separator_position == 'top' ) { ?>
		 <div class="wpzabb-module-content wpzabb-separator-parent">
		 	<?php if( $settings->separator_style == 'line_icon' || $settings->separator_style == 'line_image' || $settings->separator_style == 'line_text' ) { ?>
		 		<div class="wpzabb-separator-wrap <?php echo 'wpzabb-separator-' . esc_attr( $settings->alignment ); ?> <?php echo ( $settings->separator_style == 'line_text' ) ? esc_attr( $settings->responsive_compatibility ) : ''; ?>" >
		 			<div class="wpzabb-separator-line wpzabb-side-left">
		 				<span></span>
		 			</div>
		 	        <div class="wpzabb-divider-content wpzabbi-divider">
		 				<?php $module->render_image(); ?>
		 				<?php if( $settings->separator_style == 'line_text' ) {
	 						echo '<' . $separator_tag . ' class="wpzabb-divider-text">' . esc_html( $settings->text_inline ) . '</' . $separator_tag . '>';
	 					}
		 				?>
		 	        </div>			 		    
		 		    <div class="wpzabb-separator-line wpzabb-side-right">
		 		    	<span></span>
		 		    </div> 
		 	    </div>
		 	<?php } ?>
		 	<?php if( $settings->separator_style == 'line' ) { ?>
	 			<div class="wpzabb-separator"></div>
		 	<?php } ?>
		 </div> 
	<?php } ?>

	<<?php echo $heading_tag; ?> class="wpzabb-heading">
		<?php if( !empty( $settings->link ) ) : ?>
			<a href="<?php echo esc_url( $settings->link ); ?>" title="<?php echo esc_attr( $settings->heading ); ?>" target="<?php echo esc_attr( $settings->link_target ); ?>" <?php WPZOOM_BB_Addon_Pack_Helper::get_link_rel( $settings->link_target, 0, 1 ); ?>>
			<?php endif; ?>
			<span class="wpzabb-heading-text"><?php echo wp_kses_post( $settings->heading ); ?></span>
			<?php if( !empty( $settings->link ) ) : ?>
			</a>
		<?php endif; ?>
	</<?php echo $heading_tag; ?>>

	<?php if($settings->separator_position == 'center') { ?>
		<div class="wpzabb-module-content wpzabb-separator-parent">			
			<?php if( $settings->separator_style == 'line_icon' || $settings->separator_style == 'line_image' || $settings->separator_style == 'line_text' ) { ?>
				<div class="wpzabb-separator-wrap <?php echo 'wpzabb-separator-' . esc_attr( $settings->alignment ); ?> <?php echo ( $settings->separator_style == 'line_text' ) ? esc_attr( $settings->responsive_compatibility ) : ''; ?>">
					<div class="wpzabb-separator-line wpzabb-side-left">
						<span></span>
					</div>
			        <div class="wpzabb-divider-content wpzabbi-divider">
						<?php $module->render_image(); ?>
						<?php if( $settings->separator_style == 'line_text' ) {
							echo '<' . $separator_tag . ' class="wpzabb-divider-text">' . esc_html( $settings->text_inline ) . '</' . $separator_tag . '>';
						} ?>
			        </div>
				    <div class="wpzabb-separator-line wpzabb-side-right">
				    	<span></span>
				    </div>
			    </div>
			<?php } ?>
			<?php if( $settings->separator_style == 'line' ) { ?>
					<div class="wpzabb-separator"></div>
			<?php } ?>
		</div>
    <?php } ?>

	<?php if( $settings->description != '' ) : ?>
		<div class="wpzabb-subheading wpzabb-text-editor">
			<?php echo wp_kses_post( $settings->description ); ?>
		</div>
	<?php endif; ?>

	<?php if($settings->separator_position == 'bottom') { ?>
		<div class="wpzabb-module-content wpzabb-separator-parent">			
			<?php if( $settings->separator_style == 'line_icon' || $settings->separator_style == 'line_image' || $settings->separator_style == 'line_text' ) { ?>
				<div class="wpzabb-separator-wrap <?php echo 'wpzabb-separator-' . esc_attr( $settings->alignment ); ?> <?php echo ( $settings->separator_style == 'line_text' ) ? esc_attr( $settings->responsive_compatibility ) : ''; ?>">
					<div class="wpzabb-separator-line wpzabb-side-left">
						<span></span>
					</div>

			        <div class="wpzabb-divider-content wpzabbi-divider">
						<?php $module->render_image(); ?>
						<?php if( $settings->separator_style == 'line_text' ){
								echo '<' . $separator_tag . ' class="wpzabb-divider-text">' . esc_html( $settings->text_inline ) . '</' . $separator_tag . '>';
							}
						?>
			        </div>
				    
				    <div class="wpzabb-separator-line wpzabb-side-right">
				    	<span></span>
				    </div> 
			    </div>
			<?php } ?>

			<?php if( $settings->separator_style == 'line' ) { ?>
				<div class="wpzabb-separator"></div>
			<?php } ?>
		</div>
	<?php } ?> 
</div>