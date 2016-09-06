<?php
/**
 * Makes some basic improvements to the wp-generated body class
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/body_class
 *
 * @package Crate
 */

function crate_body_class( $classes ) {

	//cleanup template names, because who needs -php
	foreach ( $classes as &$class ) {
		if (strpos( $class, '-php' ) ) {
			$class = str_replace( array( 'page-', '-php' ), '', $class );
		}
	}

	//add the slug as a class, for picky css targeting
	if ( is_singular() ) {
		global $post;
		$classes[] = "single-" . $post->post_name;
	}

	return $classes;
}
add_filter( 'body_class', 'crate_body_class' );