(function($){

	$(document).ready(function(){
		var D = $(document),
			B = $('body');

		D.find('.wpzabb-button-popup-video').each(function(){
		    var $popupinstance = $(this);
		    var $type = $popupinstance.data('popup-type');
		    $(this).magnificPopup({
		        disableOn: function() { if( $(window).width() < 0) { return false; } return true; },
		        type: $type,
		        iframe: {
		            patterns: {
		                youtu: {
		                    index: 'youtu.be',
		                    id: function( url ) {

		                        // Capture everything after the hostname, excluding possible querystrings.
		                        var m = url.match( /^.+youtu.be\/([^?]+)/ );

		                        if ( null !== m ) {

		                            return m[1];

		                        }

		                        return null;

		                    },
		                    // Use the captured video ID in an embed URL.
		                    // Add/remove querystrings as desired.
		                    src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
		                }
		            }
		        },
		        mainClass: 'mfp-fade',
		        removalDelay: 160,
		        preloader: false,
		        fixedContentPos: false,
		        callbacks: {
		            open : function(){
		                if($type === 'inline'){
		                    var container = $.magnificPopup.instance.contentContainer.first();
		                    container.find('video')[0].play();
		                }
		            },
		            beforeClose : function(){
		                if($type === 'inline'){

		                    var $video = $(this.content).find('video');

		                    if ($video.length) {
		                        var videoElement = $video[0];

		                        var currentSrc = videoElement.currentSrc;
		                        videoElement.pause();
		                        videoElement.currentTime = 0;
		                        videoElement.src = '';
		                        videoElement.src = currentSrc;
		                    }
		                }
		            },
		        }
		    });
		});
	})
})(jQuery);