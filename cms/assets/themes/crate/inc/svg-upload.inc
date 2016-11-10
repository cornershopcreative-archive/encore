<?php
/**
 * Allow upload of SVG images.
 */

function crate_upload_mimes( $existing_mimes = array() ) {
	$existing_mimes['svg'] = 'image/svg+xml';
	return $existing_mimes;
}
add_filter( 'upload_mimes', 'crate_upload_mimes' );
