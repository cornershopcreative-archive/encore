// Load underscore.js.
var _ = require( 'underscore' );

// Get local storage object, or fall back on a dummy storage object if this
// is an old browser.
var storage = window.localStorage || {
	getItem: function() { return false; }
};

module.exports = function( $ ) {

	var $form = $( '.footer-form' );

	// Stickify the footer form.
	var $sticky_placeholder = $( '<div />' ).insertBefore( '.footer-form' );
	$( window ).on( 'scroll', _.throttle( function() {
		// Get the position of the top of the window.
		var scrollTop = $( this ).scrollTop();
		// Get the position of the bottom of the window.
		var scrollBottom = scrollTop + $( this ).height() - $form.outerHeight();
		// Make the footer form sticky if the user is scrolled more than 300px from
		// the top of the page, and ISN'T scrolled far down enough to see the
		// sticky placeholder (which sits where the footer form would be if it
		// wasn't sticky).
		if ( ( scrollTop > 300 ) && ( scrollBottom < $sticky_placeholder.offset().top ) ) {
			$form.addClass( 'is-sticky' );
		} else {
			$form.removeClass( 'is-sticky' );
		}
	}, 50 ) );

	// Trigger the scroll handler once to initialize sticky status.
	$( window ).trigger( 'scroll' );

	// Check localStorage to determine whether or not to hide the footer form.
	if ( storage.getItem( 'footer_form_hidden' ) ) {
		$( '.footer-form' ).addClass( 'is-hidden' );
	}

	// Hide the footer form when the dismiss button is clicked.
	$( '.footer-form-dismiss' ).on( 'click', function() {
		// Hide the form.
		$( '.footer-form' ).addClass( 'is-hidden' );
		// Remember that the form should be hidden.
		storage.footer_form_hidden = '1';
	} );

	// When the footer form is submitted, display a thank you message, hide the
	// form, and remember to keep it hidden.
	console.log( $form.find( 'form' ) );
	$form.find( 'form' ).on( 'submit', function() {
		console.log( 'submit' );
		// Show the thank-you message.
		$form.addClass( 'is-submitted' );
		// Hide the form on future pageviews (but leave it to the user to dismiss
		// the thank-you message).
		storage.footer_form_hidden = '1';
	} );
};
