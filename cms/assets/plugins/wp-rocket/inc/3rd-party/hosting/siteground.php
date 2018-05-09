<?php
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

if ( rocket_is_plugin_active( 'sg-cachepress/sg-cachepress.php' ) ) {
	global $sg_cachepress_supercacher, $sg_cachepress_environment;

	if ( isset( $sg_cachepress_environment ) && $sg_cachepress_environment instanceof SG_CachePress_Environment && $sg_cachepress_environment->cache_is_enabled() ) {
		add_action( 'wp_ajax_sg-cachepress-purge', 'rocket_clean_domain', 0 );
		add_action( 'admin_post_sg-cachepress-purge', 'rocket_clean_domain', 0 );
		add_action( 'after_rocket_clean_domain', 'rocket_clean_supercacher' );
		add_filter( 'rocket_display_varnish_options_tab', '__return_false' );
	}

	/**
	 * Call the cache server to purge the cache with SuperCacher (SiteGround) Pretty good hosting!
	 *
	 * @since 2.3
	 *
	 * @return void
	 */
	function rocket_clean_supercacher() {
		if ( isset( $sg_cachepress_supercacher ) && $sg_cachepress_supercacher instanceof SG_CachePress_Supercacher ) {
			$sg_cachepress_supercacher->purge_cache();
		}
	}
}