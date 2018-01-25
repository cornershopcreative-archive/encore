<?php
/**
 * Gravity Forms hooks/filters.
 *
 * @package Crate
 */

/**
 * Add classes to Gravity Forms input elements.
 *
 * @param string $class The class string to filter.
 * @param object $field The field object.
 */
function crate_gform_field_css_class( $class, $field ) {

	$class .= ' field_type_' . sanitize_html_class( $field->type );

	// If this field has a size attribute, add a class to the field wrapper element. Otherwise, GF
	// will only add a class to the <input> element itself, which makes styling a pain.
	if ( isset( $field->size ) ) {
		$class .= ' field_size_' . sanitize_html_class( $field->size );
	}

	// If this field has multiple input elements, add a class indicating that.
	if ( isset( $field->inputs ) && ! empty( $field->inputs ) ) {
		$class .= ' field_complex';
	}

	return $class;
}
add_filter( 'gform_field_css_class', 'crate_gform_field_css_class', 10, 2 );

/**
 * Add classes to Gravity Forms submit buttons, because `@extend .button-solid` isn't working
 * (probably because of some fancy mixins).
 *
 * @param string $button_input Button HTML.
 */
function crate_gform_submit_button( $button_input ) {
	$button_input = str_replace( "class='", "class='button-gold button-solid ", $button_input );
	return $button_input;
}
add_filter( 'gform_submit_button', 'crate_gform_submit_button' );
