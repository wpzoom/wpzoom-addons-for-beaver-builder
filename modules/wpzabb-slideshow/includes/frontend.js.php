<?php
define( 'WPZABB_SLIDESHOW_DEBUG', 0 );

?>( function( $ ) {
	var slider = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides' );

	slider.on( 'ready.flickity', onSlideshowStart );
	slider.on( 'change.flickity', onSlideshowChange );

	slider.flickity( {
		accessibility: true,
		adaptiveHeight: false,
		autoPlay: <?php echo 'yes' == $settings->slideshow_auto ? '' . intval( $settings->slideshow_speed ) : 'false'; ?>,
		cellAlign: 'center',
		cellSelector: '.wpzabb-slideshow-slide',
		contain: true,
		draggable: '>1',
		dragThreshold: 3,
		fade: <?php echo 'slide-horizontal' == $settings->slideshow_transition || 'slide-vertical' == $settings->slideshow_transition ? 'false' : 'true'; ?>,
		freeScroll: false,
		friction: <?php echo floatval( $settings->slideshow_transition_speed ); ?>,
		groupCells: false,
		imagesLoaded: true,
		initialIndex: 0,
		lazyLoad: true,
		pauseAutoPlayOnHover: <?php echo 'yes' == $settings->slideshow_hoverpause ? 'true' : 'false'; ?>,
		percentPosition: true,
		prevNextButtons: <?php echo 'yes' == $settings->slideshow_arrows ? 'true' : 'false'; ?>,
		pageDots: <?php echo 'dots' == $settings->slideshow_navigation ? 'true' : 'false'; ?>,
		resize: true,
		rightToLeft: <?php echo 'backward' == $settings->slideshow_direction ? 'true' : 'false'; ?>,
		setGallerySize: true,
		watchCSS: false,
		wrapAround: <?php echo 'yes' == $settings->slideshow_loop ? 'true' : 'false'; ?>
	} );

	<?php if ( 'thumbs' == $settings->slideshow_navigation ) : ?>
		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-thumbs-nav' ).flickity( {
			accessibility: true,
			adaptiveHeight: false,
			asNavFor: '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides',
			autoPlay: false,
			cellAlign: 'center',
			cellSelector: '.wpzabb-slideshow-thumbs-nav-item',
			contain: true,
			draggable: '>1',
			dragThreshold: 3,
			fade: false,
			freeScroll: false,
			groupCells: 4,
			imagesLoaded: true,
			initialIndex: 0,
			lazyLoad: true,
			percentPosition: true,
			prevNextButtons: true,
			pageDots: false,
			resize: false,
			rightToLeft: false,
			setGallerySize: true,
			watchCSS: false,
			wrapAround: <?php echo 'yes' == $settings->slideshow_loop ? 'true' : 'false'; ?>
		} );
	<?php endif; ?>

	$( function() {
<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>]%c Document ready!',
		               'color:grey', 'color:inherit' );
<?php endif; ?>

		window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReady;
		loadYouTubeAPI();
		loadVimeoAPI();
		loadHTMLVideoAPI();
	} );

	function onSlideshowStart() {
		var slides = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide' ),
		    slideVideoControls = slides.find( '.wpzabb-slideshow-slide-video .wpzabb-slideshow-slide-video-controls' );

		slideVideoControls.find( '.play-video' ).on( 'click', playVideo );
		slideVideoControls.find( '.pause-video' ).on( 'click', pauseVideo );
		slideVideoControls.find( '.mute-video' ).on( 'click', muteVideo );
		slideVideoControls.find( '.unmute-video' ).on( 'click', unmuteVideo );

		$( slides ).each( function() {
			var video = $( this ).find( '.wpzabb-slideshow-slide-video' );

			if ( video.length > 0 ) {
<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
				console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( video.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Plays on init: %c' + video.attr( 'data-autoplay' ),
				               'color:grey', 'color:inherit', 'font-weight:bold' );
<?php endif; ?>

				video.attr( 'data-was-playing', video.attr( 'data-autoplay' ) );

				togglePlayPauseButtons( 'true' == video.attr( 'data-autoplay' ), video );
				toggleMuteUnmuteButtons( 'true' == video.attr( 'data-muted' ), video );
			}
		} );
	}

	function onSlideshowChange() {
		var slides = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides .wpzabb-slideshow-slide' );

		$( slides ).each( function() {
			var video = $( this ).find( '.wpzabb-slideshow-slide-video' );

			if ( video.length > 0 ) {
				if ( $( this ).hasClass( 'is-selected' ) ) {
					if ( 'true' == video.attr( 'data-was-playing' ) ) {
<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
						console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( video.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Slide change. Slide is active. Playback %cWILL%c resume.',
						               'color:grey', 'color:inherit', 'font-weight:bold', 'font-weight:normal' );
<?php endif; ?>

						playVideo( { target: video } );
					} else {
<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
						console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( video.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Slide change. Slide is active. Playback will %cNOT%c resume.',
						               'color:grey', 'color:inherit', 'font-weight:bold', 'font-weight:normal' );
<?php endif; ?>
					}
				} else {
<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( video.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Slide change. Slide is inactive. Playback is paused.',
					               'color:grey', 'color:inherit' );
<?php endif; ?>

					video.attr( 'data-was-playing', videoIsPlaying( video ) );

					pauseVideo( { target: video } );
				}
			}
		} );
	}

	function togglePlayPauseButtons( play, slide ) {
		slide.find( '.wpzabb-slideshow-slide-video-controls .play-video' ).toggle( ! play );
		slide.find( '.wpzabb-slideshow-slide-video-controls .pause-video' ).toggle( play );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( slide.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback controls changed to: %c' + ( play ? 'play' : 'pause' ),
		               'color:grey', 'color:inherit', 'font-weight:bold' );
<?php endif; ?>
	}

	function toggleMuteUnmuteButtons( mute, slide ) {
		slide.find( '.wpzabb-slideshow-slide-video-controls .mute-video' ).toggle( ! mute );
		slide.find( '.wpzabb-slideshow-slide-video-controls .unmute-video' ).toggle( mute );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( slide.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio controls changed to: %c' + ( mute ? 'mute' : 'unmute' ),
		               'color:grey', 'color:inherit', 'font-weight:bold' );
<?php endif; ?>
	}

	function playVideo( event ) {
		var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Play triggered! Was already playing?: %c' + videoIsPlaying( target ),
		               'color:grey', 'color:inherit', 'font-weight:bold' );
<?php endif; ?>

		if ( 'youtube' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.video-embed' );

			if ( vid.length > 0 ) {
				var playr = YT.get( vid.attr( 'id' ) );
				
				if ( typeof playr !== 'undefined' ) {
					playr.playVideo();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback started!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		} else if ( 'vimeo' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.video-embed > iframe' );

			if ( vid.length > 0 ) {
				var playr = new Vimeo.Player( vid );
				
				if ( typeof playr !== 'undefined' ) {
					playr.play();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback started!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		} else if ( 'html' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

			if ( vid.length > 0 ) {
				vid[ 0 ].play();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
				console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback started!',
					           'color:grey', 'color:inherit' );
<?php endif; ?>
			}
		}
	}

	function videoIsPlaying( video ) {
		return typeof $( video ).attr( 'data-playing' ) !== 'undefined' ? 'true' == $( video ).attr( 'data-playing' ) : false;
	}

	function pauseVideo( event ) {
		var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Pause triggered! Was already playing?: %c' + videoIsPlaying( target ),
		               'color:grey', 'color:inherit', 'font-weight:bold' );
<?php endif; ?>

		if ( videoIsPlaying( target ) ) {
			if ( 'youtube' == target.attr( 'data-type' ) ) {
				var vid = target.find( '.video-embed' );

				if ( vid.length > 0 ) {
					var playr = YT.get( vid.attr( 'id' ) );
					
					if ( typeof playr !== 'undefined' ) {
						playr.pauseVideo();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
						console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback paused!',
						               'color:grey', 'color:inherit' );
<?php endif; ?>
					}
				}
			} else if ( 'vimeo' == target.attr( 'data-type' ) ) {
				var vid = target.find( '.video-embed > iframe' );

				if ( vid.length > 0 ) {
					var playr = new Vimeo.Player( vid );
					
					if ( typeof playr !== 'undefined' ) {
						playr.pause();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
						console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback paused!',
						               'color:grey', 'color:inherit' );
<?php endif; ?>
					}
				}
			} else if ( 'html' == target.attr( 'data-type' ) ) {
				var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

				if ( vid.length > 0 ) {
					vid[ 0 ].pause();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Playback paused!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		}
	}

	function muteVideo( event ) {
		var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Mute triggered!',
		               'color:grey', 'color:inherit' );
<?php endif; ?>

		if ( 'youtube' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.video-embed' );

			if ( vid.length > 0 ) {
				var playr = YT.get( vid.attr( 'id' ) );
				
				if ( typeof playr !== 'undefined' ) {
					playr.setVolume( 0 );
					playr.mute();

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio muted!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		} else if ( 'vimeo' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.video-embed > iframe' );

			if ( vid.length > 0 ) {
				var playr = new Vimeo.Player( vid );
				
				if ( typeof playr !== 'undefined' ) {
					playr.setVolume( 0 );
					playr.setMuted( true );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio muted!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		} else if ( 'html' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

			if ( vid.length > 0 ) {
				vid[ 0 ].setVolume( 0 );
				vid[ 0 ].setMuted( true );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
				console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio muted!',
				               'color:grey', 'color:inherit' );
<?php endif; ?>
			}
		}
	}

	function unmuteVideo( event ) {
		var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Unmute triggered!',
		               'color:grey', 'color:inherit' );
<?php endif; ?>

		if ( 'youtube' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.video-embed' );

			if ( vid.length > 0 ) {
				var playr = YT.get( vid.attr( 'id' ) );
				
				if ( typeof playr !== 'undefined' ) {
					playr.unMute();
					playr.setVolume( 100 );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio unmuted!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		} else if ( 'vimeo' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.video-embed > iframe' );

			if ( vid.length > 0 ) {
				var playr = new Vimeo.Player( vid );
				
				if ( typeof playr !== 'undefined' ) {
					playr.setMuted( false );
					playr.setVolume( 1 );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
					console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio unmuted!',
					               'color:grey', 'color:inherit' );
<?php endif; ?>
				}
			}
		} else if ( 'html' == target.attr( 'data-type' ) ) {
			var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

			if ( vid.length > 0 ) {
				vid[ 0 ].setMuted( false );
				vid[ 0 ].setVolume( 1 );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
				console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( target.closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Audio unmuted!',
				               'color:grey', 'color:inherit' );
<?php endif; ?>
			}
		}
	}

	function onPlaybackStateChange( play, slide ) {
		togglePlayPauseButtons( play, slide );
	}

	function onMuteStateChange( mute, slide ) {
		toggleMuteUnmuteButtons( mute, slide );
	}

	function loadYouTubeAPI() {
		var tag = document.createElement( 'script' );
		tag.src = 'https://www.youtube.com/iframe_api';
		var firstScriptTag = document.getElementsByTagName( 'script' )[ 0 ];
		firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
		console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>]%c YouTube API script tag has been injected!',
		               'color:grey', 'color:inherit' );
<?php endif; ?>
	}

	function loadVimeoAPI() {
		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video[data-type="vimeo"]' ).each( function() {
			var player = new Vimeo.Player( $( this ).find( '.video-embed' )[ 0 ], {
				height: '390',
				width: '640',
				id: $( this ).attr( 'data-id' ),
				controls: false,
				muted: 'true' == $( this ).attr( 'data-startmuted' ),
				loop: 'true' == $( this ).attr( 'data-loop' )
			} );

			player.on( 'loaded', onVimeoPlayerReady );
			player.on( 'play', function( event ) { event.type = 'play'; $.proxy( onVimeoPlayerStateChange, this, event )(); } );
			player.on( 'pause', function( event ) { event.type = 'pause'; $.proxy( onVimeoPlayerStateChange, this, event )(); } );
			player.on( 'volumechange', function( event ) { event.type = 'volumechange'; $.proxy( onVimeoMuteStateChange, this, event )(); } );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
			console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( $( this ).closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c Vimeo API loaded!',
			               'color:grey', 'color:inherit' );
<?php endif; ?>
		} );
	}

	function loadHTMLVideoAPI() {
		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video[data-type="html"]' ).each( function() {
			var video = $( this ).find( '.wp-video-shortcode' );

			video.on( 'loadeddata', onHtmlPlayerReady );
			video.on( 'play', onHtmlPlayerStateChange );
			video.on( 'pause', onHtmlPlayerStateChange );
			video.on( 'volumechange', onHtmlMuteStateChange );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
			console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( $( this ).closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c HTML Video API loaded!',
			               'color:grey', 'color:inherit' );
<?php endif; ?>
		} );
	}

	function onYouTubeIframeAPIReady() {
		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video[data-type="youtube"]' ).each( function() {
			new YT.Player( $( this ).find( '.video-embed' )[ 0 ], {
				height: '390',
				width: '640',
				videoId: $( this ).attr( 'data-id' ),
				playerVars: {
					'loop': 'true' == $( this ).attr( 'data-loop' ) ? 1 : 0
				},
				events: {
					'onReady': onYouTubePlayerReady,
					'onStateChange': onYouTubePlayerStateChange
				}
			} );

<?php if ( 1 == WPZABB_SLIDESHOW_DEBUG ) : ?>
			console.debug( '%c[WPZABB Slideshow Module <?php echo $id; ?>][Slide #' + ( $( this ).closest( '.wpzabb-slideshow-slide' ).index() + 1 ) + ']%c YouTube API loaded!',
			               'color:grey', 'color:inherit' );
<?php endif; ?>
		} );
	}

	function onYouTubePlayerReady( event ) {
		if ( typeof event !== 'undefined' && typeof event.target !== 'undefined' && typeof event.target.a !== 'undefined' ) {
			var target = $( event.target.a ),
			    slide = target.closest( '.wpzabb-slideshow-slide' ),
			    video = target.closest( '.wpzabb-slideshow-slide-video' );

			if ( 'true' == video.attr( 'data-startmuted' ) ) {
				event.target.mute();
			} else {
				event.target.unMute();
				event.target.setVolume( 100 );
			}

			if ( slide.hasClass( 'is-selected' ) && 'true' == video.attr( 'data-autoplay' ) ) {
				event.target.playVideo();
			}

			setInterval( function() {
				var muted = event.target.isMuted(),
				    muted_str = muted ? 'true' : 'false';

				if ( video.attr( 'data-muted' ) != muted_str ) {
					onYouTubeMuteStateChange( muted, slide );

					video.attr( 'data-muted', muted );
				}
			}, 500 );
		}
	}

	function onYouTubePlayerStateChange( event ) {
		var target = $( event.target.a ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' ),
		    play = event.data == YT.PlayerState.PLAYING;

		video.attr( 'data-playing', play );

		onPlaybackStateChange( play, slide );
	}

	function onYouTubeMuteStateChange( muted, slide ) {
		onMuteStateChange( muted, slide );
	}

	function onVimeoPlayerReady( data ) {
		var target = $( this ).closest( '.video-embed' ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' );

		if ( target.length > 0 ) {
			var playr = new Vimeo.Player( $( this ) );

			if ( typeof playr !== 'undefined' ) {
				var startmuted = 'true' == video.attr( 'data-startmuted' );
				playr.setMuted( startmuted );
				playr.setVolume( startmuted ? 0 : 1 );

				if ( slide.hasClass( 'is-selected' ) && 'true' == video.attr( 'data-autoplay' ) ) {
					playr.play();
				}
			}
		}
	}

	function onVimeoPlayerStateChange( event ) {
		var target = $( this.element ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' ),
		    play = event.type == 'play';

		video.attr( 'data-playing', play );

		onPlaybackStateChange( play, slide );
	}

	function onVimeoMuteStateChange( event ) {
		var target = $( this.element ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' ),
		    mute = event.type == 'volumechange' && event.volume <= 0;

		video.attr( 'data-muted', mute );

		onMuteStateChange( mute, slide );
	}

	function onHtmlPlayerReady( event ) {
		var target = $( event.target ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' );

		if ( slide.hasClass( 'is-selected' ) && 'true' == video.attr( 'data-autoplay' ) ) {
			var view = target.closest( 'mediaelementwrapper' );

			if ( view.length > 0 ) {
				view = view[ 0 ];

				var startmuted = 'true' == video.attr( 'data-startmuted' );
				view.setMuted( startmuted );
				if ( ! startmuted ) view.setVolume( 1 );

				view.play();
			}
		}
	}

	function onHtmlPlayerStateChange( event ) {
		var target = $( event.target ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' ),
		    play = event.type == 'play';

		video.attr( 'data-playing', play );

		onPlaybackStateChange( play, slide );
	}

	function onHtmlMuteStateChange( event ) {
		var target = $( event.target ),
		    slide = target.closest( '.wpzabb-slideshow-slide' ),
		    video = target.closest( '.wpzabb-slideshow-slide-video' ),
		    mute = event.type == 'volumechange' && event.target.volume <= 0;

		video.attr( 'data-muted', mute );

		onMuteStateChange( mute, slide );
	}
} )( jQuery );