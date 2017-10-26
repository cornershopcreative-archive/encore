<?php
/**
 * Redirect requests to the non-Gulp URL to the Gulp URL.
 *
 * @package Crate
 */

// Check that Gulp is running, or at least the inc/gulp-support.inc file that
// it generates exists.
if ( defined( 'BROWSERSYNC_PORT' ) ) {

	// Use the *normal* port for wp-cli, wp-cron, or admin requests.
	$disable_browsersync = (
		( defined( 'WP_CLI' ) && WP_CLI ) ||
		( defined( 'DOING_CRON' ) && DOING_CRON ) ||
		is_admin()
	);

	// Check for the presence of an HTTP header (added in the call to
	// browserSync.init() in gulpfile.js) that indicates that the current
	// request was made through Browsersync.
	$is_browsersynced = ! empty( $_SERVER['HTTP_X_BROWSERSYNCED'] );

	if ( ! $is_browsersynced && ! $disable_browsersync ) {

		// For non-admin pages, if the X-Browsersynced header isn't present, then
		// redirect to the current page with the Browsersync port added.
		$gulp_url = "//{$_SERVER['HTTP_HOST']}:" . BROWSERSYNC_PORT . $_SERVER['REQUEST_URI'];
		wp_redirect( $gulp_url );
		die;

	} elseif ( $is_browsersynced && $disable_browsersync ) {

		// If this is a Browsersync'ed admin request, reroute it to the normal
		// URL -- *don't* go through Browsersync. This will prevent admin pages
		// from reloading when files are edited, which is a drag.
		$non_gulp_url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		wp_redirect( $non_gulp_url );
		die;
	}
}
