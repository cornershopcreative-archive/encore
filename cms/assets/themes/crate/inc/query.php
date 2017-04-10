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

/**
 * Add a query var for searching just the titles of posts.
 */
function crate_add_title_keyword_query_var( $qvars ) {
	$qvars[] = 'title_keywords';
	return $qvars;
}
add_filter( 'query_vars', 'crate_add_title_keyword_query_var' );

/**
 * Handle the title_keywords query var.
 */
function crate_title_keyword_posts_where( $where, &$query ) {

	if ( $keywords = $query->get( 'title_keywords' ) ) {

		global $wpdb;

		// Split string, grouping quoted substrings (thanks to
		// http://stackoverflow.com/a/15191418).

		// First, for negated quoted strings like '-"bad phrase"', place leading
		// hyphen inside the quotes, so str_getcsv treats the whole thing as a
		// quoted substring.
		$negated_quote_regex = '/^(.* )*-"([^"]*)"/';
		while ( preg_match( $negated_quote_regex, $keywords ) ) {
			$keywords = preg_replace( $negated_quote_regex, '\1"-\2"', $keywords );
		}
		// Split into single keywords and quoted substrings.
		$keywords = str_getcsv( $keywords, ' ' );

		// Loop over keywords and add LIKE/NOT LIKE clauses.
		foreach ( $keywords as $keyword ) {
			// Trim keyword.
			$keyword = trim( $keyword );
			// Skip empty keywords (which might happen if there are extra spaces in
			// the title_keywords query var value).
			if ( ! $keyword ) continue;
			// If '-' is the first character in this keyword, exclude posts that match
			// it instead of including them.
			if ( '-' === $keyword[0] ) {
				$keyword = substr( $keyword, 1 ); // Remove '-' from keyword.
				$like = 'NOT LIKE'; // Use 'NOT LIKE' in the clause for this keyword.
			} else {
				$like = 'LIKE'; // Use 'LIKE' in the clause for this keyword.
			}
			// Add a LIKE clause.
			$where .= " AND post_title $like '%" . esc_sql( $wpdb->esc_like( trim( $keyword ) ) ) . "%' ";
		}
	}

	return $where;
}
add_filter( 'posts_where', 'crate_title_keyword_posts_where', 10, 2 );
