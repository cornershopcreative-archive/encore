<?php
/**
 * We're using admin-post.php to proxy form submission handling into Gravity Forms,
 * since the original forms used custom markup that would be hard to alter to match a GForm's HTML
 * The forms originally were processed by Blue State Digital hence the custom HTML.
 *
 * See https://www.sitepoint.com/handling-post-requests-the-wordpress-way/ for a basic outline of this approach
 */
function crate_gforms_proxy() {

	// Make sure GFAPI is available
	if ( ! class_exists('GFAPI') ) {
		return;
	}

	// Make sure we have a gform_id
	if ( ! isset( $_POST['gform_id'] ) ) {
		return;
	}

	$gform_id = $_POST['gform_id'];
	$field_key_map = false;
	$gform_submission_data = array();

	switch ( $gform_id ) {
		case "1":
			$field_key_map = array(
				'firstname'         => 'input_9_3',
				'lastname'          => 'input_9_6',
				'email'             => 'input_8',
				'zip'               => 'input_5',
				'best-contact-time' => 'input_11',
				'custom-24'         => 'input_6',
				'crowdskout'        => 'input_10'
			);
			break;
	}

	// Don't try to submit if we don't have a map
	if ( ! $field_key_map ) {
		return;
	}

	foreach( $field_key_map as $postkey => $gformkey ) {
		$gform_submission_data[ $gformkey ] = $_POST[ $postkey ];
	}

	// We have a gform id and an array of submission data, let's submit this!
	$submission = GFAPI::submit_form(
		(int) $gform_id,
		$gform_submission_data
	);

	// return json that might be used later (shrug)
	wp_send_json( $submission );
}
add_action( 'admin_post_nopriv_gform_proxy', 'crate_gforms_proxy' );
add_action( 'admin_post_gform_proxy', 'crate_gforms_proxy' );
