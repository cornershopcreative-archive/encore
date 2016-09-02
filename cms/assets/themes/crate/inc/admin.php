<?php

/**
 * Functions that hack, add pages to, or otherwise modify the admin UI
 * This includes the login screen styling
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

// Hook for altering admin menus
function crate_alter_admin_menus() {
	global $menu, $submenu;

	//Remove menus we don't need
	$restricted = array(__('Feed Items', 'crate'));
	foreach( $menu as $key => $item) {
		$text_title = trim(strip_tags($item[0]));
		if ( in_array($text_title, $restricted) ) {
			unset( $menu[$key] );
		}
	}

	//Add a handy Edit Home link
	if ( get_option('page_on_front') ) {
		$submenu['edit.php?post_type=page'][15] = array(
			__('Edit Home', 'crate'), 'edit_pages', 'post.php?action=edit&post=' . get_option('page_on_front')
		);
	}

}
add_action('admin_menu', 'crate_alter_admin_menus');


/**
 * Login customizations
 */
// logo url
function crate_logo_url() {
	return home_url();
}
//add_filter( 'login_headerurl', 'crate_logo_url' );

// logo title
function crate_logo_url_title() {
	return get_blognfo( 'name' );
}
//add_filter( 'login_headertitle', 'crate_logo_url_title' );

function crate_login_logo() { ?>
  <style type="text/css">
    .login #login h1 a {
      background-image: url(<?php echo get_template_directory_uri(); ?>/images/loginLogo.png);
      padding-bottom: 75px;
      background-size: auto !important;
      width: 515px;
    }
  </style>
<?php }
//add_action( 'login_enqueue_scripts', 'crate_login_logo' );


/**
 * Custom post type admin page hackery examples
 */
/**
 * Change the post manager page
 */
function crate_cpt_cols( $columns ) {
	//remove coauthors, tags, add thumb, source
	$columns = array(
		'cb' => '<input type="checkbox">',
		'thumb' => 'X',
		'title' => 'Title',
		'source' => 'Source',
		'prominence' => 'Prominence',
		'categories' => 'Categories',
		'date' => 'Date'
	);
	return $columns;
}
//add_filter('manage_cpt_posts_columns' , 'crate_cpt_cols');

function crate_custom_column( $column, $post_id ) {
    switch ( $column ) {
      case 'thumb' :
      	echo get_the_post_thumbnail( $post_id, array(32,32) );
        break;

      case 'source' :
      	$member_id = get_post_meta( $post_id , 'from_member_id' , true );
      	if ( $member_id ) {
      		$mem = get_post( $member_id );
          echo $mem->post_title;
				}
        break;

      case 'prominence' :
      	$terms = get_the_term_list( $post_id, 'prominence', '', ', ', '');
      	echo ( $terms ) ? $terms : '-';
      	break;
    }
}
//add_action( 'manage_cpt_posts_custom_column' , 'crate_custom_column', 10, 2 );

function crate_col_style() {
	?>
	<style>
		.column-thumb { width: 32px; }
		th#thumb { font-size: 150%; text-align: center; color: #666;	}
	</style>
	<?php
}
//add_action( 'admin_head', 'crate_col_style' );

/**
 * Reminder about DB_NAME
 */
function crate_db_notice(){
  echo '<div class="notice error">
     <p>The database is currently set to <strong>demo_wordpress</strong>. Please use a different database!</p>
  </div>';
}
if ( DB_NAME == 'dev_wordpress' || DB_NAME == 'demo_wordpress' )	add_action('admin_notices', 'crate_db_notice');