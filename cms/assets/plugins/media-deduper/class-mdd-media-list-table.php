<?php
/**
 * Media Library List Table class.
 *
 */
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
require_once( ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php' );

/**
 * Based on WP_Media_List_Table
 */
class MDD_Media_List_Table extends WP_Media_List_Table {

	/**
	 * @global WP_Query $wp_query
	 */
	public function prepare_items() {
		global $wp_query;

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();

    $data = $this->table_data();

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;

		$this->set_pagination_args( array(
			'total_items' => $wp_query->found_posts,
			'total_pages' => $wp_query->max_num_pages,
			'per_page' => $wp_query->query_vars['posts_per_page'],
		) );
	}

	/**
	 * @access public
	 */
	public function no_items() {
		_e( 'Great news, no duplicates were found!', 'media-deduper' );
	}
}
