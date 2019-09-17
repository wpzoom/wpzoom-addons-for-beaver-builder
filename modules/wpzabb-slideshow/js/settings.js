( function( $ ) {
	FLBuilder.registerModuleHelper( 'wpzabb-slideshow', {
		init: function() {
			$( 'body' ).delegate( '.fl-builder-settings-tabs a', 'click', this.onTabClick );

			setTimeout( this.onTabClick, 500 );

			$( document ).on( 'classAdded', '.fl-builder-settings[data-form-id="slides_form"]', function( event, className ) {
				var vid_src_mob = $( '.fl-builder-settings[data-form-id="slides_form"] select[name="video_source_responsive"]' ),
				    vid_src_med = $( '.fl-builder-settings[data-form-id="slides_form"] select[name="video_source_medium"]' ),
				    vid_src_des = $( '.fl-builder-settings[data-form-id="slides_form"] select[name="video_source"]' ),
				    video_source = vid_src_mob.is( ':visible' ) ? vid_src_mob : ( vid_src_med.is( ':visible' ) ? vid_src_med : vid_src_des );

				video_source.trigger( 'change' );
			} );
		},

		onTabClick: function() {
			var tab  = $( '.fl-builder-settings-tabs a.fl-active' ),
			    id   = tab.attr( 'href' ).split( '#' ).pop(),
			    form = $( '#' + id );

			if ( 'fl-builder-settings-tab-slides' == id && form.length > 0 && !form.hasClass( 'initd' ) ) {
				var trs = form.find( '#fl-field-slides > tr' ),
				    slides = form.find( '#fl-field-slides > tr > .fl-field-control > .fl-field-control-wrapper > .fl-form-field' );

				if ( slides.length > 0 ) {
					slides.each( function() {
						var input = $( this ).find( 'input[name="slides[]"]' );

						if ( input.length > 0 ) {
							var json;

							try {
								json = JSON.parse( input.val() );
							} catch( e ) {}

							if ( typeof json !== 'undefined' ) {
								var img = json.image_src,
								    vid = 'library' == json.video_source ? json.video : ( 'url' == json.video_source ? json.video_url : false ),
								    is_vid = false !== vid && '' !== vid,
								    is_img = false !== img && '' !== img;

								if ( is_vid || is_img ) {
									if ( is_vid ) {
										FLBuilder.ajax(
											{
												action: 'wpzabb_slideshow_get_thumb',
												source: ( is_vid ? json.video_source : json.image_source + '-image' ),
												dat: ( is_vid ? vid : img ),
												element_num: $( this ).closest( 'tr' ).index()
											},
											function( r ) {
												if ( false !== r ) {
													var response;

													try {
														response = JSON.parse( r );
													} catch( e ) {}

													if ( typeof response !== 'undefined' ) {
														var type = response.type,
														    url = response.url,
														    elem = $( trs.get( response.element_num ) ).find( '> .fl-field-control > .fl-field-control-wrapper > .fl-form-field > .fl-form-field-edit' );

														elem.attr( 'title', elem.text() );

														if ( 'library' === type ) {
															elem.append( '<canvas/><video controls><source type="video/mp4"></video>' );

															var canvas = elem.find( 'canvas' )[ 0 ],
															    ctx = canvas.getContext( '2d' ),
															    video = elem.find( 'video' )[ 0 ];

															$( video ).find( 'source' ).attr( 'src', url );
															video.load();
															$( video ).css( 'display', 'inline' );

															video.currentTime = 1;

															video.addEventListener( 'loadedmetadata', function() {
																canvas.width = video.videoWidth;
																canvas.height = video.videoHeight;
															} );

															video.addEventListener( 'timeupdate', function() {
																ctx.drawImage( video, 0, 0, video.videoWidth, video.videoHeight );

																$( this ).parent().html( '<img src="' + canvas.toDataURL() + '" />' );
															} );
														} else {
															elem.html( '<img src="' + url + '" />' );
														}
													}
												}
											}
										);
									} else {
										$( this ).find( '> .fl-form-field-edit' ).html( '<img src="' + img + '" />' );
									}
								}
							}
						}
					} );
				}

				form.addClass( 'initd' );
			}
		}
	} );

	( function( func ) {
		$.fn.addClass = function( n ) { // replace the existing function on $.fn
			this.each( function( i ) { // for each element in the collection
				var $this = $( this ), // 'this' is DOM element in this context
				    prevClasses = this.getAttribute( 'class' ), // note its original classes
				    classNames = $.isFunction( n ) ? n( i, prevClasses ) : n.toString(); // retain function-type argument support

				$.each( classNames.split( /\s+/ ), function( index, className ) { // allow for multiple classes being added
					if( !$this.hasClass( className ) ) { // only when the class is not already present
						func.call( $this, className ); // invoke the original function to add the class
						$this.trigger( 'classAdded', className ); // trigger a classAdded event
					}
				} );

				prevClasses != this.getAttribute( 'class' ) && $this.trigger( 'classChanged' ); // trigger the classChanged event
			} );

			return this; // retain jQuery chainability
		}
	} )( $.fn.addClass ); // pass the original function as an argument

	( function( func ) {
		$.fn.removeClass = function( n ) {
			this.each( function( i ) {
				var $this = $( this ),
				    prevClasses = this.getAttribute( 'class' ),
				    classNames = $.isFunction( n ) ? n( i, prevClasses ) : n.toString();

				$.each( classNames.split( /\s+/ ), function( index, className ) {
					if( $this.hasClass( className ) ) {
						func.call( $this, className );
						$this.trigger( 'classRemoved', className );
					}
				} );

				prevClasses != this.getAttribute( 'class' ) && $this.trigger( 'classChanged' );
			} );

			return this;
		}
	} )( $.fn.removeClass );
} )( jQuery );