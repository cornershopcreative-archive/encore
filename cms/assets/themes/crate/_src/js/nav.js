module.exports = function( $ ) {

	var $nav = $( '.nav-primary' ),
	    $nav_toggle = $( '.nav-toggle a' );

	// Handle clicks for opening/closing the main menu.
	$( document ).on( 'click', function( e ) {
		// Get the clicked element.
		var $target = $( e.target );

		if ( $target.is( $nav_toggle ) ) {
			// If the nav toggle was clicked, open or close the menu.
			e.preventDefault();
			$nav.toggleClass( 'is-open' );
		}
		else if ( $target.closest( $nav ).length ) {
			// If the nav menu itself, or one of its descendents, was clicked, do
			// nothing (don't close the menu!).
			e.stopPropagation();
		}
		else {
			// If something outside the menu was clicked, close the menu.
			$nav.removeClass( 'is-open' );
		}
	} );

	// Handle clicks for opening/closing submenus.
	$nav.find( '.menu-item-has-children' ).each( function() {

		var $item   = $( this ),
		    $link   = $item.find( '> a' ),
		    $toggle = $( '<a />' ).attr( 'href', '#' ).addClass( 'subnav-toggle' );

		$toggle.insertAfter( $link );

		$toggle.on( 'click', function( e ) {
			e.preventDefault();
			$item.toggleClass( 'subnav-is-open' );
		} );
	} );
};
