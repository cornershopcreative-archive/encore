<?php

/**
 * The place to outline custom post types and their taxonomies
 * Fields/meta boxes could be defined in here too, if using meta-box plugin instead of ACF or something
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

// Register Custom Post Type
function crate_post_types() {

	$labels = array(
		'name'                => _x( 'News Stories', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'News', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'News', 'text_domain' ),
		'parent_item_colon'   => __( '', 'text_domain' ),
		'all_items'           => __( 'All News Stories', 'text_domain' ),
		'view_item'           => __( '', 'text_domain' ),
		'add_new_item'        => __( 'Add New News Story', 'text_domain' ),
		'add_new'             => __( 'Add New News Story', 'text_domain' ),
		'edit_item'           => __( 'Edit News Story', 'text_domain' ),
		'update_item'         => __( 'Update News Story', 'text_domain' ),
		'search_items'        => __( 'Search the News', 'text_domain' ),
		'not_found'           => __( 'No News stories found', 'text_domain' ),
		'not_found_in_trash'  => __( 'No News stories found in trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'news', 'text_domain' ),
		'description'         => __( 'News', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		'taxonomies'          => array( 'category'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => get_template_directory_uri() . '/images/admin/newspaper.png',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);

	// NEVER USE 'action' HERE, IT'S A RESERVED WORD
	register_post_type( 'news', $args );
}
// add_action( 'init', 'crate_post_types', 0 );


function crate_taxonomies() {
	register_taxonomy(
		'classification',	// taxonomy machine name
		'post',				// post types supported, can be array('post', 'page' ... )
		array(					// labels. See http://codex.wordpress.org/Function_Reference/register_taxonomy for full details
			'label' => __( 'Classification', 'crate' ),
			'rewrite' => array( 'slug' => 'classification' ),
			'hierarchical' => true,
		)
	);

	// for pre-existing cpts and taxonomies that just need to be linked, use register_taxonomy_for_object_type( $taxonomy, $object_type );
}
//add_action( 'init', 'crate_taxonomies' );
