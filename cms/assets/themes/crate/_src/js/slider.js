var $ = require( 'jQuery' );
var cycle2 = require( 'jquery-cycle2' );

module.exports = function( $ ) {

	// Find each slider section.
	$( '.content-section-slider' ).each( function() {

		// Initialize cycle2.
		$( this ).find( '.slider-items' ).cycle( {
			fx: 'scrollHorz',
			autoHeight: 'container',
			// Note that autoHeight: 'container' and fx: 'scrollHorz' don't play all
			// that well together, so we need a couple CSS opacity rules to make them
			// look sane (see _src/scss/sections/_content-sections.scss).
			timeout: 0, // No automatic animation.
			slides: '.slider-item',
			prev: $( this ).find( '.slider-prev' ),
			next: $( this ).find( '.slider-next' ),
			// Wait until images are loaded to calculate container heights.
			loader: 'wait'
		} );

	} );
};
