( function( $ ) {
	$( function() {
		$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow' ).flexslider( {
			after: onSlideshowChange,
			animation: '<?php echo 'slide-horizontal' == $settings->slideshow_transition || 'slide-vertical' == $settings->slideshow_transition ? 'slide' : 'fade'; ?>',
			animationLoop: <?php echo 'yes' == $settings->slideshow_loop ? 'true' : 'false'; ?>,
			animationSpeed: <?php echo intval( $settings->slideshow_transition_speed ); ?>,
			controlNav: <?php echo 'thumbs' == $settings->slideshow_navigation ? '\'thumbnails\'' : ( 'dots' == $settings->slideshow_navigation ? 'true' : 'false' ); ?>,
			desktopTouch: true,
			direction: '<?php echo 'slide-vertical' == $settings->slideshow_transition ? 'vertical' : 'horizontal'; ?>',
			directionNav: <?php echo 'yes' == $settings->slideshow_arrows ? 'true' : 'false'; ?>,
			nextText: '',
			pauseOnHover: <?php echo 'yes' == $settings->slideshow_hoverpause ? 'true' : 'false'; ?>,
			prevText: '',
			randomize: <?php echo 'yes' == $settings->slideshow_shuffle ? 'true' : 'false'; ?>,
			reverse: <?php echo 'backward' == $settings->slideshow_direction ? 'true' : 'false'; ?>,
			selector: '.wpzabb-slideshow-slides > li',
			slideshow: <?php echo 'yes' == $settings->slideshow_auto ? 'true' : 'false'; ?>,
			slideshowSpeed: <?php echo intval( $settings->slideshow_speed ); ?>,
			smoothHeight: false,
			start: onSlideshowStart,
			touch: true,
			useCSS: true,
			video: true
		} );

		function onSlideshowStart( slider ) {
			var slides = $( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slides > .wpzabb-slideshow-slide' ),
			    slideVideoControls = slider.slides.find( '.wpzabb-slideshow-slide-video .wpzabb-slideshow-slide-video-controls' ),
			    heights = slides.map( function() { return $( this ).height(); } ).get(),
			    maxHeight = Math.max.apply( null, heights );

			slides.css( 'min-height', maxHeight + 'px' );

			$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .flex-direction-nav' ).height( maxHeight + 'px' );

			slideVideoControls.find( '.play-video' ).on( 'click', playVideo );
			slideVideoControls.find( '.pause-video' ).on( 'click', pauseVideo );
			slideVideoControls.find( '.mute-video' ).on( 'click', muteVideo );
			slideVideoControls.find( '.unmute-video' ).on( 'click', unmuteVideo );

			$( slider.slides ).each( function( i ) {
				var video = $( this ).find( '.wpzabb-slideshow-slide-video' );

				if ( video.length > 0 ) {
					video.data( 'was-playing', video.data( 'autoplay' ) );

					togglePlayPauseButtons( video.data( 'autoplay' ), video );
					toggleMuteUnmuteButtons( video.data( 'muted' ), video );
				}
			} );
		}

		function onSlideshowChange( slider ) {
			$( slider.slides ).each( function( i ) {
				var video = $( this ).find( '.wpzabb-slideshow-slide-video' );

				if ( video.length > 0 ) {
					if ( $( this ).hasClass( 'flex-active-slide' ) ) {
						if ( video.data( 'was-playing' ) ) {
							playVideo( { target: video } );
						}
					} else {
						video.data( 'was-playing', videoIsPlaying( video ) );

						pauseVideo( { target: video } );
					}
				}
			} );
		}

		function togglePlayPauseButtons( play, slide ) {
			slide.find( '.wpzabb-slideshow-slide-video-controls .play-video' ).toggle( ! play );
			slide.find( '.wpzabb-slideshow-slide-video-controls .pause-video' ).toggle( play );
		}

		function toggleMuteUnmuteButtons( mute, slide ) {
			slide.find( '.wpzabb-slideshow-slide-video-controls .mute-video' ).toggle( ! mute );
			slide.find( '.wpzabb-slideshow-slide-video-controls .unmute-video' ).toggle( mute );
		}

		function playVideo( event ) {
			var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

			if ( 'youtube' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed' );

				if ( vid.length > 0 ) {
					var playr = YT.get( vid.attr( 'id' ) );
					
					if ( typeof playr !== 'undefined' ) {
						playr.playVideo();
					}
				}
			} else if ( 'vimeo' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed > iframe' );

				if ( vid.length > 0 ) {
					var playr = new Vimeo.Player( vid );
					
					if ( typeof playr !== 'undefined' ) {
						playr.play();
					}
				}
			} else if ( 'html' == target.data( 'type' ) ) {
				var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

				if ( vid.length > 0 ) {
					vid[ 0 ].play();
				}
			}
		}

		function videoIsPlaying( slide ) {
			return typeof $( slide ).data( 'playing' ) !== 'undefined' ? $( slide ).data( 'playing' ) : false;
		}

		function pauseVideo( event ) {
			var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

			if ( 'youtube' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed' );

				if ( vid.length > 0 ) {
					var playr = YT.get( vid.attr( 'id' ) );
					
					if ( typeof playr !== 'undefined' ) {
						playr.pauseVideo();
					}
				}
			} else if ( 'vimeo' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed > iframe' );

				if ( vid.length > 0 ) {
					var playr = new Vimeo.Player( vid );
					
					if ( typeof playr !== 'undefined' ) {
						playr.pause();
					}
				}
			} else if ( 'html' == target.data( 'type' ) ) {
				var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

				if ( vid.length > 0 ) {
					vid[ 0 ].pause();
				}
			}
		}

		function muteVideo( event ) {
			var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

			if ( 'youtube' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed' );

				if ( vid.length > 0 ) {
					var playr = YT.get( vid.attr( 'id' ) );
					
					if ( typeof playr !== 'undefined' ) {
						playr.setVolume( 0 );
						playr.mute();
					}
				}
			} else if ( 'vimeo' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed > iframe' );

				if ( vid.length > 0 ) {
					var playr = new Vimeo.Player( vid );
					
					if ( typeof playr !== 'undefined' ) {
						playr.setVolume( 0 );
						playr.setMuted( true );
					}
				}
			} else if ( 'html' == target.data( 'type' ) ) {
				var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

				if ( vid.length > 0 ) {
					vid[ 0 ].setVolume( 0 );
					vid[ 0 ].setMuted( true );
				}
			}
		}

		function unmuteVideo( event ) {
			var target = $( event.target ).closest( '.wpzabb-slideshow-slide-video' );

			if ( 'youtube' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed' );

				if ( vid.length > 0 ) {
					var playr = YT.get( vid.attr( 'id' ) );
					
					if ( typeof playr !== 'undefined' ) {
						playr.unMute();
						playr.setVolume( 100 );
					}
				}
			} else if ( 'vimeo' == target.data( 'type' ) ) {
				var vid = target.find( '.video-embed > iframe' );

				if ( vid.length > 0 ) {
					var playr = new Vimeo.Player( vid );
					
					if ( typeof playr !== 'undefined' ) {
						playr.setMuted( false );
						playr.setVolume( 1 );
					}
				}
			} else if ( 'html' == target.data( 'type' ) ) {
				var vid = target.find( '.wp-video-shortcode' ).closest( 'mediaelementwrapper' );

				if ( vid.length > 0 ) {
					vid[ 0 ].setMuted( false );
					vid[ 0 ].setVolume( 1 );
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
		}

		function loadVimeoAPI() {
			$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video[data-type="vimeo"]' ).each( function() {
				var player = new Vimeo.Player( $( this ).find( '.video-embed' )[ 0 ], {
					height: '390',
					width: '640',
					id: $( this ).data( 'id' ),
					controls: false,
					muted: $( this ).data( 'startmuted' ),
					loop: $( this ).data( 'loop' )
				} );

				player.on( 'loaded', onVimeoPlayerReady );
				player.on( 'play', function( event ) { event.type = 'play'; $.proxy( onVimeoPlayerStateChange, this, event )(); } );
				player.on( 'pause', function( event ) { event.type = 'pause'; $.proxy( onVimeoPlayerStateChange, this, event )(); } );
				player.on( 'volumechange', function( event ) { event.type = 'volumechange'; $.proxy( onVimeoMuteStateChange, this, event )(); } );
			} );
		}

		function loadHTMLVideoAPI() {
			$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video[data-type="html"]' ).each( function() {
				var video = $( this ).find( '.wp-video-shortcode' );

				video.on( 'loadeddata', onHtmlPlayerReady );
				video.on( 'play', onHtmlPlayerStateChange );
				video.on( 'pause', onHtmlPlayerStateChange );
				video.on( 'volumechange', onHtmlMuteStateChange );
			} );
		}

		window.onYouTubeIframeAPIReady = function() {
			$( '.fl-node-<?php echo $id; ?> .wpzabb-slideshow .wpzabb-slideshow-slide-video[data-type="youtube"]' ).each( function() {
				new YT.Player( $( this ).find( '.video-embed' )[ 0 ], {
					height: '390',
					width: '640',
					videoId: $( this ).data( 'id' ),
					playerVars: {
						'loop': $( this ).data( 'loop' ) ? 1 : 0
					},
					events: {
						'onReady': onYouTubePlayerReady,
						'onStateChange': onYouTubePlayerStateChange
					}
				} );
			} );
		}

		function onYouTubePlayerReady( event ) {
			if ( typeof event !== 'undefined' && typeof event.target !== 'undefined' && typeof event.target.a !== 'undefined' ) {
				var target = $( event.target.a ),
				    slide = target.closest( '.wpzabb-slideshow-slide' ),
				    video = target.closest( '.wpzabb-slideshow-slide-video' );

				if ( video.data( 'startmuted' ) ) {
					event.target.mute();
				} else {
					event.target.unMute();
					event.target.setVolume( 100 );
				}

				if ( slide.hasClass( 'flex-active-slide' ) && video.data( 'autoplay' ) ) {
					event.target.playVideo();
				}

				setInterval( function() {
					var muted = event.target.isMuted();

					if ( video.data( 'muted' ) != muted ) {
						onYouTubeMuteStateChange( muted, slide );

						video.data( 'muted', muted );
					}
				}, 500 );
			}
		}

		function onYouTubePlayerStateChange( event ) {
			var target = $( event.target.a ),
			    slide = target.closest( '.wpzabb-slideshow-slide' ),
			    video = target.closest( '.wpzabb-slideshow-slide-video' ),
			    play = event.data == YT.PlayerState.PLAYING;

			video.data( 'playing', play );

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
					var startmuted = video.data( 'startmuted' );
					playr.setMuted( startmuted );
					playr.setVolume( startmuted ? 0 : 1 );

					if ( slide.hasClass( 'flex-active-slide' ) && video.data( 'autoplay' ) ) {
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

			video.data( 'playing', play );

			onPlaybackStateChange( play, slide );
		}

		function onVimeoMuteStateChange( event ) {
			var target = $( this.element ),
			    slide = target.closest( '.wpzabb-slideshow-slide' ),
			    video = target.closest( '.wpzabb-slideshow-slide-video' ),
			    mute = event.type == 'volumechange' && event.volume <= 0;

			video.data( 'muted', mute );

			onMuteStateChange( mute, slide );
		}

		function onHtmlPlayerReady( event ) {
			var target = $( event.target ),
			    slide = target.closest( '.wpzabb-slideshow-slide' ),
			    video = target.closest( '.wpzabb-slideshow-slide-video' );

			if ( slide.hasClass( 'flex-active-slide' ) && video.data( 'autoplay' ) ) {
				var view = target.closest( 'mediaelementwrapper' );

				if ( view.length > 0 ) {
					view = view[ 0 ];

					var startmuted = video.data( 'startmuted' );
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

			video.data( 'playing', play );

			onPlaybackStateChange( play, slide );
		}

		function onHtmlMuteStateChange( event ) {
			var target = $( event.target ),
			    slide = target.closest( '.wpzabb-slideshow-slide' ),
			    video = target.closest( '.wpzabb-slideshow-slide-video' ),
			    mute = event.type == 'volumechange' && event.target.volume <= 0;

			video.data( 'muted', mute );

			onMuteStateChange( mute, slide );
		}

		loadYouTubeAPI();
		loadVimeoAPI();
		loadHTMLVideoAPI();
	} );
} )( jQuery );