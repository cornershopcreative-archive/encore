module.exports = function( $ ) {

	// Open, or maybe close, the search form when the form wrapper receives a
	// click event.
	$( '.collapsible-search' ).on( 'click', function( e ) {
		// Don't bubble up to the document, or this same click event could also
		// close the form.
		e.stopPropagation();

		if ( $( e.target ).is( 'a' ) ) {
			// Don't bother actually following the toggle link.
			e.preventDefault();
		}

		var $form = $( this );
			$toggle = $form.find( '.search-toggle' );

		if ( ! $form.is( '.is-open' ) ) {
			// If the form isn't open, open it.
			$form.addClass( 'is-open' );
			$form.find( 'input[type="search"]' ).focus();
		} else {
			// If the form is open, close it, but only if the user clicked the toggle
			// link or a descendent of it.
			if ( $toggle.is( e.target ) || $toggle.has( e.target ).length ) {
				$form.removeClass( 'is-open' );
			}
		}
	} );

	// Any time a click event is allowed to bubble up to the document, make sure
	// the search form is closed.
	$( document ).on( 'click', function() {
		$( '.collapsible-search' ).removeClass( 'is-open' );
	} );

};
