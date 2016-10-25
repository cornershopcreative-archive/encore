var featherlight = require( 'featherlight' );

module.exports = function( $ ) {
	$( '.hero-video-link' ).each( function() {
		$( this ).featherlight();
	} );
};
