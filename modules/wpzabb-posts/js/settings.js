jQuery( function($){

	var strings = $.extend( {
		posts:      'Start typing post names here:',
		categories: 'Start typing category names here:',
		tags:       'Start typing tag names here:',
		authors:    'Start typing author names here:'
	}, window.WPZABBPostsStrings || {} );

	function addFilterLabel( form, selector, field, text ) {
		form.find( selector ).before(
			$( '<label class="extra-space" />' ).attr( 'for', field ).text( text )
		);
	}

	$( 'body' ).delegate( '.fl-builder-settings-tabs a', 'click', function(e){
		var tab  = $( this ),
		    form = tab.closest( '.fl-builder-settings' ),
		    id   = tab.attr( 'href' ).split( '#' ).pop();

		if ( id == 'fl-builder-settings-tab-filter' )
		{
			addFilterLabel( form, '#as-selections-posts_post', 'posts_post', strings.posts );
			addFilterLabel( form, '#as-selections-tax_post_category', 'tax_post_category', strings.categories );
			addFilterLabel( form, '#as-selections-tax_post_post_tag', 'tax_post_post_tag', strings.tags );
			addFilterLabel( form, '#as-selections-users', 'users', strings.authors );
		}
	} );
} );
