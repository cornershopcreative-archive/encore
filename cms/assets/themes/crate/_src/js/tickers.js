var imagesLoaded = require( 'imagesloaded' );

module.exports = function( $ ) {

	$( '.section-ticker' ).each( function() {

		var $ticker = $( this ),
			$items = $ticker.children();

		// Shuffle child elements (see http://jsfiddle.net/C6LPY/2/).
		while ( $items.length ) {
			$ticker.append( $items.splice( Math.floor( Math.random() * $items.length ), 1 )[0] );
		}

		// Add a wrapper around all child elements.
		$ticker.wrapInner( '<div class="ticker-half" />' );
		// Duplicate it and append.
		$ticker.find( '.ticker-half' ).clone().appendTo( this );

		$ticker.imagesLoaded( function() {
			$ticker.addClass( 'is-ready' );
		} );
	} );
};
