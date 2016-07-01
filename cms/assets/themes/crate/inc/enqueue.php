<?php
if ( !defined('ABSPATH') ) { die('Direct access not allowed'); }

/**
 * Enqueue stuff in frontend
 * For enqueueing scripts needed in the admin UI or login, see inc/admin.php
 */
function crate_enqueue_assets() {

	//our scripts and styles
	wp_register_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.js',   false, '3.3.1', false );
	wp_register_script( 'plugins',   get_template_directory_uri() . '/js/plugins.min.js', array('jquery'), false, true );
	wp_register_script( 'main',      get_template_directory_uri() . '/js/main.min.js',    array('jquery', 'plugins'), false, true );
	wp_register_style(  'crate',     get_template_directory_uri() . '/css/core.min.css',  array(), '3.1.1', 'all' ); //could also be "screen"
	wp_register_style(  'print',     get_template_directory_uri() . '/css/print.min.css', array(), '3.1.1', 'print' );

	//during development, avoid minified versions and run livereoad
	if ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {

		wp_deregister_script( 'main' );
		wp_deregister_style(  'crate' );
		wp_deregister_style(  'print' );

		wp_register_script( 'main',  get_template_directory_uri() . '/js/main.js',    array('jquery', 'plugins'), false, true );
		wp_register_style(  'crate', get_template_directory_uri() . '/css/core.css',  array(), '3.1.1', 'all' ); //could also be "screen"
		wp_register_style(  'print', get_template_directory_uri() . '/css/print.css', array(), '3.1.1', 'print' );

		if ( defined('LIVERELOAD_PORT') ) {
			wp_enqueue_script( 'livereload', '//' . $_SERVER['SERVER_NAME'] . ':' . LIVERELOAD_PORT . "/livereload.js", array('main'), false, true );
		}
	}

	// make sure we only add this stuff to the frontend
	if ( !is_admin() ) {
		//use CDN jQuery in the hopes the client already has it, or at least it gets there fast
		//note that going past 1.8.x in jquery can sometimes lead to problems
		//as can loading 3rd party jQuery that still clings to $, which is why we only do this on the front end
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', "//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js", '3.0.0', true );

		wp_enqueue_script( 'modernizr' );
		wp_enqueue_script( 'main' );
		wp_enqueue_style(  'crate' );
		wp_enqueue_style(  'crate-print' );
		// put the AJAX endpoint URL into theme.ajaxurl
		wp_localize_script( 'main', 'theme', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	}
}
add_action('wp_enqueue_scripts', 'crate_enqueue_assets');