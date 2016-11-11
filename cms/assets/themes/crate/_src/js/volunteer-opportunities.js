var $ = require( 'jQuery' );
var Mustache = require( 'mustache' );

var load_handler = function( is_reset ) {

	return function( data ) {

		var $container = $( this ).closest( '.content-section' ),
			location = $container.data( 'location' ),
			searchTerms = $container.find( '.filter-search' ).val(),
			pageNum = ( is_reset ? 0 : $container.data( 'page' ) );

		console.log( $container, location, searchTerms, pageNum );

		$.get( '/wp-json/vmatch/v1/search/page/' + (++pageNum), {
			location: location,
			keywords: searchTerms
		}, function( data ) {

			// Clear out existing entries, if appropriate.
			if ( is_reset ) {
				$container.find( '.content-section-grid' ).html( '' );
			}

			// Set or reset page number.
			$container.data( 'page', pageNum );

			// suppress more button if warranted
			if ( data.resultsSize <= pageNum * 18 ) {
				$container.find( 'a.more' ).hide();
			} else {
				$container.find( 'a.more' ).show();
			}

			// load up template
			var template = $('#volunteer-opportunity').html(),
				rendered = '';

			Mustache.parse(template);

			// build out markup
			$.each( data.organizations, function( index, org ) {
				rendered += Mustache.render(template, org );
			});

			// inject into DOM
			$container.find('.content-section-grid').append( rendered );
		});

		// Prevent normal action.
		return false;
	};
};

module.exports = function( $ ) {

	$('.section-volunteer-opportunities .filter-form').on( 'submit', load_handler( true ) );

	$('.section-volunteer-opportunities a.more').on( 'click', load_handler() );
};
