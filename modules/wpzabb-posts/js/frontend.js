(function($) {

	WPZABBPostsModule = function(settings)
	{
		this.settings    = settings;
		this.nodeClass   = '.fl-node-' + settings.id;
		this.matchHeight = settings.matchHeight;

		this.wrapperClass = this.nodeClass + ' .wpzabb-post-' + this.settings.layout;
		this.postClass    = this.nodeClass + ' .wpzabb-post-column';

		if(this._hasPosts()) {
			this._initLayout();
			this._initInfiniteScroll();
		}
	};

	WPZABBPostsModule.prototype = {

		settings        : {},
		nodeClass       : '',
		wrapperClass    : '',
		postClass       : '',
		gallery         : null,
		currPage		: 1,
		totalPages		: 1,

		_hasPosts: function()
		{
			return $(this.postClass).length > 0;
		},

		_initLayout: function()
		{
			switch(this.settings.layout) {

				case 'grid':
				this._gridLayout();
				break;
			}

			$(this.postClass).css('visibility', 'visible');
		},

		_gridLayout: function()
		{
			var wrap = $(this.wrapperClass);

			wrap.masonry({
				columnWidth         : this.postClass,
				gutter              : parseInt(this.settings.postSpacing),
				isFitWidth          : false,
				itemSelector        : this.postClass,
				transitionDuration  : 0,
				isRTL               : this.settings.isRTL
			});

			wrap.imagesLoaded( $.proxy( function() {
				this._gridLayoutMatchHeight();
				wrap.masonry();
			}, this ) );

			$( window ).on( 'resize', $.proxy( this._gridLayoutMatchHeight, this ) );
		},

		_gridLayoutMatchHeight: function()
		{
			var highestBox = 0;

			if ( 0 === this.matchHeight ) {
				return;
			}

            $(this.nodeClass + ' .wpzabb-post-grid-post').css('height', '').each(function(){

                if($(this).height() > highestBox) {
                	highestBox = $(this).height();
                }
            });

            $(this.nodeClass + ' .wpzabb-post-grid-post').height(highestBox);
		},

		_initInfiniteScroll: function()
		{
			var isScroll = 'scroll' == this.settings.pagination || 'load_more' == this.settings.pagination,
				pages	 = $( this.nodeClass + ' .wpzabb-builder-pagination' ).find( 'li .page-numbers:not(.next)' );

			if( pages.length > 1) {
				total = pages.last().text().replace( /\D/g, '' )
				this.totalPages = parseInt( total );
			}

			if( isScroll && this.totalPages > 1 && 'undefined' === typeof FLBuilder ) {
				this._infiniteScroll();

				if( 'load_more' == this.settings.pagination ) {
					this._infiniteScrollLoadMore();
				}
			}
		},

		_infiniteScroll: function(settings)
		{
			var path 		= $(this.nodeClass + ' .wpzabb-builder-pagination a.next').attr('href'),
				pagePattern = /(.*?(\/|\&|\?)paged-[0-9]{1,}(\/|=))([0-9]{1,})+(.*)/,
				pageMatched = null,
				scrollData	= {
					navSelector     : this.nodeClass + ' .wpzabb-builder-pagination',
					nextSelector    : this.nodeClass + ' .wpzabb-builder-pagination a.next',
					itemSelector    : this.postClass,
					prefill         : true,
					bufferPx        : 200,
					loading         : {
						msgText         : 'Loading',
						finishedMsg     : '',
						img             : FLBuilderLayoutConfig.paths.pluginUrl + 'img/ajax-loader-grey.gif',
						speed           : 1
					}
				};

			// Define path since Infinitescroll incremented our custom pagination '/paged-2/2/' to '/paged-3/2/'.
			if ( pagePattern.test( path ) ) {
				scrollData.path = function( currPage ){
					pageMatched = path.match( pagePattern );
					path = pageMatched[1] + currPage + pageMatched[5];
					return path;
				}
			}

			$(this.wrapperClass).infinitescroll( scrollData, $.proxy(this._infiniteScrollComplete, this) );

			setTimeout(function(){
				$(window).trigger('resize');
			}, 100);
		},

		_infiniteScrollComplete: function(elements)
		{
			var wrap = $(this.wrapperClass);

			elements = $(elements);

			if(this.settings.layout == 'grid') {
				wrap.imagesLoaded( $.proxy( function() {
					this._gridLayoutMatchHeight();
					wrap.masonry('appended', elements);
					elements.css('visibility', 'visible');
				}, this ) );
			}

			if( 'load_more' == this.settings.pagination ) {
				$( '#infscr-loading' ).appendTo( this.wrapperClass );
			}

			this.currPage++;

			this._removeLoadMoreButton();
		},

		_infiniteScrollLoadMore: function()
		{
			var wrap = $( this.wrapperClass );

			$( window ).unbind( '.infscr' );

			$(this.nodeClass + ' .wpzabb-builder-pagination-load-more .wpzabb-button').on( 'click', function(){
				wrap.infinitescroll( 'retrieve' );
				return false;
			});
		},

		_removeLoadMoreButton: function()
		{
			if ( 'load_more' == this.settings.pagination && this.totalPages == this.currPage ) {
				$( this.nodeClass + ' .wpzabb-builder-pagination-load-more' ).remove();
			}
		}
	};

})(jQuery);
