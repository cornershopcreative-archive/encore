var featherlight = require( 'featherlight' );

module.exports = function( $ ) {

	$.featherlight.defaults.closeIcon = "&#9587;";

	$( '.hero-video-link' ).each( function() {
		$( this ).featherlight();
	} );
};
