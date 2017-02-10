var _ = require( 'underscore' );

module.exports = function( $ ) {

	var $header = $( '.site-header' ),
	    $nav = $( '.nav-primary' ),
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

	// Determine whether the active menu item has, or is a member of, a submenu
	// that should be visible on desktop.
	var has_active_submenu = ( $nav.find( '.menu-item-has-children.current-menu-item, .current-menu-ancestor' ).length > 0 );

	// Restore the 'has-current-submenu' class if the current menu item has a
	// submenu that should be visible.
	var reset_submenu = function() {
		if ( has_active_submenu ) {
			$header.addClass( 'has-current-submenu' );
		} else {
			$header.removeClass( 'has-current-submenu' );
		}
	};

	// Initialize submenu visibility state.
	reset_submenu();

	// If user hovers over a top-level item that has a submenu, then open the
	// submenu. Otherwise, close it.
	$nav.find( '.menu > .menu-item' ).on( 'mouseenter', function( e ) {
		if ( $( this ).is( '.menu-item-has-children' ) ) {
			$header.addClass( 'has-current-submenu' );
		} else {
			$header.removeClass( 'has-current-submenu' );
		}
	} );

	// If the user's mouse exits the main menu, reset submenu visibility to its
	// original state.
	$nav.find( '.menu' ).on( 'mouseleave', reset_submenu );

	// Add CSS transitions *after* above has been processed, to prevent initial
	// transition on page load
	setTimeout( function() {
		$header.addClass( 'is-animated' );
	}, 0 );

	// Allow different styling for when the main nav is being sticky.
	$( window ).on( 'scroll', _.throttle( function() {
		if ( $( this ).scrollTop() > 0 ) {
			$header.addClass( 'has-sticky-nav' );
		} else {
			$header.removeClass( 'has-sticky-nav' );
		}
	}, 100 ) );
};
