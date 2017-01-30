module.exports = function( $ ) {

	$( '.section-dropdown' ).each( function() {

		var $menu = $( this ).find( '.section-dropdown-menu' );
		var $options = $menu.find( '.option' );
		var $sets = $( this ).find( '.section-dropdown-set' );

		$( document ).click( function( e ) {
			$menu.removeClass( 'is-open' );
		} );

		$menu.click( function( e ) {
			e.preventDefault();
			e.stopPropagation();
			$menu.toggleClass( 'is-open' );
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
			$menu.removeClass( 'is-open' );
		} );

		$options.eq(0).click();

	} );
};
