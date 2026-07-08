<?php
$auto_height             = array();
$auto_height['']         = 'yes' == $settings->slideshow_autoheight;
$auto_height['large']    = empty( $settings->slideshow_autoheight_large ) ? $auto_height[''] : ( 'yes' == $settings->slideshow_autoheight_large );
$auto_height['medium']   = empty( $settings->slideshow_autoheight_medium ) ? $auto_height['large'] : ( 'yes' == $settings->slideshow_autoheight_medium );
$auto_height['responsive'] = empty( $settings->slideshow_autoheight_responsive ) ? $auto_height['medium'] : ( 'yes' == $settings->slideshow_autoheight_responsive );

$auto_height_size             = array();
$auto_height_size['']         = empty( $settings->slideshow_autoheight_size ) ? 100 : intval( $settings->slideshow_autoheight_size );
$auto_height_size['large']    = empty( $settings->slideshow_autoheight_size_large ) ? $auto_height_size[''] : intval( $settings->slideshow_autoheight_size_large );
$auto_height_size['medium']   = empty( $settings->slideshow_autoheight_size_medium ) ? $auto_height_size['large'] : intval( $settings->slideshow_autoheight_size_medium );
$auto_height_size['responsive'] = empty( $settings->slideshow_autoheight_size_responsive ) ? $auto_height_size['medium'] : intval( $settings->slideshow_autoheight_size_responsive );

$auto_height_max             = array();
$auto_height_max['']         = empty( $settings->slideshow_autoheight_max ) ? 550 : intval( $settings->slideshow_autoheight_max );
$auto_height_max['large']    = empty( $settings->slideshow_autoheight_max_large ) ? $auto_height_max[''] : intval( $settings->slideshow_autoheight_max_large );
$auto_height_max['medium']   = empty( $settings->slideshow_autoheight_max_medium ) ? $auto_height_max['large'] : intval( $settings->slideshow_autoheight_max_medium );
$auto_height_max['responsive'] = empty( $settings->slideshow_autoheight_max_responsive ) ? $auto_height_max['medium'] : intval( $settings->slideshow_autoheight_max_responsive );

foreach ( array( '', 'large', 'medium', 'responsive' ) as $device ) {
	$key_size = empty( $device ) ? 'slideshow_autoheight_size' : "slideshow_autoheight_size_{$device}";
	
	FLBuilderCSS::rule( array(
		'media'    => $device,
		'enabled'  => $auto_height[ $device ] && ( empty( $device ) ? true : ! empty( $settings->{ $key_size } ) ),
		'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slides, .fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slides .flickity-viewport, .fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slides .flickity-slider, .fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide, .fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide-outer-wrap",
		'props'    => array(
			'height'    => $auto_height_size[ $device ] . 'vh',
			'max-height' => $auto_height_max[ $device ] . 'px',
		),
	) );
}
?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide {
	background-color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_background_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-image::after {
	background-image: <?php echo FLBuilderColor::gradient( $settings->slide_overlay_gradient ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_title_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title,
.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_title_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-title a:hover {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_title_hover_color ); ?>;
}

<?php echo FLBuilderCSS::typography_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'slide_content_font',
	'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content"
) ); ?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-content {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_content_color ); ?>;
}

<?php
$button_align             = array();
$button_align['']         = empty( $settings->slide_button_align ) ? 'left' : $settings->slide_button_align;
$button_align['large']    = empty( $settings->slide_button_align_large ) ? $button_align[''] : $settings->slide_button_align_large;
$button_align['medium']   = empty( $settings->slide_button_align_medium ) ? $button_align['large'] : $settings->slide_button_align_medium;
$button_align['responsive'] = empty( $settings->slide_button_align_responsive ) ? $button_align['medium'] : $settings->slide_button_align_responsive;

foreach ( array( '', 'large', 'medium', 'responsive' ) as $device ) {
	$key = empty( $device ) ? 'slide_button_align' : "slide_button_align_{$device}";
	
	FLBuilderCSS::rule( array(
		'media'    => $device,
		'enabled'  => empty( $device ) ? true : ! empty( $settings->{ $key } ),
		'selector' => ".fl-node-$id .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button",
		'props'    => array(
			'text-align' => $button_align[ $device ],
		),
	) );
}
?>

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-details .wpzabb-slideshow-slide-button a {
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_color ); ?>;
	background-color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_background_color ); ?>;
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
	color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_hover_color ); ?>;
	background-color: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_button_hover_background_color ); ?>;
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

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flickity-prev-next-button path {
	fill: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_navigation_color ); ?>;
}

.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flickity-prev-next-button:hover path {
	fill: <?php echo WPZABB_Helper::maybe_prepend_hash( $settings->slide_navigation_hover_color ); ?>;
}
