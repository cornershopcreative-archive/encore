var $ = require( 'jQuery' );
var Mustache = require( 'mustache' );

module.exports = function( $ ) {

	$('.section-volunteer-opportunities a.more').on( 'click', function() {
		var $container = $(this).closest('.content-section'),
			pageNum = $container.data('page');

		$.get( '/wp-json/vmatch/v1/basic/page/' + (++pageNum), function( data ) {
			// increment page number
			$container.data( 'page', pageNum );

			// suppress more button if warranted
			if ( data.resultsSize <= pageNum * 20 ) {
				$(this).hide();
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
			console.log( rendered );
			$('.content-section-grid', $container).append( rendered );

		});

		// link should do nothing
		return false;
		
	});
};
