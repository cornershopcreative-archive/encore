var featherlight = require( 'featherlight' );
var storage = require( 'localstorage.js' );

module.exports = function( $ ) {

	$.featherlight.defaults.closeIcon = "&#9587;";

	$( '.lightbox-embed-link' ).each( function() {
		$( this ).featherlight( {
			iframe: $( this ).attr( 'href' ),
			variant: 'video',
		} );
	} );

	// When the BSD form is submitted to the iframe, toggle lightboxes
	$('#signup-generic, #signup-auto-lightbox').on('submit', function() {

		// Remember that the user has filled out a signup form.
		storage.signup_form_completed = '1';
		// Save the data that the user submitted for later use.
		storage.signup_form_data = $( this ).serializeJSON();

		$.featherlight.current().close();
		$.featherlight( $('#signup-modal-thanks') );

		// Track that the form was completed
		trackInteraction( 'submit', 'generic' );
	});

	var opportunityUrl = "";

	$('.section-partner-list .modal-trigger, .partners-grid-item .modal-trigger').on('click', function() {
		// If the user has already filled out the signup form (either via a modal
		// or the footer form), then don't display the signup modal -- just visit
		// the link the user clicked.
		if ( storage.getItem( 'signup_form_completed' ) ) {
			trackInteraction( 'bypass', $(this).data('org-name') );
			return true;
		}
		// If the user hasn't filled out the signup form, then display a modal.
		opportunityUrl = this.href;
		$.featherlight( $('#signup-modal-opportunity'), { variant: 'modalform' } );
		$('#signup-modal-opportunity #partner').val( $(this).data('org-name') );

		// Track that the modal was opened for this org
		trackInteraction( 'open', $(this).data('org-name') );

		return false;
	});

	// When the opportunity form is submitted to the iframe, toggle lightboxes
	$('#signup-opportunity').on('submit', function() {

		// Remember that the user has filled out a signup form.
		storage.signup_form_completed = '1';
		// Save the data that the user submitted for later use.
		storage.signup_form_data = $( this ).serializeJSON();

		// Track the submission
		trackInteraction( 'submit', $('#signup-modal-opportunity #partner').val() );

		// Close the lightbox with the form
		$.featherlight.current().close();

		// Initialize the countdown lightbox
		$('#signup-modal-opportunity-thanks #continue-button').attr( 'href', opportunityUrl );
		var countdown = 5;
		$('.countdown').html('in 5 seconds');

		// Show the countdown lightbox
		$.featherlight( $('#signup-modal-opportunity-thanks') );

		// Run the countdown
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

	// Track when header lightbox is opened
	$(document).on('click', '#masthead *[data-featherlight]', function() {
		trackInteraction( 'open', 'generic' );
	});

	// Track when modals are closed via user action
	$(document).on('click', '.featherlight-close-icon', function() {
		var modalType = 'generic';
		if ( $(this).siblings('#signup-modal-opportunity').length ) {
			modalType = $('input#partner').val();
		} else if ( $(this).siblings('#signup-modal-thanks').length ) {
			modalType = 'thanks';
		}
		trackInteraction( 'close', modalType );
	});

	var trackInteraction = function( action, label ) {
		if ( typeof ga === 'function' ) {
			ga( 'send', 'event', 'lightbox', action, label );
			console.log( 'tracking ' + action + ' + ' + label );
		}	else {
			console.log( 'ga undefined, no tracking will occur' );
		}
	}

};
