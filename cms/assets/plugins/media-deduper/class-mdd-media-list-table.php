<?php
/**
 * Media Library List Table class.
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
		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Eliminate some E_NOTICES from class-wp-media-list-table.
		$this->is_trash = false;

		$this->set_pagination_args( array(
			'total_items' => $wp_query->found_posts,
			'total_pages' => $wp_query->max_num_pages,
			'per_page'    => $wp_query->query_vars['posts_per_page'],
		) );
	}

	/**
	 * @access public
	 */
	public function no_items() {
		_e( 'Great news, no duplicates were found!', 'media-deduper' );
	}

	/**
	 * @return array
	 */
	protected function get_bulk_actions() {
		$actions = array();
		$actions['delete']      = __( 'Delete Permanently', 'media-deduper' );
		$actions['smartdelete'] = __( 'Delete Preserving Featured', 'media-deduper' );
		return $actions;
	}

	/**
	 * Handles the file size column output.
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_mdd_size( $post ) {
		$filesize = get_post_meta( $post->ID, 'mdd_size', true );
		if ( ! $filesize ) {
			echo __( 'Unknown', 'media-deduper' );
		} else {
			echo size_format( $filesize );
		}
	}

	/**
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'title'    => 'title',
			'author'   => 'author',
			'parent'   => 'parent',
			'comments' => 'comment_count',
			'date'     => array( 'date', true ),
			'mdd_size' => array( 'mdd_size', true ),
		);
	}
}
