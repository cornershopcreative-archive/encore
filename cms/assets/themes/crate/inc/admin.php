<?php
/**
 * Functions that hack, add pages to, or otherwise modify the admin UI
 *
 * @package Crate
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

/**
 * Remove some admin menus we never use, and add an 'Edit Home' link in Pages
 */
function crate_alter_admin_menus() {
	global $menu, $submenu;

	//Remove menus we don't need
	$restricted = array( __('Feed Items', 'crate') );
	foreach( $menu as $key => $item) {
		$text_title = trim( strip_tags( $item[0] ) );
		if ( in_array( $text_title, $restricted ) ) {
			unset( $menu[$key] );
		}
	}

	//Add a handy Edit Home link
	if ( get_option( 'page_on_front' ) ) {
		$submenu['edit.php?post_type=page'][15] = array(
			__('Edit Home', 'crate'), 'edit_pages', 'post.php?action=edit&post=' . get_option('page_on_front')
		);
	}

}
add_action('admin_menu', 'crate_alter_admin_menus');


/**
 * Reminder about DB_NAME when working on the Cornershop development server
 */
function crate_db_notice(){
	echo '<div class="notice error"><p>The database is currently set to <strong>demo_wordpress</strong>. Please use a different database!</p></div>';
}
if ( DB_NAME == 'dev_wordpress' || DB_NAME == 'demo_wordpress' )add_action( 'admin_notices', 'crate_db_notice' );

/**
 * Remove the annoying Customize link from the admin bar
 */
function remove_admin_bar_customize( $wp_admin_bar ) {
	$wp_admin_bar->remove_menu( 'customize' );
}
add_action( 'admin_bar_menu', 'remove_admin_bar_customize', 999 );