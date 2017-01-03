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


/**
 * When showing a list of news stories in wp-admin, add a column heading for
 * sticky status.
 */
function crate_news_columns( $columns ) {
	$columns['sticky'] = 'Sticky';
	return $columns;
}
add_filter( 'manage_news_posts_columns', 'crate_news_columns' );

/**
 * Render the content of the 'related programs' column for FAQ question lists
 * in wp-admin.
 */
function crate_news_column( $column ) {

	if ( 'sticky' === $column ) {

		if ( get_field( 'sticky' ) ) {
			echo '<span class="dashicons dashicons-sticky" title="This post has been marked as sticky."></span>';
		}
	}
}
add_action( 'manage_news_posts_custom_column', 'crate_news_column' );

/**
 * Make the 'sticky' column in news post listings sortable.
 */
function crate_news_sortable_columns( $columns ) {

	$columns['sticky'] = array(
		// First array value: the orderby parameter that will be used when the user
		// sorts by this column.
		'sticky',
		// Second array value: TRUE means that this column should be sorted
		// descending by default, instead of ascending. We want descending so that
		// sticky posts (with a meta value of 1) are displayed first.
		true,
	);

	return $columns;
}
add_filter( 'manage_edit-news_sortable_columns', 'crate_news_sortable_columns' );

/**
 * Add CSS for styling the custom admin columns.
 */
function crate_admin_css() {
	?>
	<style>
	.fixed .column-sticky {
		width: 10%; /* Match narrow columns: .column-posts, .column-date, etc. */
	}
	</style>
	<?php
}
add_action( 'admin_head', 'crate_admin_css' );
