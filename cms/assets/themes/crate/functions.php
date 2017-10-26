<?php
/**
 * Crate functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Crate
 */

if ( ! function_exists( 'crate_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function crate_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Empty Crate, use a find and replace
	 * to change 'crate' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'crate', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'hero-lg', 1280, 400, true ); // Page hero image.
	add_image_size( 'hero-sm-2x', 750, 428, true ); // Page hero image, taller mobile version (@2x).
	add_image_size( 'hero-sm', 375, 214, true ); // Page hero image, taller mobile version.
	add_image_size( 'square-lg', 1000, 1000, true ); // gallery image cropped
	add_image_size( 'square-md', 500, 500, true ); // Larger grid items (Stories, maybe others later).
	add_image_size( 'square-sm', 250, 250, true ); // Circle Grid section item.
	add_image_size( 'slider-item-2x', 1960, 880, true ); // Action Slider section item (@2x).
	add_image_size( 'slider-item', 980, 440, true ); // Action Slider section item.

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'  => __( 'Primary', 'crate' ),
		'footer-1' => __( 'Footer column 1', 'crate' ),
		'footer-2' => __( 'Footer column 2 (desktop only)', 'crate' ),
		'footer-3' => __( 'Footer column 3 (desktop only)', 'crate' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
}
endif;
add_action( 'after_setup_theme', 'crate_setup' );

/**
 * Allow extra-large images to be included in the srcset + sizes attributes
 * that WP generates for wp_get_attachment_image(), etc.
 */
function crate_max_srcset_image_width( $width, $size_array ) {
	return 2600; // WP's default is 1600 px.
}
add_filter( 'max_srcset_image_width', 'crate_max_srcset_image_width', 10, 2 );

/**
 * Include other functions
 */
foreach ( glob( __DIR__ . '/inc/*.{php,inc}', GLOB_BRACE ) as $filename ) {
	include $filename;
}

function my_facetwp_is_main_query( $is_main_query, $query ) {
    if ( isset( $query->query_vars['facetwp'] ) ) {
        $is_main_query = true;
    }
    return $is_main_query;
}
add_filter( 'facetwp_is_main_query', 'my_facetwp_is_main_query', 10, 2 );

add_filter( 'facetwp_proximity_store_distance', '__return_true' );
define( 'GMAPS_API_KEY', 'AIzaSyCs_39jH3aZxdo0MZ5FANzQUfFJqCqGBI8' );

