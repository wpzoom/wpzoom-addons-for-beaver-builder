( function( $ ) {
	FLBuilder.registerModuleHelper( 'wpzabb-slideshow', {
		init: function() {
			$( 'body' ).delegate( '.fl-builder-settings-tabs a', 'click', this.onTabClick );
		},

		onTabClick: function() {
			var tab  = $( this ),
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
	});
} )( jQuery );