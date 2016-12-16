<?php
/**
 * Functions/hooks/etc. for the Conditional Overlay-like auto lightbox.
 */

/**
 * Outputs the theme options into a JS var for use.
 */
function crate_lightbox_echo_config() {
	$config = array(
		'context'   => get_field( 'coverlay_visibility', 'options' ),
		'trigger'   => get_field( 'coverlay_trigger', 'options' ),
		'amount'    => get_field( 'coverlay_trigger_amount', 'options' ),
	);
	echo '<script>window.lightbox_opts = ' . json_encode( $config ) . ';</script>';
}
add_action( 'wp_head', 'crate_lightbox_echo_config' );

