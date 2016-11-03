<?php
/**
 * Filters, etc. for 'pseudo-archive' functionality (using Pages as post type
 * archives).
 */

/**
 * When viewing a single post of a type that has a pseudo-archive, highlight
 * the pseudo-archive page and its ancestors in nav menus.
 */
function crate_pseudo_archive_nav_item_class( $classes, $item, $args ) {

	$pseudo_archive_id = crate_get_pseudo_archive_id();

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

/**
 * When looking for a post type archive link, use the post type's
 * pseudo-archive's permalink if there is one.
 */
function crate_pseudo_archive_link( $link, $post_type ) {

	// Get pseudo-archive ID, if any.
	$pseudo_archive_id = get_field( 'page_for_' . $post_type, 'option' );

	// If a pseudo-archive was found, use its permalink.
	if ( $pseudo_archive_id ) {
		$link = get_permalink( $pseudo_archive_id );
	}

	return $link;
}
add_filter( 'post_type_archive_link', 'crate_pseudo_archive_link', 10, 2 );

/**
 * Get the ID of the current post's pseudo-archive, if any.
 */
function crate_get_pseudo_archive_id( $post = 0 ) {

	$post = get_post( $post );

	// If no post found, bail.
	if ( ! $post ) {
		return;
	}

	return get_field( 'page_for_' . $post->post_type, 'option' );
}
