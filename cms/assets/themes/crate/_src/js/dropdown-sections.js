module.exports = function( $ ) {

	$( '.section-dropdown' ).each( function() {

		var $menu = $( this ).find( '.section-dropdown-menu' );
		var $options = $menu.find( '.option' );
		var $sets = $( this ).find( '.section-dropdown-set' );
		var close_timer;

		$( document ).click( function( e ) {
			$menu.removeClass( 'is-open is-clicked' );
		} );

		$menu.click( function( e ) {
			e.preventDefault();
			e.stopPropagation();
			$menu.addClass( 'is-open is-clicked' );
		} ).hover( function() {
			clearTimeout( close_timer );
			$menu.addClass( 'is-open' );
		}, function() {
			close_timer = setTimeout( function() {
				if ( ! $menu.is( '.is-clicked' ) ) {
					$menu.removeClass( 'is-open' );
				}
			}, 300 );
		} );

		$options.click( function( e ) {
			e.preventDefault();
			e.stopPropagation();
			// Hide all sets & show the selected set.
			$sets.removeClass( 'is-active' );
			$sets.filter( $( this ).attr( 'href' ) ).addClass( 'is-active' );
			// Change the text of the current option to match this dropdown option's
			// text.
			$menu.find( '.current-option' ).html( $( this ).html() );
			$menu.removeClass( 'is-open is-clicked' );
		} );

		$options.eq(0).click();

	} );
};
