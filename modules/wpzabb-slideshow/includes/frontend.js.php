( function( $ ) {
	$( function() {
		var player;

		loadYouTubeAPI();

		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow' ).flexslider( {
			animation: '<?php echo $settings->slideshow_transition == 'slide-horizontal' || $settings->slideshow_transition == 'slide-vertical' ? 'slide' : 'fade'; ?>',
			animationLoop: <?php echo $settings->slideshow_loop == 'yes' ? 'true' : 'false'; ?>,
			animationSpeed: <?php echo intval( $settings->slideshow_transition_speed ); ?>,
			controlNav: <?php echo $settings->slideshow_navigation == 'thumbs' ? '\'thumbnails\'' : ( $settings->slideshow_navigation == 'dots' ? 'true' : 'false' ); ?>,
			direction: '<?php echo $settings->slideshow_transition == 'slide-vertical' ? 'vertical' : 'horizontal'; ?>',
			directionNav: <?php echo $settings->slideshow_arrows == 'yes' ? 'true' : 'false'; ?>,
			nextText: '',
			pauseOnHover: <?php echo $settings->slideshow_hoverpause == 'yes' ? 'true' : 'false'; ?>,
			prevText: '',
			randomize: <?php echo $settings->slideshow_shuffle == 'yes' ? 'true' : 'false'; ?>,
			reverse: <?php echo $settings->slideshow_direction == 'backward' ? 'true' : 'false'; ?>,
			selector: '.wpzabb-slideshow-slides > li',
			slideshow: <?php echo $settings->slideshow_auto == 'yes' ? 'true' : 'false'; ?>,
			slideshowSpeed: <?php echo intval( $settings->slideshow_speed ); ?>,
			smoothHeight: false,
			start: onSlideshowStart,
			touch: true,
			useCSS: true,
			video: true
		} );

		function onSlideshowStart( slider ) {
			var slides = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides > .wpzabb-slideshow-slide' ),
			    heights = slides.map( function() { return $( this ).height(); } ).get(),
			    maxHeight = Math.max.apply( null, heights );

			slides.css( 'min-height', maxHeight + 'px' );

			$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav' ).height( maxHeight + 'px' );
		}

		function loadYouTubeAPI() {
			var tag = document.createElement( 'script' );
			tag.src = 'https://www.youtube.com/iframe_api';
			var firstScriptTag = document.getElementsByTagName( 'script' )[0];
			firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );
		}

		window.onYouTubeIframeAPIReady = function() {
			$( '.wpzabb-slideshow .video-embed[data-type="youtube"]' ).each( function() {
				var id = $( this ).data( 'id' ),
				    auto = $( this ).data( 'autoplay' ),
				    loop = $( this ).data( 'loop' );

				player = new YT.Player( $( this )[ 0 ], {
					height: '390',
					width: '640',
					videoId: id,
					events: {
						'onReady': onPlayerReady,
						//'onStateChange': onPlayerStateChange
					}
				} );
			} );
		}

		function onPlayerReady( event ) {
			console.error('onPlayerReady called!');
		}
	} );
} )( jQuery );