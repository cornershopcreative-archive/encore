<?php
/**
 * Filters, etc. for 'pseudo-archive' functionality (using Pages as post type
 * archives).
 */

function crate_pseudo_archive_nav_item_class( $classes, $item, $args ) {

	$pseudo_archive_id = false;

	if ( is_singular( 'stories' ) ) {
		// Get the Stories pseudo-archive page.
		$pseudo_archive_id = get_field( 'page_for_stories', 'option' );
	}

	// Only bother with the following logic if there actually is a
	// pseudo-archive, AND this menu item points to post.
	if ( $pseudo_archive_id && isset( $item->object_id ) ) {

		// If this menu item IS the pseudo-archive, highlight it as the parent.
		if ( $pseudo_archive_id == $item->object_id ) {
			$classes[] = 'current-menu-ancestor';
			$classes[] = 'current-menu-parent';
		}
		// Otherwise, if the pseudo-archive is a descendent of this item, highlight
		// it as an ancestor.
		else {
			$stories_page_ancestors = get_post_ancestors( $pseudo_archive_id );
			if ( in_array( $item->object_id, $stories_page_ancestors ) ) {
				$classes[] = 'current-menu-ancestor';
			}
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'crate_pseudo_archive_nav_item_class', 10, 3 );
