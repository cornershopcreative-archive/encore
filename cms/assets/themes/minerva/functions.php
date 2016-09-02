<?php

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

/**
 * Minerva functions and definitions, based on Crate's 
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )	$content_width = 640;


if ( ! function_exists( 'minerva_setup' ) ):
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
function minerva_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	// add_editor_style('css/editor-style.css');

	// Add default posts and comments RSS feed links to head
	// add_theme_support( 'automatic-feed-links' );

	// Use Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	// add_theme_support( 'post-thumbnails' );

	// Let WordPress generate the <title> tag in the newfangled 4.1 way
	// add_theme_support( 'title-tag' );

	// Make the default markup for some things more semantic HTML5-y
	// add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

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
add_action( 'after_setup_theme', 'minerva_setup' );

/**
 * Load up all of the other goodies from the /inc directory
 */
$includes = array(
	// '/inc/urls.php',        // URL rewriting and redirection handlers as well as template overrides
	// '/inc/query.php',       // pre_get_posts() query alterations
	'/inc/enqueue.php',        // Enqueue styles and scripts
	// '/inc/widgets.php',     // Widget regions and custom widgets
	// '/inc/admin.php',       // Modifications to admin pages, new admin pages, etc
	// '/inc/shortcodes.php',  // Custom shortcodes
	// '/inc/filters.php',     // Filters for overriding default WP behavior
	// '/inc/menus.php',       // Define menus and custom menu walkers
	// '/inc/post-types.php',  // Custom post type definitions
	// '/inc/utilities.php',   // Misc helper functions and conditionals for templates
	// '/inc/acf-export.php',  // Exported code from ACF so the plugin isn't needed
	/**
	 * Stuff that might still need a home:
	 *		scheduler/cron-related stuff
	 *		feed-related filters and overrides
	 *		ajax handlers
	 */
);

foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

/**
 * Returns the current page URL
 */
function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";

	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/**
* Fix Visual Composer Raw HTML breaking after edit & save
* http://codex.wordpress.org/Plugin_API/Filter_Reference/content_save_pre
*/
function minerva_content_save_pre_fix_vc_raw_html( $content ) {
	$eol = PHP_EOL;
	if (strpos($content, "[vc_raw_html]\r\n") !== FALSE) {
		// Windows & email standard, CRLF
		$eol = "\r\n";
	} elseif (strpos($content, "[vc_raw_html]\n\r") !== FALSE) {
		// Should theoretically never happen, no one uses LFCR
		// Check anyway, just in case
		$eol = "\n\r";
	} elseif (strpos($content, "[vc_raw_html]\n") !== FALSE) {
		// *nix & mac standard, LF
		$eol = "\n";
	} elseif (strpos($content, "[vc_raw_html]\r") !== FALSE) {
		// Legacy mac standard, CR
		$eol = "\r";
	} else {
		// Can't determine newline or no erroneous raw html shortcode present
		// Return unfiltered
		return $content;
	}

	// VC places two newlines between the shortcode start, content, and end
	// This breaks the Visual Composer
    $content = str_replace('[vc_raw_html]'.$eol.$eol, '[vc_raw_html]', $content);
    $content = str_replace($eol.$eol.'[/vc_raw_html]', '[/vc_raw_html]', $content);
    return $content;
}
add_filter( 'content_save_pre' , 'minerva_content_save_pre_fix_vc_raw_html' , 90, 1);