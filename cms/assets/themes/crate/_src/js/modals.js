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

	var opportunityUrl = "";

	$('.section-partner-list .modal-trigger, .partners-grid-item .modal-trigger').on('click', function() {
		opportunityUrl = this.href;
		$.featherlight( $('#signup-modal-opportunity'), { variant: 'modalform' } );
		$('#signup-modal-opportunity #partner').val( $(this).data('org-name') );
		return false;
	});

	// When the opportunity form is submitted to the iframe, toggle lightboxes
	$('#signup-opportunity').on('submit', function() {

		$('#signup-modal-opportunity-thanks #continue-button').attr( 'href', opportunityUrl );
		var countdown = 5;
		$('.countdown').html('in 5 seconds');

		$.featherlight.current().close();
		$.featherlight( $('#signup-modal-opportunity-thanks') );

		var interval = setInterval(function() {
			countdown--;
			if ( countdown >= 2 ) {
				$('.countdown').html( 'in ' + countdown + " seconds" );
			}
			else if ( countdown == 1 ) {
				$('.countdown').html( 'in ' + countdown + " second" );
			} else {
				$('.countdown').html( 'now' );
				window.location = opportunityUrl;
			}
		}, 1000);
	});


};
