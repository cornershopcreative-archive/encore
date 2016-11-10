<?php
/**
 * General-purpose theme shortcodes.
 */

/**
 * Output a single button.
 */
function crate_button_shortcode( $args = array(), $content ) {

	$args = shortcode_atts( array(
		'href' => '',
		'target' => '',
		'color' => '',
		'style' => '',
	), $args );

	// Initialize list of classes.
	$classes = array( 'button' );

	// Add color class, if color attribute was a valid color.
	if ( in_array( $args['color'], array(
		'gold',
		'lime',
		'teal',
		'bright-blue',
		'deep-blue',
	), true ) ) {
		$classes[] = 'button-' . $args['color'];
	}

	// Add style class(es), if valid.
	foreach ( explode( ' ', $args['style'] ) as $style ) {
		if ( in_array( $style, array(
			'solid',
			'facebook',
			'twitter',
		), true ) ) {
			$classes[] = 'button-' . $style;
		}
	}

	// Set additional attributes, if any.
	$other_attrs = '';
	if ( '_blank' === $args['target'] ) {
		$other_attrs .= ' target="_blank" rel="noopener noreferrer"';
	}

	return '<a href="' . esc_url( $args['href'] ) . '" class="' . esc_attr( join( ' ', $classes ) ) . '"' . $other_attrs . '>' . do_shortcode( $content ) . '</a>';
}
add_shortcode( 'button', 'crate_button_shortcode' );

/**
 * Output a button-group wrapper (centers and aligns a group of buttons).
 */
function crate_button_group_shortcode( $args = array(), $content ) {
	return '<div class="button-group">' . do_shortcode( $content ) . '</div>';
}
add_shortcode( 'button-group', 'crate_button_group_shortcode' );
