var featherlight = require( 'featherlight' );
var storage = require( 'localstorage.js' );
var moment = require( 'moment' );

module.exports = function( $ ) {

	// Disable Featherlight's irritating anti-tabindex behavior.
	// See http://stackoverflow.com/q/42234790/470749.
	$.featherlight._callbackChain.beforeOpen = function (event) { };
	$.featherlight._callbackChain.afterClose = function (event) { };
	$.featherlight.defaults.afterContent = function (event) {
		var firstInput = $('.featherlight-content #firstname');
		console.log('Considering whether to focus on input depending on window size...', $(window).width(), $(window).height(), firstInput);
		if (Math.min($(window).width(), $(window).height()) > 736) {//if the smallest dimension of the device is larger than iPhone6+
			console.log('yes, focus');
			firstInput.attr('autofocus', true);
		}
	};

	// Set a default signup modal tracking context. This global variable will
	// change when the signup modal is opened. Storing the modal context in a
	// global variable allows us to associate 'close' or 'submit' events with the
	// way the modal that's just been closed or submitted was originally opened.
	var trackingContext = 'generic';

	/**
	 * Track a lightbox-related event in GA.
	 */
	var trackInteraction = function( action, label, extraOpts ) {

		// Initialize additional options to send to GA.
		var opts = extraOpts || {};

		// If no label was provided, use the global modal context variable.
		if ( ! label ) {
			label = trackingContext;
		}

		if ( typeof ga === 'function' ) {
			ga( 'send', 'event', 'lightbox', action, label, opts );
			console.log( 'tracking ' + action + ' + ' + label );
		}	else {
			console.log( 'ga undefined, no tracking will occur' );
			// Call hitCallback immediately, since GA's not around to call it.
			if ( 'hitCallback' in opts ) {
				opts.hitCallback.call( window );
			}
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

		// If the user has already signed up, don't bother opening the lightbox.
		if ( parseInt( storage.getItem( 'signup_form_completed' ) ) ) {
			return;
		}

		options = window.lightbox_opts || {};
		// var force = window.location.search.indexOf("forceoverlay");
		var alreadyOpened = false;
		var settings = $.extend({
			context : 'none',
			trigger : 'immediate',
			amount  : 1,
			reappear: 7,
		}, options );

		// do nothing if it's disabled...
		if ( 'none' === settings.context ) return;

		// ...if it's supposed to be on the homepage and we're not...
		if ( 'home' === settings.context && !$('body').hasClass('home') ) return;

		// Check for 'lightbox seen' storage var.
		var lightbox_seen = parseInt( storage.getItem( 'lightbox_seen_1' ) );
		var now = moment().valueOf();
		// Convert old-format 'lightbox seen' storage var (1 = seen) to new format
		// (timestamp = date seen in ms).
		if ( 1 === lightbox_seen ) {
			// Replace lightbox_seen with the time in ms from the UNIX epoch. Lightbox
			// reappear in `settings.reappear` days.
			lightbox_seen = storage['lightbox_seen_1'] = now;
		}
		// If the lightbox is not set to reappear, or the lightbox_seen date is less
		// than `settings.reappear` days in the past, don't show the lightbox.
		if (
			( settings.reappear < 1 )
			||
			( moment( lightbox_seen ).add( settings.reappear, 'days' ).valueOf() > now )
		) {
			return;
		}

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
			storage['lightbox_seen_1'] = moment().valueOf();
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
		// Set the 'trigger' field in the modal form depending on which button was
		// clicked, for lead tracking within BSD.
		if ( $( this ).closest( '#masthead' ).length ) {
			$( '#signup-modal input[name="custom-24"]' ).val( 'G2G Site Header Button' );
		} else {
			$( '#signup-modal input[name="custom-24"]' ).val( 'G2G Hero Button' );
		}
		// Open the signup modal window.
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

	// When the user clicks a button in the Summer Challenge section, open its
	// sibling modal form.
	$('.section-summer-challenge').on('click', '.button-challenge', function(e) {
		e.preventDefault();
		openModal(
			$(this).closest('.grid-item').find('.modal'),
			'summer-challenge-' + $(this).attr('data-challenge'),
			{ variant: 'modalform' }
		);
	});

	// When the BSD form is submitted to the iframe, toggle lightboxes
	$('.signup-summer-challenge').on('submit.ga', function(e) {

		var $this = $( this ),
			hasSubmitted = false;

		// Cancel submission for now, so we can log a GA event first.
		e.preventDefault();

		// Set up a callback to redirect the user to the Get Equipped page.
		var submit = function() {
			// If the form has already been submitted, bail.
			if ( hasSubmitted ) return;
			// Detach this event handler so we don't cause an infinite loop!
			$this.off( 'submit.ga' );
			// Submit the form.
			$this.submit();
			// Don't submit again.
			hasSubmitted = true;
		};

		// Track that the form was completed.
		trackInteraction( 'submit', false, {
			hitCallback: submit
		});

		// trackInteraction() should call the hitCallback set above immediately if
		// GA didn't load, but _just_ in case something else went wrong, call it
		// again in 1 second.
		setTimeout( submit, 1000 );
	});

	// Set up the automatic lightbox functionality.
	autoLightboxSetup();

};
