<?php
/**
 * Handing (de)enqueueing scripts and styles to be loaded into Crate
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access not allowed' ); }

/**
 * Main enqueue handler for front-end of the site
 */
function crate_enqueue() {

  if ( ! is_admin() ) {
		wp_enqueue_style( 'crate_style', get_template_directory_uri() . '/css/crate.css', array(), '4.4.2' );
		wp_enqueue_script( 'crate', get_template_directory_uri() . '/js/crate.js', array('jquery'), '4.4.2', true );

		// put the AJAX endpoint URL into theme.ajaxurl
		wp_localize_script( 'crate', 'crate', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

}
add_action( 'wp_enqueue_scripts', 'crate_enqueue' );


/**
 * Get rid of emoji stuff
 */
function crate_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'crate_disable_emoji', 99 );
