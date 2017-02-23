var featherlight = require( 'featherlight' );
var storage = require( 'localstorage.js' );
module.exports = function( $ ) {

	// Set a default signup modal tracking context. This global variable will
	// change when the signup modal is opened. Storing the modal context in a
	// global variable allows us to associate 'close' or 'submit' events with the
	// way the modal that's just been closed or submitted was originally opened.
	var trackingContext = 'generic';

	/**
	 * Track a lightbox-related event in GA.
	 */
	var trackInteraction = function( action, label ) {

		// If no label was provided, use the global modal context variable.
		if ( ! label ) {
			label = trackingContext;
		}

		if ( typeof ga === 'function' ) {
			ga( 'send', 'event', 'lightbox', action, label );
			console.log( 'tracking ' + action + ' + ' + label );
		}	else {
			console.log( 'ga undefined, no tracking will occur' );
		}
	};

	/**
	 * Open a new Featherlight modal, and set the global modal context variable
	 * for event tracking purposes.
	 */
	var openModal = function( $content, context, options ) {
		// Close the currently open modal window, if any, without tracking a
		// 'close' event.
		closeCurrentModal();

		// Set the modal context variable for future event tracking.
		trackingContext = context;

		// Open the new modal.
		$.featherlight(
			$content,
			$.extend( {
				// Set a close handler that will track a 'close' event if the user
				// manually closes the modal window.
				afterClose: function() {
					trackInteraction( 'close' );
				}
			}, options )
		);
		trackInteraction( 'open' );
	};

	/**
	 * Close the currently open Featherlight modal window, without tracking a
	 * 'close' event in Google Analytics.
	 */
	var closeCurrentModal = function() {
		// Remove any existing afterClose handler (this prevents a 'close' event
		// being tracked in addition to the 'submit' event).
		var current_modal = $.featherlight.current();
		if ( current_modal ) {
			current_modal.afterClose = $.noop;
			current_modal.close();
		}
	};

	/**
	 * Set up the auto-lightbox, based on the window.lightbox_opts variable.
	 */
	var autoLightboxSetup = function() {

		options = window.lightbox_opts || {};
		// var force = window.location.search.indexOf("forceoverlay");
		var alreadyOpened = false;
		var settings = $.extend({
			context : 'none',
			trigger : 'immediate',
			amount  : 1,
		}, options );

		// do nothing if it's disabled...
		if ( 'none' === settings.context ) return;

		// ...if it's supposed to be on the homepage and we're not...
		if ( 'home' === settings.context && !$('body').hasClass('home') ) return;

		// ...or if the already-seen cookie is still around
		if ( storage.getItem( 'lightbox_seen_1' ) ) return;

		// let's see if we meet our open criteria!
		// right away
		if ( 'immediate' == settings.trigger ) {
			openAutoModal();
		// delay by n seconds
		} else if ( 'delay' == settings.trigger ) {
			setTimeout( function() { openAutoModal(); }, settings.amount * 1000 );
		}

		/**
		 * Open the auto-lightbox.
		 */
		var openAutoModal = function() {

			if ( alreadyOpened ) return;

			openModal( $('#auto-lightbox-modal'), 'automatic', {
				type: 'html'
			});

			alreadyOpened = true;

			// Remember that we've opened the lightbox.
			storage['lightbox_seen_1'] = 1;
		};
	};

	$.featherlight.defaults.closeIcon = "&#9587;";

	$( '.lightbox-embed-link' ).each( function() {
		$( this ).featherlight( {
			iframe: $( this ).attr( 'href' ),
			variant: 'video'
		} );
	} );

	// When the BSD form is submitted to the iframe, toggle lightboxes
	$('#signup-generic, #signup-auto-lightbox').on('submit', function() {

		// Remember that the user has filled out a signup form.
		storage.signup_form_completed = '1';
		// Save the data that the user submitted for later use.
		storage.signup_form_data = $( this ).serializeJSON();

		// Track that the form was completed.
		trackInteraction( 'submit' );

		openModal( $('#signup-modal-thanks'), 'thanks' );
	});

	// When the user clicks a 'share' button in a thank-you modal, follow the
	// link (whose target is probably _blank) but close the 'thank you for
	// signing up' modal and open the 'thank you for sharing' modal.
	$('#signup-modal-thanks .button, #opportunity-modal-thanks .button').on('click', function() {
		trackInteraction( 'share' );
		openModal( $('#share-modal-thanks'), 'share-thanks' );
	} );

	// When the user clicks the 'back to Gen2Gen' button in the 'thank you for
	// sharing' modal, just close the modal window.
	$('#share-modal-thanks .button-close').on('click', function( e ) {
		e.preventDefault();
		// Note that we're NOT using closeCurrentModal() here, because this time we
		// WANT the 'close' event to be tracked by GA.
		$.featherlight.current().close();
	} );

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
		openModal( $('#signup-modal-opportunity'), $(this).data('org-name'), {
			variant: 'modalform'
		} );
		$('#signup-modal-opportunity #partner').val( $(this).data('org-name') );

		return false;
	});

	// When the opportunity form is submitted to the iframe, toggle lightboxes
	$('#signup-opportunity').on('submit', function() {

		// Remember that the user has filled out a signup form.
		storage.signup_form_completed = '1';
		// Save the data that the user submitted for later use.
		storage.signup_form_data = $( this ).serializeJSON();

		// Track the submission
		trackInteraction( 'submit' );

		// Close the current modal without tracking a 'close' event.
		closeCurrentModal();

		// Initialize the countdown lightbox
		$('#signup-modal-opportunity-thanks #continue-button').attr( 'href', opportunityUrl );
		var countdown = 5;
		$('.countdown').html('in 5 seconds');

		// Show the countdown lightbox (without tracking an 'open' event, because
		// we don't really care about tracking this modal window type).
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

	// Open signup modal window when either the hero or navigation 'Get Involved'
	// link is clicked.
	$(document).on('click', '.signup-modal-trigger', function( e ) {
		e.preventDefault();
		openModal( $('#signup-modal'), $(this).data('modal-tracking-context'), {
			variant: 'modalform'
		});
	});

	// Track when modals are closed via user action
	$(document).on('click', '.featherlight-close-icon, #share-modal-thanks .button-close', function() {
		var modalType = trackingContext;
		if ( $(this).siblings('#signup-modal-opportunity').length ) {
			modalType = $('input#partner').val();
		} else if ( $(this).siblings('#signup-modal-thanks').length ) {
			modalType = 'thanks';
		} else if ( $(this).siblings('#share-modal-thanks').length ) {
			modalType = 'share-thanks';
		}
		trackInteraction( 'close', modalType );
	});

	// Set up the automatic lightbox functionality.
	autoLightboxSetup();

};
