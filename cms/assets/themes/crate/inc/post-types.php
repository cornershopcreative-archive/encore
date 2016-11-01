<?php

/**
 * The place to outline custom post types and their taxonomies
 * Fields/meta boxes could be defined in here too, if using meta-box plugin instead of ACF or something
 */

if ( !defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' );
}

// Register Custom Post Type
function crate_post_types() {

	// Partners
	// Post Type contains Partners
	// Plural: Partners
	// Singular: Partner
	// URL: /partners/

	$labels = array(
		'name' 			=> _x( 'Partner', 'Post Type General Name', 'text_domain' ),
		'singular_name' => _x( 'Partner', 'Post Type Singular Name', 'text_domain' ),
		'menu_name' 	=> __( 'Partners', 'text_domain' ),
		'parent_item_colon' => __( '', 'text_domain' ),
		'all_items' 	=> __( 'All Partners', 'text_domain' ),
		'view_item' 	=> __( '', 'text_domain' ),
		'add_new_item' 	=> __( 'Add New Partner', 'text_domain' ),
		'add_new' 		=> __( 'Add New Partner', 'text_domain' ),
		'edit_item' 	=> __( 'Edit Partner', 'text_domain' ),
		'update_item' 	=> __( 'Update Partner', 'text_domain' ),
		'search_items' 	=> __( 'Search the Partners', 'text_domain' ),
		'not_found' 	=> __( 'No Partners found', 'text_domain' ),
		'not_found_in_trash' => __( 'No Partners found in trash', 'text_domain' ),
	);

	$args = array(
		'label' 		=> __( 'partner', 'text_domain' ),
		'description' 	=> __( 'Partner', 'text_domain' ),
		'labels' 		=> $labels,
		'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		'taxonomies' 	=> array(),
		'hierarchical' 	=> false,
		'public' 		=> true,
		'show_ui' 		=> true,
		'show_in_menu' 	=> true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'menu_icon' 	=> 'dashicons-money',
		'rewrite' 		=> array( 'slug' => 'partners' ),
		'can_export' 	=> true,
		'has_archive' 	=> true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);

	register_post_type('partners', $args);


	// News
	// Post Type contains News
	// Plural: News
	// Singular: News
	// URL: /news/

	$labels = array(
		'name' 			=> _x( 'News', 'Post Type General Name', 'text_domain' ),
		'singular_name' => _x( 'News', 'Post Type Singular Name', 'text_domain' ),
		'menu_name' 	=> __( 'News', 'text_domain' ),
		'parent_item_colon' => __( '', 'text_domain' ),
		'all_items' 	=> __( 'All News', 'text_domain' ),
		'view_item' 	=> __( '', 'text_domain' ),
		'add_new_item' 	=> __( 'Add New News Item', 'text_domain' ),
		'add_new' 		=> __( 'Add New News Item', 'text_domain' ),
		'edit_item' 	=> __( 'Edit News Item', 'text_domain' ),
		'update_item' 	=> __( 'Update News Item', 'text_domain' ),
		'search_items' 	=> __( 'Search the News', 'text_domain' ),
		'not_found' 	=> __( 'No News Items found', 'text_domain' ),
		'not_found_in_trash' => __( 'No News Items found in trash', 'text_domain' ),
	);

	$args = array(
		'label' 		=> __( 'news', 'text_domain' ),
		'description' 	=> __( 'News', 'text_domain' ),
		'labels' 		=> $labels,
		'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		'taxonomies' 	=> array(),
		'hierarchical' 	=> false,
		'public' 		=> true,
		'show_ui' 		=> true,
		'show_in_menu' 	=> true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'menu_icon' 	=> 'dashicons-money',
		'rewrite' 		=> array( 'slug' => 'news' ),
		'can_export' 	=> true,
		'has_archive' 	=> true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);

	register_post_type('news', $args);

	

	// Opportunities
	// Post Type contains Opportunities
	// Plural: Opportunities
	// Singular: Opportunity
	// URL: /opportunities/


	$labels = array(
		'name' 			=> _x( 'Opportunity', 'Post Type General Name', 'text_domain' ),
		'singular_name' => _x( 'Opportunity', 'Post Type Singular Name', 'text_domain' ),
		'menu_name' 	=> __( 'Opportunities', 'text_domain' ),
		'parent_item_colon' => __( '', 'text_domain' ),
		'all_items' 	=> __( 'All Opportunities', 'text_domain' ),
		'view_item' 	=> __( '', 'text_domain' ),
		'add_new_item' 	=> __( 'Add New Opportunity', 'text_domain' ),
		'add_new' 		=> __( 'Add New Opportunity', 'text_domain' ),
		'edit_item' 	=> __( 'Edit Opportunity', 'text_domain' ),
		'update_item' 	=> __( 'Update Opportunity', 'text_domain' ),
		'search_items' 	=> __( 'Search the Opportunities', 'text_domain' ),
		'not_found' 	=> __( 'No Opportunities found', 'text_domain' ),
		'not_found_in_trash' => __( 'No Opportunities found in trash', 'text_domain' ),
	);
	$args = array(
		'label' 		=> __( 'opportunity', 'text_domain' ),
		'description' 	=> __( 'Opportunity', 'text_domain' ),
		'labels' 		=> $labels,
		'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		'taxonomies' 	=> array(),
		'hierarchical' 	=> false,
		'public' 		=> true,
		'show_ui' 		=> true,
		'show_in_menu' 	=> true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'menu_icon' 	=> 'dashicons-money',
		'rewrite' 		=> array( 'slug' => 'opportunities' ),
		'can_export' 	=> true,
		'has_archive' 	=> true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	// NEVER USE 'action' HERE, IT'S A RESERVED WORD
	register_post_type( 'opportunities', $args );

	// Stories
	// Post Type contains Stories
	// Plural: Stories
	// Singular: Story
	// URL: /stories/

	$labels = array(
		'name' 			=> _x( 'Story', 'Post Type General Name', 'text_domain' ),
		'singular_name' => _x( 'Story', 'Post Type Singular Name', 'text_domain' ),
		'menu_name' 	=> __( 'Stories', 'text_domain' ),
		'parent_item_colon' => __( '', 'text_domain' ),
		'all_items' 	=> __( 'All Stories', 'text_domain' ),
		'view_item' 	=> __( '', 'text_domain' ),
		'add_new_item' 	=> __( 'Add New Story', 'text_domain' ),
		'add_new' 		=> __( 'Add New Story', 'text_domain' ),
		'edit_item' 	=> __( 'Edit Story', 'text_domain' ),
		'update_item' 	=> __( 'Update Story', 'text_domain' ),
		'search_items' 	=> __( 'Search the Stories', 'text_domain' ),
		'not_found' 	=> __( 'No Stories found', 'text_domain' ),
		'not_found_in_trash' => __( 'No Stories found in trash', 'text_domain' ),
	);

	$args = array(
		'label' 		=> __( 'story', 'text_domain' ),
		'description' 	=> __( 'Story', 'text_domain' ),
		'labels' 		=> $labels,
		'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		'taxonomies' 	=> array(),
		'hierarchical' 	=> false,
		'public' 		=> true,
		'show_ui' 		=> true,
		'show_in_menu' 	=> true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 5,
		'menu_icon' 	=> 'dashicons-money',
		'rewrite' 		=> array( 'slug' => 'stories' ),
		'can_export' 	=> true,
		'has_archive' 	=> true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);

	register_post_type('stories', $args);
}

add_action( 'init', 'crate_post_types', 0 );



function crate_taxonomies() {
	register_taxonomy(
		'location',	// taxonomy machine name
		array( 'post', 'partners', 'news', 'opportunities', 'stories'),		// post types supported, can be array('post', 'page' ... )
		array(					// labels. See http://codex.wordpress.org/Function_Reference/register_taxonomy for full details
			'label' => __( 'Locations', 'crate' ),
			'rewrite' => array( 'slug' => 'location' ),
			'hierarchical' => true,
			'public' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
		)
	);

	// for pre-existing cpts and taxonomies that just need to be linked, use register_taxonomy_for_object_type( $taxonomy, $object_type );

	register_taxonomy(
		'topic',	// taxonomy machine name
		array( 'post', 'partners', 'news', 'opportunities', 'stories'),		// post types supported, can be array('post', 'page' ... )
		array(					// labels. See http://codex.wordpress.org/Function_Reference/register_taxonomy for full details
			'label' => __( 'Topics', 'crate' ),
			'rewrite' => array( 'slug' => 'topic' ),
			'hierarchical' => true,
			'public' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
		)
	);

	// for pre-existing cpts and taxonomies that just need to be linked, use register_taxonomy_for_object_type( $taxonomy, $object_type );
}



add_action( 'init', 'crate_taxonomies' );

add_action( 'init', 'remove_tags' );
function remove_tags() {
    global $wp_taxonomies;
    $tax = 'post_tag'; // this may be wrong, I never remember the names on the defaults
    if( taxonomy_exists( $tax ) )
        unset( $wp_taxonomies[$tax] );
}

add_action( 'init', 'remove_categories' );
function remove_categories() {
    global $wp_taxonomies;
    $tax = 'category'; // this may be wrong, I never remember the names on the defaults
    if( taxonomy_exists( $tax ) )
        unset( $wp_taxonomies[$tax] );
}

/**
 * Use the link_url field for News Item permalinks.
 */
function crate_news_post_type_link( $post_link, $post, $leavename, $sample ) {

	// Bail if we're just generating a sample permalink for use in wp-admin.
	if ( $leavename || $sample ) {
		return $post_link;
	}

	// Bail if this is a non-News post.
	if ( 'news' !== $post->post_type ) {
		return $post_link;
	}

	// If an external link has been set, return it.
	if ( $external_link = get_field( 'link_url', $post->ID ) ) {
		return $external_link;
	}

	// Otherwise, pass the permalink through unaltered.
	return $post_link;
}
add_filter( 'post_type_link', 'crate_news_post_type_link', 10, 4 );
