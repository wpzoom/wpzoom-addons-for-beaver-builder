( function( $ ) {
	$( function() {
		$( '.wpzabb-slideshow' ).flexslider( {
			animation: '<?php echo $settings->slideshow_transition == 'slide-horizontal' || $settings->slideshow_transition == 'slide-vertical' ? 'slide' : 'fade'; ?>',
			animationLoop: <?php echo $settings->slideshow_loop == 'yes' ? 'true' : 'false'; ?>,
			animationSpeed: <?php echo intval( $settings->slideshow_transition_speed ); ?>,
			controlNav: <?php echo $settings->slideshow_navigation == 'thumbs' ? '\'thumbnails\'' : ( $settings->slideshow_navigation == 'dots' ? 'true' : 'false' ); ?>,
			direction: '<?php echo $settings->slideshow_transition == 'slide-vertical' ? 'vertical' : 'horizontal'; ?>',
			directionNav: <?php echo $settings->slideshow_arrows == 'yes' ? 'true' : 'false'; ?>,
			pauseOnHover: <?php echo $settings->slideshow_hoverpause == 'yes' ? 'true' : 'false'; ?>,
			randomize: <?php echo $settings->slideshow_shuffle == 'yes' ? 'true' : 'false'; ?>,
			reverse: <?php echo $settings->slideshow_direction == 'backward' ? 'true' : 'false'; ?>,
			selector: '.wpzabb-slideshow-slides > li',
			slideshow: <?php echo $settings->slideshow_auto == 'yes' ? 'true' : 'false'; ?>,
			slideshowSpeed: <?php echo intval( $settings->slideshow_speed ); ?>,
			smoothHeight: <?php echo $settings->slideshow_smootheight == 'yes' ? 'true' : 'false'; ?>,
			touch: true,
			useCSS: true,
			video: true
		} );
	} );
} )( jQuery );