var _ = require( 'underscore' );

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

	// Add a class to the nav element if it contains a submenu that should be
	// shown on desktop (i.e. a current menu item with children, or a current
	// menu item's ancestor). This allows us to style this type of menu
	// specifically.
	if ( $nav.find( '.menu-item-has-children.current-menu-item, .current-menu-ancestor' ).length ) {
		$( '.site-header' ).addClass( 'has-current-submenu' );
	}

	// Allow different styling for when the main nav is being sticky.
	$( window ).on( 'scroll', _.throttle( function() {
		console.log( $( this ).scrollTop() );
		if ( $( this ).scrollTop() > 0 ) {
			$( '.site-header' ).addClass( 'has-sticky-nav' );
		} else {
			$( '.site-header' ).removeClass( 'has-sticky-nav' );
		}
	}, 100 ) );
};
