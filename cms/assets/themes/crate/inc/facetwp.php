<?php
/**
 * FacetWP-related functions and filters.
 */

/**
 * Always load FacetWP frontend scripts.
 *
 * This allows us to use the FacetWP pager without having to use facets
 * anywhere on the page. Also, as long as caching, Expires headers and stuff
 * are all set up properly, why not always load the scripts?
 */
add_filter( 'facetwp_load_assets', '__return_true' );

/**
 * Check the 'facetwp' query var to determine which queries FacetWP should
 * filter.
 */
function crate_facetwp_is_main_query( $is_main_query, $query ) {

	$is_main_query = ( isset( $query->query_vars['facetwp'] ) && $query->query_vars['facetwp'] );

	return $is_main_query;
}
add_filter( 'facetwp_is_main_query', 'crate_facetwp_is_main_query', 10, 2 );

/**
 * Customize the FacetWP pager HTML.
 */
function crate_facetwp_pager_html( $output, $params ) {

	// Clear out default pager HTML.
	$output = '';

	// Get relevant info from $params.
	$total_pages = (int) $params['total_pages'];
	$page = (int) $params['page'];

	// If there's only one page worth of content, don't bother outputting
	// pagination.
	if ( $total_pages < 2 ) {
		return '';
	}

	// Add a 'previous page' link, unless this is the first page.
	if ( $page > 1 ) {
		$output .= '<a class="facetwp-page prev" data-page="' . esc_attr( $page - 1 ) . '"><span class="icon-pager-arrow"></span></a>';
	} else {
		$output .= '<a class="disabled prev"><span class="icon-pager-arrow"></span></a>';
	}

	// Add links for up to five pages around the current page.
	// First, determine which pages should be the first & last page with
	// individual links.
	if ( $total_pages <= 5 ) {
		// If there are 5 pages or fewer, link them all.
		$start_page = 1;
		$end_page = $total_pages;
	}
	elseif ( $page - 3 < 1 ) {
		// If the current page is less than 3 pages away from the first page, show
		// the first five pages.
		$start_page = 1;
		$end_page = 5;
	}
	elseif ( $page + 3 > $total_pages ) {
		// If the current page is less than 3 pages away from the last page, show
		// the last five pages.
		$start_page = $total_pages - 4;
		$end_page = $total_pages;
	}
	else {
		// If none of the above apply, just link to two pages before and two pages
		// after the current page.
		$start_page = $page - 2;
		$end_page = $page + 2;
	}

	for ( $i = $start_page; $i <= $end_page; $i++ ) {
		$classes = array( 'page-number-link', 'facetwp-page' );
		// Mark the current page as active.
		if ( $i === $page ) {
			$classes[] = 'active';
		}
		// Add a class that can be used to hide the outer pages (more than 1 page
		// away from the current page) on mobile.
		if ( $i < $page - 1 || $i > $page + 1 ) {
			$classes[] = 'outer-page';
		}
		$output .= '<a class="' . esc_attr( join( ' ', $classes ) ) . '" data-page="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</a>';
	}

	// Add a 'next page' link.
	if ( $page < $total_pages ) {
		$output .= '<a class="facetwp-page next" data-page="' . esc_attr( $page + 1 ) . '"><span class="icon-pager-arrow"></span></a>';
	} else {
		$output .= '<a class="disabled next disabled"><span class="icon-pager-arrow"></span></a>';
	}

	return $output;
}
add_filter( 'facetwp_pager_html', 'crate_facetwp_pager_html', 10, 2 );

/**
 * Always show all options in the VM State dropdown.
 */
function crate_facetwp_facet_render_args( $args ) {

	if ( 'vm-state' === $args['facet']['name'] ) {
		// Temporarily remove $or_values property.
		$real_or_values = FWP()->or_values;
		unset( FWP()->or_values );
		// Re-load drodpown options. With $or_values unset, this will cause all
		// state options to be displayed, regardless of how other facets are set.
		$args['values'] = FWP()->facet->facet_types[ $args['facet']['type'] ]->load_values( $args );
		// Restore original $or_values property.
		FWP()->or_values = $real_or_values;
	}

	return $args;
}
add_filter( 'facetwp_facet_render_args', 'crate_facetwp_facet_render_args' );

/**
 * Show more results for search facets. By default, FacetWP uses a SearchWP
 * query that only returns up to 200 results, but we want more sometimes.
 */
function crate_facetwp_searchwp_query_args( $args ) {
	// If this looks like the query that FacetWP generates (in
	// plugins/facetwp/includes/integrations/searchwp/searchwp.php), increase the
	// posts_per_page value to something we're less likely to actually run into.
	if ( isset( $args['facetwp'] ) && $args['facetwp'] && 200 === $args['posts_per_page'] ) {
		$args['posts_per_page'] = 1000;
	}
	return $args;
}
add_filter( 'searchwp_swp_query_args', 'crate_facetwp_searchwp_query_args' );
