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
	$total_pages = (int)$params['total_pages'];
	$page = (int)$params['page'];

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
	elseif ( $page - 5 < 1 ) {
		// If the current page is less than 5 pages away from the first page, start
		// at page 1.
		$start_page = 1;
		$end_page = 5;
	}
	elseif ( $page + 5 > $total_pages ) {
		// If the current page is less than 5 pages away from the last page, start
		// 5 pages before the last page.
		$start_page = $total_pages - 5;
		$end_page = $total_pages;
	}
	else {
		// If none of the above apply, just link to two pages before and two pages
		// after the current page.
		$start_page = $page - 2;
		$end_page = $page + 2;
	}

	for ( $i = $start_page; $i <= $end_page; $i++ ) {
		$output .= '<a class="page-number-link facetwp-page' . ( $i === $page ? ' active' : '' ) . '" data-page="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</a>';
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
