<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed' );
}

/**
 * crate functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 */


if ( ! function_exists( 'crate_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override crate_setup() in a child theme, add your own crate_setup to your child theme's
	 * functions.php file.
	 *
	 */
	function crate_setup() {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style( 'css/editor-style.css' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Use Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
		// Second argument can be an array of post types that should have them.
		add_theme_support( 'post-thumbnails' );

		// Let WordPress generate the <title> tag in the newfangled 4.1 way
		add_theme_support( 'title-tag' );

		// Make the default markup for some things more semantic HTML5-y
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

		//Uncomment for custom background in admin. Outputs inline CSS per http://codex.wordpress.org/Custom_Backgrounds
		//add_theme_support( 'custom-background' );

		//Make sure to call header_image() and/or get_custom_header() per http://codex.wordpress.org/Custom_Headers
		//add_theme_support( 'custom-header' );	//this also lets users set a text color, which crate doesn't support (because it's a pain)

		//Post formats, to be like Tumblr, see http://codex.wordpress.org/Post_Formats
		//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

		//Custom image sizes
		//add_image_size( 'slide', 938, 418, true );

	}
endif;
add_action( 'after_setup_theme', 'crate_setup' );

/**
 * Load up all of the other goodies from the /inc directory
 */
$includes = array(
	'/inc/urls.php',        // URL rewriting and redirection handlers as well as template overrides
	'/inc/query.php',       // pre_get_posts() query alterations
	'/inc/enqueue.php',     // Enqueue styles and scripts
	'/inc/widgets.php',     // Widget regions and custom widgets
	'/inc/admin.php',       // Modifications to admin pages, new admin pages, etc
	'/inc/shortcodes.php',  // Custom shortcodes
	'/inc/filters.php',     // Filters for overriding various WP behavior that doesn't belong elsewhere
	'/inc/menus.php',       // Define menus and custom menu walkers
	'/inc/post-types.php',  // Custom post type definitions
	'/inc/utilities.php',   // Misc helper functions and conditionals for templates
	'/inc/plugins.php',	    // Provides a way to delineate required plugins. Cool!
	/**
	 * Stuff that might still need a home:
			scheduler/cron-related stuff
			feed-related filters and overrides
			ajax handlers
	 */
);
foreach ( $includes as $include ) {
	require_once( get_template_directory() . $include );
}


/**
 * Make sure sites don't die when Kint Debugger isn't around
 */
if ( ! function_exists('d') ) {
	function d( $arg ) {}
}