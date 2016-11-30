var featherlight = require( 'featherlight' );
var storage = require( 'localstorage.js' );

module.exports = function( $ ) {

	$.featherlight.defaults.closeIcon = "&#9587;";

	$( '.hero-video-link' ).each( function() {
		$( this ).featherlight();
	} );

	// When the BSD form is submitted to the iframe, toggle lightboxes
	$('#signup-generic').on('submit', function() {
		$.featherlight.current().close();
		$.featherlight( $('#signup-modal-thanks') );
		// Remember that the user has filled out a signup form.
		storage.signup_form_completed = '1';
	});

	var opportunityUrl = "";

	$('.section-partner-list .modal-trigger, .partners-grid-item .modal-trigger').on('click', function() {
		// If the user has already filled out the signup form (either via a modal
		// or the footer form), then don't display the signup modal -- just visit
		// the link the user clicked.
		if ( storage.getItem( 'signup_form_completed' ) ) {
			return true;
		}
		// If the user hasn't filled out the signup form, then display a modal.
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

		// Remember that the user has filled out a signup form.
		storage.signup_form_completed = '1';
	});

};
