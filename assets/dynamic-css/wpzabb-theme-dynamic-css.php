<?php ob_start(); 

/**
 * Write Your Dynamic CSS Below This
 */ 
?>

/* Theme Button
------------------------------------------------------ */
<?php $wpzabb_theme_btn_family = apply_filters( 'wpzabb/theme/button_font_family', '' ); ?>
/*.fl-builder-content a.wpzabb-button,
.fl-builder-content a.wpzabb-button:visited,
.fl-builder-content a.wpzabb-creative-button,
.fl-builder-content a.wpzabb-creative-button:visited*/

.wpzabb-creative-button-wrap a,
.wpzabb-creative-button-wrap a:visited {
	
	<?php if( isset( $wpzabb_theme_btn_family['family'] ) ) { ?>
	font-family: <?php echo $wpzabb_theme_btn_family['family']; ?>;
	<?php } ?> 
	
	<?php if ( isset( $wpzabb_theme_btn_family['weight'] ) ) { ?>
	font-weight: <?php echo $wpzabb_theme_btn_family['weight']; ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_font_size('') != '' ) { ?>
	font-size: <?php echo wpzabb_theme_button_font_size(''); ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_line_height('') != '' ) { ?>
	line-height: <?php echo wpzabb_theme_button_line_height(''); ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_letter_spacing('') != '' ) { ?>
	letter-spacing: <?php echo wpzabb_theme_button_letter_spacing(''); ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_text_transform('') != '' ) { ?>
	text-transform: <?php echo wpzabb_theme_button_text_transform(''); ?>;
	<?php } ?>
}

.wpzabb-dual-button .wpzabb-btn,
.wpzabb-dual-button .wpzabb-btn:visited {
	<?php if( isset( $wpzabb_theme_btn_family['family'] ) ) { ?>
	font-family: <?php echo $wpzabb_theme_btn_family['family']; ?>;
	<?php } ?> 
	
	<?php if ( isset( $wpzabb_theme_btn_family['weight'] ) ) { ?>
	font-weight: <?php echo $wpzabb_theme_btn_family['weight']; ?>;
	<?php } ?>
		
	<?php if ( wpzabb_theme_button_font_size('') != '' ) { ?>
	font-size: <?php echo wpzabb_theme_button_font_size(''); ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_line_height('') != '' ) { ?>
	line-height: <?php echo wpzabb_theme_button_line_height(''); ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_letter_spacing('') != '' ) { ?>
	letter-spacing: <?php echo wpzabb_theme_button_letter_spacing(''); ?>;
	<?php } ?>

	<?php if ( wpzabb_theme_button_text_transform('') != '' ) { ?>
	text-transform: <?php echo wpzabb_theme_button_text_transform(''); ?>;
	<?php } ?>
}


/* Responsive Js Breakpoint Css */

#wpzabb-js-breakpoint { 
	content:"default"; 
	display:none;
}
<?php if($global_settings->responsive_enabled) { ?>
@media screen and (max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?>) {
	#wpzabb-js-breakpoint {
		content:"<?php echo $global_settings->medium_breakpoint; ?>";
	}
}

@media screen and (max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?>) {
	#wpzabb-js-breakpoint {
		content:"<?php echo $global_settings->responsive_breakpoint; ?>";
	}
}
<?php } ?>


<?php
/**
 * Write Your Dynamic CSS Above This 
 */

	return ob_get_clean(); 
?>