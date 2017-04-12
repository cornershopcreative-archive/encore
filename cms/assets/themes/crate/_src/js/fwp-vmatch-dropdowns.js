module.exports = function( $ ) {

	var previousStateValue;

	// facetwp-loaded fires AFTER a refresh/load has completed.
	$( document ).on( 'facetwp-loaded', function() {

		// Set previousStateValue so we can compare it with the new State facet
		// value on subsequent refreshes.
		if ( 'vm-state' in FWP.facets ) {
			previousStateValue = FWP.facets['vm-state'][0];
		}

		// facetwp-refresh fires BEFORE a refresh/load starts, and allows us to edit
		// facet values before the query is processed.
		//
		// Adding the listener here means that the initial refresh that occurs when
		// the page first loads will be ignored (which is what we want -- the city
		// and org values that are set when you first load the page should be
		// retained).
		$( document ).on( 'facetwp-refresh', function() {
			if ( 'vm-state' in FWP.facets ) {
				// Compare new state dropdown value with previous value. If the value
				// has changed, reset the city and organization dropdown values.
				if ( previousStateValue !== FWP.facets['vm-state'][0] ) {
					if ( 'vm-city' in FWP.facets ) {
						FWP.facets['vm-city'] = [];
					}
					if ( 'vm-organization' in FWP.facets ) {
						FWP.facets['vm-organization'] = [];
					}
				}
			}
		});
	});
};
