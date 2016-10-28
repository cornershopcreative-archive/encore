/**
 * Add a 'More...' button to FacetWP templates.
 *
 * See https://facetwp.com/how-to-add-a-load-more-button/
 */

var $ = require( 'jQuery' );

module.exports = function() {

	if ( 'object' === typeof FWP ) {

		// When temporary 'is_load_more' property is set, append new posts instead
		// of replacing those already loaded on the page.
		wp.hooks.addFilter( 'facetwp/template_html', function( resp, params ) {
			if ( FWP.is_load_more ) {
				FWP.is_load_more = false;
				$( '.facetwp-template' ).append( params.html );
				return true;
			}
			return resp;
		} );

		// When 'load more' button is clicked, set 'is_load_more' property.
		$( document ).on( 'click', '.fwp-load-more', function() {
			$( '.fwp-load-more' ).html( $( '.fwp-load-more' ).attr( 'data-text-loading' ) );
			FWP.is_load_more = true;
			FWP.paged = parseInt( FWP.settings.pager.page ) + 1;
			FWP.soft_refresh = true;
			FWP.refresh();
		});

		// When more than one page of results is available, display a 'load more'
		// button.
		$( document ).on( 'facetwp-loaded', function() {
			if ( FWP.settings.pager.page < FWP.settings.pager.total_pages ) {
				if ( ! FWP.loaded && 1 > $( '.fwp-load-more' ).length ) {
					$( '#nav-below' ).html( '<div class="nav-previous"><button class="fwp-load-more">More Grants</button></div>' );
				}
				else {
					$( '.fwp-load-more' ).html( $( '.fwp-load-more' ).attr( 'data-text-more' ) ).parent().show();
				}
			}
			else {
				$( '.fwp-load-more' ).parent().hide();
			}
		} );

	}
};
