<?php
/**
 * Function for altering WP queries: sort order, etc.
 */
function crate_pre_get_posts( $query ) {

	if ( $orderby = $query->get( 'orderby' ) ) {

		// If 'sticky' is among the sort options, then add a meta query for it.
		if ( ( 'sticky' === $orderby ) || isset( $orderby['sticky'] ) ) {
			// Get the current meta query, if any.
			$meta_query = (array) $query->get( 'meta_query' );
			// If there's no meta query component named 'sticky', add one. If there
			// is, we'll just assume it's one that makes sense for ordering and leave
			// it alone.
			if ( ! isset( $meta_query['sticky'] ) ) {
				$meta_query['sticky'] = array(
					'key' => 'sticky',
					'compare' => 'EXISTS',
				);
				$query->set( 'meta_query', $meta_query );
			}
		}
	}
}
add_action( 'pre_get_posts', 'crate_pre_get_posts' );
