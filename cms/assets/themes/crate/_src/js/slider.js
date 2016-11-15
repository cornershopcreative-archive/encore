var $ = require( 'jQuery' );
var cycle2 = require( 'jquery-cycle2' );

module.exports = function( $ ) {

	// Find each slider section.
	$( '.content-section-slider' ).each( function() {

		var $slider = $( this );

		// Get slides.
		var $items = $slider.find( '.slider-item' );

		// Skip slider initialization for slider sections with only one slide.
		if ( $items.length < 2 ) {
			return;
		}

		// Initialize cycle2.
		$slider.find( '.slider-items' ).cycle( {
			fx: 'scrollHorz',
			autoHeight: 'container',
			// Note that autoHeight: 'container' and fx: 'scrollHorz' don't play all
			// that well together, so we need a couple CSS opacity rules to make them
			// look sane (see _src/scss/sections/_content-sections.scss).
			timeout: $slider.data( 'timeout' ) || 0,
			slides: '.slider-item',
			prev: $slider.find( '.slider-prev' ),
			next: $slider.find( '.slider-next' ),
			// Wait until images are loaded to calculate container heights.
			loader: 'wait'
		} );

		// Add a class to indicate that cycle2 has been / is being initialized.
		// We can use this to show the slider controls, and hide them if this class
		// isn't present.
		$( this ).addClass( 'slider-is-active' );

	} );
};
