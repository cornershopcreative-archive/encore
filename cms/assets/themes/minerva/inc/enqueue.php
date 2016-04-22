<?php

/**
 * Enqueue stuff in frontend
 * For enqueueing scripts needed in the admin UI or login, see inc/admin.php
 */

if (!defined('ABSPATH')) {die('Direct access not allowed');}

add_action('wp_enqueue_scripts', 'minerva_enqueue_assets');
function minerva_enqueue_assets() {

	//use Google's hosted jQuery in the hopes the client already has it.
	//note that going past 1.8.x in jquery can sometimes lead to problems
	wp_deregister_script('jquery');
	wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js", '1.11.2', true);

	// Fix Jupiter's incorrect Vimeo CDN domain, which doesn't support SSL
	wp_deregister_script('api-vimeo');
	wp_register_script('api-vimeo', '//f.vimeocdn.com/js/froogaloop2.min.js', array(), false, false);

	//our scripts and styles
	wp_register_script('minerva-plugins', get_stylesheet_directory_uri() . '/js/plugins.min.js', array('jquery'), false, true);
	wp_register_script('minerva-main', get_stylesheet_directory_uri() . '/js/main.min.js', array('jquery', 'minerva-plugins'), false, true);
	wp_register_style('minerva-core', get_stylesheet_directory_uri() . '/css/core.min.css', array(), '1.0', 'all'); //could also be "screen"
	// wp_register_style( 'minerva-print',   get_stylesheet_directory_uri() . '/css/print.css',     array(), '1.0', 'print');

	//during development, avoid minified versions and run livereoad
	if (defined('ENVIRONMENT') && ENVIRONMENT == 'dev') {
		wp_deregister_script('minerva-plugins');
		wp_deregister_script('minerva-main');
		wp_deregister_style('minerva-core');
		wp_register_script('minerva-plugins', get_stylesheet_directory_uri() . '/js/plugins.js', array('jquery'), false, true);
		wp_register_script('minerva-main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery', 'minerva-plugins'), false, true);
		wp_register_style('minerva-core', get_stylesheet_directory_uri() . '/css/core.css', array(), '1.0', 'all'); //could also be "screen"

		if (defined('LIVERELOAD_PORT')) {
			wp_enqueue_script('livereload', '//' . $_SERVER['SERVER_NAME'] . ':' . LIVERELOAD_PORT . "/livereload.js", array('main'), false, true);
		}
	}

	if (!is_admin()) {
		wp_enqueue_script('minerva-main');
		wp_enqueue_style('minerva-core');
		// wp_enqueue_style( 'minerva-print');
		// get the AJAX endpoint URL & liveReload
		wp_localize_script('minerva-main', 'theme_obj', array('ajaxurl' => admin_url('admin-ajax.php')));
	}
}
