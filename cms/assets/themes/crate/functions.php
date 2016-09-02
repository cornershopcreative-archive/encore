<?php
/**
 * Empty Crate functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Empty_Crate
 */

if ( ! function_exists( 'empty_crate_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function empty_crate_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Empty Crate, use a find and replace
	 * to change 'empty_crate' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'empty_crate', get_template_directory() . '/languages' );

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
	
	add_image_size( 'gallery', 800, 800, true ); // gallery image cropped

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'empty_crate' ),
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
add_action( 'after_setup_theme', 'empty_crate_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function empty_crate_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'empty_crate_content_width', 640 );
}
add_action( 'after_setup_theme', 'empty_crate_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function empty_crate_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'empty_crate' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'empty_crate' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'empty_crate_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function empty_crate_enqueue() {
	wp_enqueue_style( 'empty_crate_theme_setup', get_stylesheet_uri() );
	
	wp_enqueue_style( 'empty_crate_style', get_template_directory_uri() . '/css/crate.css', array(), '1.0.0' );

	wp_enqueue_script( 'empty_crate_scripts', get_template_directory_uri() . '/js/crate.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'empty_crate_enqueue' );

/**
 * Include other functions
 */
//require get_template_directory() . '/inc/custom-header.php';