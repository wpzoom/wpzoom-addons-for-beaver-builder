<?php
function is_hex_color( $string )
{
	$color = ltrim( $string, '#' );
	return !empty( $color ) && ctype_xdigit( $color ) && ( strlen( $color ) == 6 || strlen( $color ) == 3 );
}

function maybe_prepend_hash( $string )
{
	return ( is_hex_color( $string ) ? '#' : '' ) . $string;
}

$auto_height = $settings->slideshow_autoheight == 'yes';
$auto_height_size = intval( $settings->slideshow_autoheight_size );
$auto_height_max = intval( $settings->slideshow_autoheight_max );
?>

<?php if ( $auto_height ) : ?>
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-viewport,
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides,
	.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide {
		height: <?php echo $auto_height_size; ?>vh;
		max-height: <?php echo $auto_height_max; ?>px;
	}
<?php endif; ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide {
	background-color: <?php echo maybe_prepend_hash( $settings->slide_background_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-image::after,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video::after {
	background-image: <?php echo FLBuilderColor::gradient( $settings->slide_overlay_gradient ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_title_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a {
	color: <?php echo maybe_prepend_hash( $settings->slide_title_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a:hover {
	color: <?php echo maybe_prepend_hash( $settings->slide_title_hover_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_content_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content {
	color: <?php echo maybe_prepend_hash( $settings->slide_content_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a {
	color: <?php echo maybe_prepend_hash( $settings->slide_button_color ); ?>;
	background-color: <?php echo maybe_prepend_hash( $settings->slide_button_background_color ); ?>;
}

<?php echo FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_border',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a"
) ); ?>

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover {
	color: <?php echo maybe_prepend_hash( $settings->slide_button_hover_color ); ?>;
	background-color: <?php echo maybe_prepend_hash( $settings->slide_button_hover_background_color ); ?>;
}

<?php echo FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_hover_border',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover"
) ); ?>

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_button_hover_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a:hover"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a::before {
	color: <?php echo maybe_prepend_hash( $settings->slide_navigation_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:hover,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:active,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:hover::before,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav a:active::before {
	color: <?php echo maybe_prepend_hash( $settings->slide_navigation_hover_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video .wpzabb-slideshow-slide-video-controls a {
	color: <?php echo maybe_prepend_hash( $settings->slide_video_controls_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video .wpzabb-slideshow-slide-video-controls a:hover {
	color: <?php echo maybe_prepend_hash( $settings->slide_video_controls_hover_color ); ?>;
}