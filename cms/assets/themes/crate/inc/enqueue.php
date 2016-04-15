<?php

/**
 * Enqueue stuff in frontend
 * For enqueueing scripts needed in the admin UI or login, see inc/admin.php
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }


add_action('wp_enqueue_scripts', 'crate_enqueue_assets');
function crate_enqueue_assets() {

	//use CDN jQuery in the hopes the client already has it, or at least it gets there fast
	//note that going past 1.8.x in jquery can sometimes lead to problems
	wp_deregister_script('jquery');
	wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js", '2.2.3', true);

	//our scripts and styles
	wp_register_script('plugins',   get_template_directory_uri() . '/js/plugins.min.js', array('jquery'), false, true);
	wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.js',   false, '3.3.1', false);
	wp_register_script('main',      get_template_directory_uri() . '/js/main.min.js',    array('jquery', 'plugins'), false, true);
	wp_register_style( 'crate',     get_template_directory_uri() . '/css/core.min.css',      array(), '3.1.1', 'all');	//could also be "screen"
	wp_register_style( 'print',     get_template_directory_uri() . '/css/print.min.css',     array(), '3.1.1', 'print');

	//during development, avoid minified versions and run livereoad
	if ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {

		wp_deregister_script( 'main' );
		wp_register_script('main',      get_template_directory_uri() . '/js/main.js',    array('jquery', 'plugins'), false, true);

		wp_deregister_style( 'crate' );
		wp_deregister_style( 'print' );
		wp_register_style( 'crate',     get_template_directory_uri() . '/css/core.css',      array(), '3.1.1', 'all');	//could also be "screen"
		wp_register_style( 'print',     get_template_directory_uri() . '/css/print.css',     array(), '3.1.1', 'print');

		if (  defined('LIVERELOAD_PORT') ) {
			wp_enqueue_script('livereload', '//' . $_SERVER['SERVER_NAME'] . ':' . LIVERELOAD_PORT . "/livereload.js", array('main'), false, true);
		}
	}

	if (!is_admin()) {
		wp_enqueue_script('modernizr');
		wp_enqueue_script('main');
		wp_enqueue_style( 'crate');
		wp_enqueue_style( 'crate-print');
		// get the AJAX endpoint URL & liveReload
		wp_localize_script( 'main', 'theme_obj', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	}
}

/**
 * Yepnope is no longer included in Modernizr, so this won't work anymore.
 * Leaving here for posterity
 */
function crate_selectivizr() {
	if (wp_script_is('modernizr', 'done')) :
	?><script>
	yepnope({
		test: Modernizr.generatedcontent && Modernizr.lastchild,
		nope: "<?php echo get_template_directory_uri() . '/js/selectivizr.min.js' ?>"
	});
</script><?php
	endif;
}
//add_action('wp_head', 'crate_selectivizr', 9);
