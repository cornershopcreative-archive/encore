var featherlight = require( 'featherlight' );

module.exports = function( $ ) {

	$.featherlight.defaults.closeIcon = "&#9587;";

	$( '.hero-video-link' ).each( function() {
		$( this ).featherlight();
	} );

	// When the BSD form is submitted to the iframe, toggle lightboxes
	$('#signup-generic').on('submit', function() {
		$.featherlight.current().close();
		$.featherlight( $('#signup-modal-thanks') );
	});

};
