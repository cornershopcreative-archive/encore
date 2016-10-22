<?php
/**
 * Template tags.
 *
 * Utility functions, odds & ends, mainly for use in content section templates.
 */

/**
 * Output an opening <a> tag for the current section item, if appropriate.
 *
 * This function relies on a naming convention for ACF sub-fields:
 */
function crate_item_link( $attrs = array(), $prefix = '', $use_sub_fields = true ) {

	// Determine whether to use get_sub_field() or get_field().
	$get_field_function = ( $use_sub_fields ? 'get_sub_field' : 'get_field' );

	// Add an underscore to $prefix if it's non-empty, so we can prefix all field
	// names.
	$prefix = ( $prefix ? $prefix . '_' : '' );

	// Get the Link URL field.
	$link_url = $get_field_function( $prefix . 'link_url' );

	// If there's nothing to link to, bail.
	if ( ! $link_url ) {
		return;
	}

	// Get link options, and translate them to HTML attributes.
	$link_options = $get_field_function( $prefix . 'link_options' );
	if ( is_array( $link_options ) && in_array( 'target_blank', $link_options ) ) {
		$attrs['target'] = '_blank';
		$attrs['rel'] = 'noopener noreferrer';
	}

	$html = '<a href="' . esc_url( $link_url ) . '"';
	foreach ( $attrs as $attr_name => $attr_value ) {
		$html .= " $attr_name=" . '"' . esc_attr( $attr_value ) . '"';
	}
	$html .= '>';

	echo $html;
}

/**
 * Output a closing </a> tag for the current section item, if appropriate.
 */
function crate_item_link_close( $prefix = '', $use_sub_fields = true ) {

	// Determine whether to use get_sub_field() or get_field().
	$get_field_function = ( $use_sub_fields ? 'get_sub_field' : 'get_field' );

	// Determine the field name to use.
	$field_name = ( $prefix ? "{$prefix}_link_url" : 'link_url' );

	// If the current section item has a URL to link to, just spit out a closing
	// tag. It doesn't matter what the link options, etc. are.
	if ( $link_url = $get_field_function( $field_name) ) {
		echo '</a>';
	}
}

/**
 * Return true or false depending on whether the current section item has a
 * link.
 */
function crate_item_has_link( $prefix = '', $use_sub_fields = true ) {

	// Determine whether to use get_sub_field() or get_field().
	$get_field_function = ( $use_sub_fields ? 'get_sub_field' : 'get_field' );

	// Determine the field name to use.
	$field_name = ( $prefix ? "{$prefix}_link_url" : 'link_url' );

	// Return true if the link_url field is present, or false if not.
	return !! $get_field_function( $field_name );
}

/**
 * Output an opening <a> tag for the current post (don't use sub-fields).
 */
function crate_post_item_link( $attrs = array(), $prefix = '' ) {
	return crate_item_link( $attrs, $prefix, false );
}

/**
 * Output a closing </a> tag for the current post (don't use sub-fields).
 */
function crate_post_item_link_close( $prefix = '' ) {
	return crate_item_link_close( $prefix, false );
}

/**
 * Return true or false depending on whether the current post has a link (don't
 * use sub-fields).
 */
function crate_post_item_has_link( $prefix = '' ) {
	return crate_item_has_link( $prefix, false );
}

