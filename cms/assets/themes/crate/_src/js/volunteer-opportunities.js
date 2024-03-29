var $ = require( 'jQuery' );
var Mustache = require( 'mustache' );

var load_handler = function( is_reset ) {

	return function( data ) {

		var $container = $( this ).closest( '.content-section' ),
			location = $container.data( 'location' ),
			searchTerms = $container.find( '.filter-search' ).val(),
			radius = $container.find( '.filter-radius' ).val(),
			pageNum = ( is_reset ? 0 : $container.data( 'page' ) );

		if ( $container.find( '.filter-location' ).val() ) {
			location = $container.find( '.filter-location' ).val();
		}

		$.get( '/wp-json/vmatch/v1/search/page/' + (++pageNum), {
			location: location,
			keywords: searchTerms,
			radius: radius
		}, function( data ) {

			// Clear out existing entries, if appropriate.
			if ( is_reset ) {
				var resultHeader = '<h4>' + data.resultsSize + ' opportunities found.</h4>';
				// if we didn't get anything....
				if ( data.resultsSize == 0 ) {
					resultHeader = "<h4><br>Sorry, no opportunities found.<br><br></h4>";
				} else if ( data.resultsSize == 1 ) {
					resultHeader = '<h4>' + data.resultsSize + ' opportunity found.</h4>';
				}

				$container.find( '.content-section-grid' ).html( resultHeader );
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
			$.each( data.opportunities, function( index, org ) {
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

	$('.section-volunteer-opportunities .filter-form *').on( 'change', load_handler( true ) );
};
