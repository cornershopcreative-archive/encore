<?php

/**
 * The place to alter main queries
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

// Some examples just for guidance...
function crate_pre_get_posts( $query ) {

	// Up the limit for number of partners to display on a single page
	if ( $query->get('post_type') == 'partner' && $query->is_main_query() && !$query->is_single() ) {
		$query->set('posts_per_page', 100 );
	}

	// Up the number of events per page
	if ( $query->get('post_type') == 'event' && $query->is_main_query() && !$query->is_single() ) {
		$query->set('posts_per_page', 30 );
	}

	// Allow other post types on an author page
	if ( $query->is_author() && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'news', 'video', 'story' ) );
	}

	// Hide archived action alerts
	if ( $query->is_main_query() && $query->is_tax( 'regions' ) ) {
		$meta_query = $query->get( 'meta_query' );

		$meta_query[] = array(
			'key' => 'archive_action_alert',
			'value' => '1',
			'compare' => '!=',
		);

		$query->set( 'meta_query', $meta_query );
		$query->set( 'posts_per_page', 9 );
	}

	return $query;
}

if ( !is_admin() ) {
	//add_filter( 'pre_get_posts', 'crate_pre_get_posts' );
}
