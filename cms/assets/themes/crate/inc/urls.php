<?php

/**
 * Any custom URL handling, redirection or rewrite actions/filters go in here
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }


/**
 * URL Rewrite API to display other stuff
 *
 * http://codex.wordpress.org/Rewrite_API/add_rewrite_rule
 * http://codex.wordpress.org/Rewrite_API/add_rewrite_tag
 * NOTE: Remember to flush/update rules under Settings > Permalinks before changes will take effect
 */
function crate_rewrite_rule_and_tag() {

    add_rewrite_rule( '^waterkeeper/([^/]*)/?', 'index.php?pagename=waterkeeper&wko=$matches[1]', 'top' );
    add_rewrite_tag( '%wko%','([^/]+)' );

    return;
}
//add_action( 'init', 'crate_rewrite_rule_and_tag', 99 );


/**
 * See http://codex.wordpress.org/Plugin_API/Filter_Reference/template_include
 */
function crate_alter_template( $template ) {

	$new_template = $template;

	// put custom handlers here

	// load JSON alternatives
	if ( is_ajax() ) {
		$filename = basename( $new_template, ".php" );
		$json_template = locate_template( array( $filename . "-json.php" ) );
		if ( !empty( $json_template ) ) $new_template = $json_template;
	}
	return $new_template;

}
add_filter( 'template_include', 'crate_alter_template' );