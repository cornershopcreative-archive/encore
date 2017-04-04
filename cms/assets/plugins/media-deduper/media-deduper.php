<?php
/*
Plugin Name: Media Deduper
Version: 1.3
Description: Save disk space and bring some order to the chaos of your media library by removing and preventing duplicate files.
Plugin URI: https://cornershopcreative.com/
Author: Cornershop Creative
Author URI: https://cornershopcreative.com/

	Still to do:
		- better identification/handling of not-found and other error-producing attachments
		- dashboard panel?
		- PHPDoc commenting
		- indexing via cron for large libraries, or wp-cli
		- more screen options
*/

register_activation_hook( __FILE__, array( 'Media_Deduper', 'activate' ) );
register_uninstall_hook( __FILE__, array( 'Media_Deduper', 'uninstall' ) );

/**
 * The main Media Deduper plugin class.
 */
class Media_Deduper {

	const NOT_FOUND_HASH = 'not-found';
	const NOT_FOUND_SIZE = 0;
	const VERSION = '1.2.2';

	/**
	 * The number of attachments deleted during a 'smart delete' operation.
	 *
	 * @var int Set/incremented by Media_Deduper::smart_delete_media().
	 */
	protected $smart_deleted_count = 0;

	/**
	 * The number of attachments skipped during a 'smart delete' operation.
	 *
	 * @var int Set/incremented in Media_Deduper::smart_delete_media().
	 */
	protected $smart_skipped_count = 0;

	/**
	 * When the plugin is activated (initial install or update), do.... stuff
	 * Note that this checks 'site_option' instead of 'option' because multisite is a thing
	 * But right now the admin notices are not very smart about who's seeing them (e.g. multisite admin)
	 */
	static function activate() {

		$prev_version = get_site_option( 'mdd_version', false );
		if ( ! $prev_version || version_compare( Media_Deduper::VERSION, $prev_version ) ) {
			add_option( 'mdd-updated', true );
			update_site_option( 'mdd_version', Media_Deduper::VERSION );
		}

		// Delete transients, in case MDD was previously active and is being
		// re-enabled. Old duplicate counts, etc. are probably no longer accurate.
		Media_Deduper::delete_transients();

		Media_Deduper::db_index( 'add' );
	}

	/**
	 * Main constructor, primarily used for registering hooks.
	 */
	function __construct() {
		// Use an existing capabilty to check for privileges. manage_options may not be ideal, but gotta use something...
		$this->capability = apply_filters( 'media_deduper_cap', 'manage_options' );

		add_action( 'wp_ajax_calc_media_hash',    array( $this, 'ajax_calc_media_hash' ) );
		add_action( 'admin_menu',                 array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_notices',              array( $this, 'admin_notices' ), 11 );
		add_action( 'add_attachment',             array( $this, 'upload_calc_media_meta' ) );
		add_action( 'edit_attachment',            array( $this, 'upload_calc_media_meta' ) );

		add_filter( 'set-screen-option',          array( $this, 'save_screen_options' ), 10, 3 );
		add_filter( 'wp_handle_upload_prefilter', array( $this, 'block_duplicate_uploads' ) );

		register_deactivation_hook( __FILE__,     array( $this, 'deactivate' ) );

		// Class for handling outputting the duplicates.
		require_once( dirname( __FILE__ ) . '/class-mdd-media-list-table.php' );

		// Bulk action listener.
		if ( version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
			add_filter( 'handle_bulk_actions-upload', array( $this, 'handle_bulk_actions' ), 10, 3 );
		} else {
			// Back-compat bulk action listeners.
			add_action( 'admin_action_smartdelete',   array( $this, 'compat_bulk_actions' ) );
			add_action( 'admin_action_delete',        array( $this, 'compat_bulk_actions' ) );
		}

		// Handler for requests that *aren't* bulk actions (i.e. requests to change
		// list table filter parameters).
		add_action( 'admin_action_-1',            array( $this, 'filter_redirect' ) );

		// Set removable query args (used for displaying messages to the user).
		add_filter( 'removable_query_args',       array( $this, 'removable_query_args' ) );

		// Column handlers.
		add_filter( 'manage_upload_columns',          array( $this, 'media_columns' ) );
		add_filter( 'manage_upload_sortable_columns', array( $this, 'media_sortable_columns' ) );
		add_filter( 'manage_media_custom_column',     array( $this, 'media_custom_column' ), 10, 2 );

		// Query filters (for adding sorting options in wp-admin).
		if ( is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}
	}

	/**
	 * Enqueue the media js file from core. Also enqueue our own assets.
	 */
	public function enqueue_scripts( $hook_suffix ) {

		$screen = get_current_screen();

		// Enqueue the main media JS + our own JS on the Manage Duplicates screen.
		if ( 'media_page_media-deduper' === $screen->base ) {
			wp_enqueue_media();
			wp_enqueue_script( 'media-grid' );
			wp_enqueue_script( 'media' );
			wp_enqueue_script( 'media-deduper-js', plugins_url( 'media-deduper.js', __FILE__ ), array(), Media_Deduper::VERSION );
		}

		// Enqueue our admin CSS on both the Manage Duplicates screen and the main
		// Media Library screen. We need it on the latter screen in order to style
		// the custom mdd_size column.
		if ( in_array( $screen->base, array( 'upload', 'media_page_media-deduper' ), true ) ) {
			wp_enqueue_style( 'media-deduper', plugins_url( 'media-deduper.css', __FILE__ ), array(), Media_Deduper::VERSION );
		}
	}

	/**
	 * Remind people they need to do things.
	 */
	public function admin_notices() {

		$screen = get_current_screen();
		$html = '';

		if ( get_option( 'mdd-updated', false ) ) {

			// Update was just performed, not initial activation.
			$html = '<div class="updated notice is-dismissible"><p>';
			$html .= sprintf( __( 'Thanks for updating Media Deduper. Due to recent enhancements you’ll need to <a href="%s">regenerate the index</a>. Sorry for the inconvenience!', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';
			delete_option( 'mdd-updated' );

		} elseif ( ! get_option( 'mdd-activated', false ) && $this->get_count( 'indexed' ) < $this->get_count() ) {

			// On initial plugin activation, point to the indexing page.
			add_option( 'mdd-activated', true, '', 'no' );
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf( __( 'In order to manage duplicate media you must first <strong><a href="%s">generate the media index</a></strong>.', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';

		} elseif ( 'upload' === $screen->base && $this->get_count( 'indexed' ) < $this->get_count() ) {

			// Otherwise, complain about incomplete indexing if necessary.
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf( __( 'Media duplication index is not comprehensive, please <strong><a href="%s">update the index now</a></strong>.', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';

		} elseif ( 'media_page_media-deduper' === $screen->base ) {

			if ( isset( $_GET['smartdeleted'] ) ) {

				// The 'smartdelete' action has been performed. $_GET['smartdelete'] is
				// expected to be a comma-separated pair of values reflecting the number
				// of attachments deleted and the number of attachments that weren't
				// deleted (which happens if all other copies of an image have already
				// been deleted).
				list( $deleted, $skipped ) = array_map( 'absint', explode( ',', $_GET['smartdeleted'] ) );
				// Only output a message if at least one attachment was either deleted
				// or skipped.
				if ( $deleted || $skipped ) {
					$html = '<div class="updated notice is-dismissible"><p>';
					$html .= sprintf( __( 'Deleted %d items and skipped %d items.', 'media-deduper' ), $deleted, $skipped );
					$html .= '</p></div>';
				}
				// Remove the 'smartdeleted' query arg from the REQUEST_URI, since it's
				// served its purpose now and we don't want it weaseling its way into
				// redirect URLs or the like.
				$_SERVER['REQUEST_URI'] = remove_query_arg( 'smartdeleted', $_SERVER['REQUEST_URI'] );

			} elseif ( isset( $_GET['deleted'] ) ) {

				// The 'delete' action has been performed. $_GET['deleted'] is expected
				// to reflect the number of attachments deleted.
				// Only output a message if at least one attachment was deleted.
				if ( $deleted = absint( $_GET['deleted'] ) ) {
					// Show a simpler message if only one file was deleted (based on
					// wp-admin/upload.php).
					if ( 1 === $deleted ) {
						$message = __( 'Media file permanently deleted.', 'media-deduper' );
					} else {
						/* translators: %s: number of media files */
						$message = _n( '%s media file permanently deleted.', '%s media files permanently deleted.', $deleted, 'media-deduper' );
					}
					$html = '<div class="updated notice is-dismissible"><p>';
					$html .= sprintf( $message, number_format_i18n( $deleted ) );
					$html .= '</p></div>';
				}
				// Remove the 'deleted' query arg from REQUEST_URI.
				$_SERVER['REQUEST_URI'] = remove_query_arg( 'deleted', $_SERVER['REQUEST_URI'] );

			}//end if
		}//end if

		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Adds/removes DB index on meta_value to facilitate performance in finding dupes.
	 */
	static function db_index( $task = 'add' ) {

		global $wpdb;
		if ( 'add' === $task ) {
			$sql = "CREATE INDEX `mdd_hash_index` ON $wpdb->postmeta ( meta_value(32) );";
		} else {
			$sql = "DROP INDEX `mdd_hash_index` ON $wpdb->postmeta;";
		}

		$wpdb->query( $sql );

	}

	/**
	 * On deactivation, get rid of our index.
	 */
	public function deactivate() {

		global $wpdb;

		// Kill our index.
		Media_Deduper::db_index( 'remove' );
	}

	/**
	 * On uninstall, get rid of ALL junk.
	 */
	static function uninstall() {
		global $wpdb;

		// Kill our mdd_hashes and mdd_sizes. It's annoying to re-generate the
		// index but we don't want to pollute the DB.
		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'mdd_hash' ) );
		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'mdd_size' ) );

		// Kill our mysql table index.
		Media_Deduper::db_index( 'remove' );

		// Remove the option indicating activation.
		delete_option( 'mdd-activated' );
	}

	/**
	 * Prevents duplicates from being uploaded.
	 */
	function block_duplicate_uploads( $file ) {

		global $wpdb;

		$upload_hash = md5_file( $file['tmp_name'] );

		// Does our hash match?
		$sql = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'mdd_hash' AND meta_value = %s LIMIT 1;", $upload_hash );
		$matches = $wpdb->get_var( $sql );
		if ( $matches ) {
			// @todo Find a way to actually include HTML so we can use get_edit_post_link().
			$file['error'] = sprintf( __( 'It appears this file is already present in your media library as post %u!', 'media-deduper' ), $matches );
		}
		return $file;
	}

	/**
	 * Calculate the hash for a just-uploaded file.
	 */
	function upload_calc_media_meta( $post_id ) {
		$mediafile = get_attached_file( $post_id );

		$hash = $this->calculate_hash( $mediafile );
		$size = $this->calculate_size( $mediafile );

		$this->save_media_meta( $post_id, $size, 'mdd_size' );
		$save_meta_result = $this->save_media_meta( $post_id, $hash );

		// Delete transients, most importantly the attachment count (but duplicate
		// IDs and shared file IDs may have been affected too, if this post was
		// copied meta-value-for-meta-value from another post).
		Media_Deduper::delete_transients();

		return $save_meta_result;
	}

	/**
	 * This is where we compute the hash for a given attachment and store it in the DB.
	 */
	function ajax_calc_media_hash() {

		@error_reporting( 0 ); // Don't break the JSON result.

		$id = (int) $_POST['id'];
		$image = get_post( $id );

		if ( ! $image || 'attachment' !== $image->post_type ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'Failed hash: %d is an invalid attachment ID.', 'media-deduper' ), $id ) ) );
		}

		if ( ! current_user_can( $this->capability ) ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'You lack permissions to do this.', 'media-deduper' ) ) ) );
		}

		$mediafile = get_attached_file( $image->ID );

		if ( false === $mediafile || ! file_exists( $mediafile ) ) {
			$this->save_media_meta( $id, self::NOT_FOUND_HASH );
			$this->save_media_meta( $id, self::NOT_FOUND_SIZE, 'mdd_size' );
			wp_send_json_error( array(
				'error' => sprintf( __( 'Attachment file for <a href="%s">%s</a> could not be found.', 'media-deduper' ), get_edit_post_link( $id ), get_the_title( $id ) ),
				'post_id' => $id,
			) );
		}

		// @todo Actually save fails so that media don't get this reattempted repeatedly.
		if ( ! get_post_meta( $id, 'mdd_hash', true ) ) {
			$hash = $this->calculate_hash( $mediafile );
			$processed_hash = $this->save_media_meta( $image->ID, $hash );
		} else {
			$processed_hash = true;
		}

		// @todo As above, actually keep track of failures.
		if ( ! get_post_meta( $id, 'mdd_size', true ) ) {
			$size = $this->calculate_size( $mediafile );
			$processed_size = $this->save_media_meta( $image->ID, $size, 'mdd_size' );
		} else {
			$processed_size = true;
		}

		if ( ! $processed_hash ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'Hash for attachment %s could not be saved', 'media-deduper' ), esc_html( $image->ID ) ) ) );
		}

		if ( ! $processed_size ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'File size for attachment %s could not be saved', 'media-deduper' ), esc_html( $image->ID ) ) ) );
		}

		// Delete transients, most importantly the duplicate ID list + count of
		// indexed attachments.
		Media_Deduper::delete_transients();

		wp_send_json_success( array( 'message' => sprintf( __( 'Hash and size for attachment %s saved.', 'media-deduper' ), esc_html( $image->ID ) ) ) );

	}

	/**
	 * Calculate the size for a given file.
	 *
	 * Wrapper for filesize().
	 */
	private function calculate_size( $file ) {
		return filesize( $file );
	}

	/**
	 * Calculate the MD5 hash for a given file.
	 *
	 * Wrapper for md5_file().
	 */
	private function calculate_hash( $file ) {
		return md5_file( $file );
	}

	/**
	 * Save metadata for an attachment.
	 *
	 * Wrapper for update_post_meta().
	 */
	private function save_media_meta( $post_id, $value, $meta_key = 'mdd_hash' ) {
		return update_post_meta( $post_id, $meta_key, $value );
	}

	/**
	 * Return either the total # of attachments, or the # of indexed attachments.
	 *
	 * @param string $type The type of count to return. Use 'all' to count all
	 *                     attachments, or 'indexed' to count only attachments
	 *                     whose hash and size have already been calculated.
	 *                     Default 'all'.
	 */
	private function get_count( $type = 'all' ) {

		global $wpdb;

		switch ( $type ) {
			case 'all':
				$sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment';";
				break;
			case 'indexed':
			default:
				$sql = "SELECT COUNT(*) FROM $wpdb->posts p
					INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
					INNER JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id
					WHERE pm.meta_key = 'mdd_hash'
					AND pm2.meta_key = 'mdd_size'
					AND p.post_type = 'attachment';
					";
		}

		$result = get_transient( 'mdd_count_' . $type );
		if ( false === $result ) {
			$result = $wpdb->get_var( $sql );
			set_transient( 'mdd_count_' . $type, $result );
		}
		return $result;

	}

	/**
	 * Add to admin menu.
	 */
	function add_admin_menu() {
		$this->hook = add_media_page( __( 'Manage Duplicates', 'media-deduper' ), __( 'Manage Duplicates', 'media-deduper' ), $this->capability, 'media-deduper', array( $this, 'admin_screen' ) );

		add_action( 'load-' . $this->hook, array( $this, 'screen_tabs' ) );
	}

	/**
	 * Implements screen options.
	 */
	function screen_tabs() {

		$option = 'per_page';
		$args = array(
			'label'   => 'Items',
			'default' => get_option( 'posts_per_page', 20 ),
			'option'  => 'mdd_per_page',
		);
		add_screen_option( $option, $args );

		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'      => 'overview',
			'title'   => __( 'Overview' ),
			'content' =>
			'<p>' . __( 'Media Deduper was built to help you find and eliminate duplicate images and attachments from your WordPress media library.' )
				. '</p><p>' . __( 'Before Media Deduper can identify duplicate assets, it first must build an index of all the files in your media library, which can take some time.' )
				. '</p><p>' . __( 'Once its index is complete, Media Deduper will also prevent users from uploading duplicates of files already present in your media library.' )
				. '</p>',
		) );

		$screen->add_help_tab( array(
			'id'      => 'indexing',
			'title'   => __( 'Indexing' ),
			'content' =>
			'<p>' . __( 'Media Deduper needs to generate an index of your media files in order to determine which files match. It only looks at the files themselves, not any data in WordPress (such as title, caption or comments). Once that’s done, however, Media Deduper automatically adds new uploads to its index, so you shouldn’t have to generate the index again.' )
				. '</p><p>' . __( 'As a part of the indexing process, Media Deduper also stores information about each file’s size so duplicates can be sorted by disk space used, allow you to most efficiently perform cleanup.' )
				. '</p>',
		) );

		$screen->add_help_tab( array(
			'id'      => 'deletion',
			'title'   => __( 'Deletion' ),
			'content' =>
			'<p>' . __( 'Once Media Deduper has indexed your files and found duplicates, you can easily delete them in one of two ways:' )
				. '</p><p>' . __( 'Option 1: Delete Permanently. This option <em>permanently</em> deletes whichever files you select. This can be <em>very dangerous</em> as it cannot be undone, and you may inadvertently delete all versions of a file, regardless of how they are being used on the site.' )
				. '</p><p>' . __( 'Option 2: Delete Preserving Featured. This option preserves images that are assigned as Featured Images on posts. Deduper reassigns a single instance of the image to the post, and only deletes orphaned copies of that image. <em><strong>Please note:</strong></em> Although this option preserves Featured Images, it does <em>not</em> preserve media used in galleries, other shortcodes, custom fields, the bodies of posts, or other meta data. Please be careful.' )
				. '</p>',
		) );

		$screen->add_help_tab( array(
			'id'      => 'shared',
			'title'   => __( 'Shared Files' ),
			'content' =>
			'<p>' . __( 'In a typical WordPress installation, each different Media "post" relates to a separate file uploaded to the filesystem. However, some plugins facilitate copying media posts in a way that produces multiple posts all referencing a single file.' )
				. '</p><p>' . __( 'Media Deduper considers such posts to be "duplicates" because they share the same image data. However, in most cases you would not want to actually delete any of these posts because deleting any one of them would remove the media file they all share.' )
				. '</p><p>' . __( 'Because this can lead to unintentional data loss, Media Deduper prefers to suppress showing duplicates that share a file. However, it is possible to show these media items if you wish to review or delete them. <strong>Be extremely cautious</strong> when working with duplicates that share files as unintentional data loss can easily occur.' )
				. '</p>',
		) );

		$screen->add_help_tab( array(
			'id'      => 'about',
			'title'   => __( 'About' ),
			'content' =>
			'<p>' . __( 'Media Deduper was built by Cornershop Creative, on the web at <a href="https://cornershopcreative.com">https://cornershopcreative.com</a>' )
				. '</p><p>' . __( 'Need support? Got a feature idea? <a href="https://wordpress.org/support/plugin/media-deduper">Contact us on the wordpress.org plugin support page</a>. Thanks!' )
				. '</p>',
		) );
	}

	/**
	 * Saves screen options.
	 */
	function save_screen_options( $status, $option, $value ) {
		return $value; // Is that it?
	}

	/**
	 * The main admin screen!
	 */
	function admin_screen() {

		?>
		<div id="message" class="updated fade" style="display:none"></div>
		<div class="wrap deduper">
			<h1><?php esc_html_e( 'Media Deduper', 'media-deduper' ); ?></h1>
			<aside class="mdd-column-2">
				<div class="mdd-box">
					<h2>Like Media Deduper?</h2>
					<ul>
						<li class="share"><a href="#" data-service="facebook">Share it on Facebook »</a></li>
						<li class="share"><a href="#" data-service="twitter">Tweet it »</a></li>
						<li><a href="https://wordpress.org/support/plugin/media-deduper/reviews/#new-post" target="_blank">Review it on WordPress.org »</a></li>
					</ul>
				</div>
			</aside>
			<div class="mdd-column-1">
			<?php

			if ( ! empty( $_POST['mdd-build-index'] ) ) {
				// Capability check.
				if ( ! current_user_can( $this->capability ) ) {
					wp_die( esc_html__( 'Cheatin\' eh?', 'media-deduper' ) ); }

				// Form nonce check.
				check_admin_referer( 'media-deduper-index' );

				// Build the whole index-generator-progress-bar-ajax-thing.
				$this->process_index_screen();

			} else {
			?>

			<p><?php esc_html_e( 'Use this tool to identify duplicate media files in your site. It only looks at the files themselves, not any data in WordPress (such as title, caption or comments).', 'media-deduper' ); ?></p>
			<p><?php esc_html_e( 'In order to identify duplicate files, an index of all media must first be generated.', 'media-deduper' ); ?></p>

			<?php if ( $this->get_count( 'indexed' ) < $this->get_count() ) : ?>
				<p>
					<?php echo esc_html( sprintf(
						__( 'Looks like %u of %u media items have been indexed.', 'media-deduper' ),
						$this->get_count( 'indexed' ),
						$this->get_count()
					) ); ?>
					<strong><?php esc_html_e( 'Please index all media now.', 'media-deduper' ); ?></strong>
				</p>

				<form method="post" action="">
					<?php wp_nonce_field( 'media-deduper-index' ); ?>
					<p><input type="submit" class="button hide-if-no-js" name="mdd-build-index" id="mdd-build-index" value="<?php esc_attr_e( 'Index Media', 'media-deduper' ) ?>" /></p>

					<noscript><p><em><?php esc_html_e( 'You must enable Javascript in order to proceed!', 'media-deduper' ) ?></em></p></noscript>
				</form><br>

			<?php else : ?>
				<p><?php esc_html_e( 'All media have been indexed.', 'media-deduper' ); ?></p>
			<?php endif; ?>
			</div>

			<!-- the posts table -->
			<h2 style="clear:both;"><?php esc_html_e( 'Duplicate Media Files', 'media-deduper' ); ?></h2>
			<form id="posts-filter" method="get">
				<div class="wp-filter">
					<div class="view-switch">
						<select name="show_shared">
							<option value="0" <?php selected( ! isset( $_GET['show_shared'] ) || ( '0' === $_GET['show_shared'] ) ); ?>><?php esc_html_e( 'Hide duplicates that share files', 'media-deduper' ); ?></option>
							<option value="1" <?php selected( isset( $_GET['show_shared'] ) && ( '1' === $_GET['show_shared'] ) ); ?>><?php esc_html_e( 'Show duplicates that share files', 'media-deduper' ); ?></option>
						</select>
						<input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php esc_attr_e( 'Apply', 'media-deduper' ); ?>">
					</div>
					<a href="javascript:void(0);" id="shared-help"><?php esc_html_e( 'What\'s this?', 'media-deduper' ); ?></a>
				</div>
				<?php

				$this->get_duplicate_ids();

				// We use $wp_query (the main query) since Media_List_Table does and we extend that.
				global $wp_query;
				$query_parameters = array_merge( $_GET, array(
					'post__in'       => $this->duplicate_ids,
					'post_type'      => 'attachment',
					'post_status'    => 'inherit',
					'posts_per_page' => get_user_option( 'mdd_per_page' ),
				) );

				// If suppressing shared files (the default), do that
				if ( ! isset( $_GET['show_shared'] ) || 1 !== absint( $_GET['show_shared'] ) ) {
					$this->get_shared_filename_ids();
					$query_parameters['post__in'] = array_diff( $this->duplicate_ids, $this->shared_filename_ids );
					if ( ! count( $query_parameters['post__in'] ) ) {
						// We do this otherwise WP_Query's post__in gets an empty array and returns all posts
						$query_parameters['post__in'] = array( '0' );
					}
				}

				$wp_query = new WP_Query( $query_parameters );

				$wp_list_table = new MDD_Media_List_Table( array(
					// Even though this is really the 'media_page_media-deduper' screen,
					// we want to show the columns that would normally be shown on the
					// 'upload' screen, including taxonomy terms or any other columns
					// that other plugins might be adding.
					'screen' => 'upload',
				) );
				$wp_list_table->prepare_items();
				$wp_list_table->display();

				// This stuff makes the 'Attach' dialog work.
				wp_nonce_field( 'find-posts', '_ajax_nonce', false );
				?><input type="hidden" id="find-posts-input" name="ps" value="" /><div id="ajax-response"></div>
				<?php find_posts_div(); ?>
				</form>
			</div><?php
			}//end if
	}


	/**
	 * Output the indexing progress page.
	 */
	private function process_index_screen() {
		?>
		<p><?php esc_html_e( 'Please be patient while the index is generated. This can take a while, particularly if your server is slow or if you have many large media files. Do not navigate away from this page until this script is done.', 'media-deduper' ); ?></p>

		<noscript><p><em><?php esc_html_e( 'You must enable Javascript in order to proceed!', 'media-deduper' ) ?></em></p></noscript>

		<div id="mdd-bar">
			<div id="mdd-meter"></div>
			<div id="mdd-bar-percent"></div>
		</div>

		<p>
			<input type="button" class="button hide-if-no-js" name="mdd-stop" id="mdd-stop" value="<?php esc_attr_e( 'Abort', 'media-deduper' ) ?>" />
			<input type="button" class="button hide-if-no-js" name="mdd-manage" id="mdd-manage" value="<?php esc_attr_e( 'Manage Duplicates Now', 'media-deduper' ) ?>" />
		</p>

		<ul class="error-files">
		</ul>

		<script>
			// If mdd_config is present, media-deduper.js will handle all the processing
			var mdd_config = {
				id_list   : [<?php echo esc_html( implode( ',', $this->get_unhashed_ids() ) ); ?>],
				stopping  : "<?php esc_attr_e( 'Stopping...', 'media-deduper' ); ?>",
				media_url : "<?php echo esc_url_raw( admin_url( 'upload.php?page=media-deduper' ) ); ?>",
				complete  : {
					issues : "<?php
						echo '<p>'
							. esc_html__( 'Media indexing complete;', 'media-deduper' )
							. ' <strong>'
							. esc_html( sprintf(
								__( '%s files could not be indexed.', 'media-deduper' ),
								'{NUM}'
							) )
							. ' <a href=\'' . esc_url( admin_url( 'upload.php?page=media-deduper' ) ) . '\'>'
							. esc_html__( 'Manage duplicates now.', 'media-deduper' )
							. '</a></strong></p>';
					?>",
					perfect : "<p><?php esc_html_e( 'Media indexing complete;', 'media-deduper' ); ?> <strong><?php esc_html_e( 'All media successfully indexed.', 'media-deduper' ); ?></strong></p>",
					aborted : "<p><?php esc_html_e( 'Indexing aborted; only some media items indexed.', 'media-deduper' ); ?></p>",
				},
				ajax_fail : "<?php esc_html_e( 'Request EPIC FAIL', 'media-deduper' ); ?>"
			}
		</script>

		<?php
	}

	/**
	 * Retrieves a list of attachment posts that haven't yet had their file md5 hashes computed.
	 */
	private function get_unhashed_ids() {

		global $wpdb;

		$sql = "SELECT ID FROM $wpdb->posts p
						WHERE p.post_type = 'attachment'
						AND ( NOT EXISTS (
							SELECT * FROM $wpdb->postmeta pm
							WHERE pm.meta_key = 'mdd_hash'
							AND pm.post_id = p.ID
						) OR NOT EXISTS (
							SELECT * FROM $wpdb->postmeta pm2
							WHERE pm2.meta_key = 'mdd_size'
							AND pm2.post_id = p.ID
						) );";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Retrieves an array of post ids that have duplicate hashes.
	 */
	private function get_duplicate_ids() {

		global $wpdb;

		$duplicate_ids = get_transient( 'mdd_duplicate_ids' );

		if ( false === $duplicate_ids ) {
			$sql = "SELECT DISTINCT p.post_id
				FROM $wpdb->postmeta AS p
				JOIN (
					SELECT count(*) AS dupe_count, meta_value
					FROM $wpdb->postmeta
					WHERE meta_key = 'mdd_hash'
					AND meta_value != '" . self::NOT_FOUND_HASH . "'
					GROUP BY meta_value
					HAVING dupe_count > 1
				) AS p2
				ON p.meta_value = p2.meta_value;";

			$duplicate_ids = $wpdb->get_col( $sql );
			// we do this otherwise WP_Query's post__in gets an empty array and returns all posts
			if ( ! count( $duplicate_ids ) ) {
				$duplicate_ids = array( '0' );
			}
			set_transient( 'mdd_duplicate_ids', $duplicate_ids );
		}

		$this->duplicate_ids = $duplicate_ids;
		return $this->duplicate_ids;

	}

	/**
	 * Retrieves an array of post ids that have duplicate filenames/paths.
	 */
	private function get_shared_filename_ids() {

		global $wpdb;

		$sharedfile_ids = get_transient( 'mdd_sharedfile_ids' );

		if ( false === $sharedfile_ids ) {
			$sql = "SELECT DISTINCT p.post_id
				FROM $wpdb->postmeta AS p
				JOIN (
					SELECT count(*) AS sharedfile_count, meta_value
					FROM $wpdb->postmeta
					WHERE meta_key = '_wp_attached_file'
					GROUP BY meta_value
					HAVING sharedfile_count > 1
				) AS p2
				ON p.meta_value = p2.meta_value;";

			$sharedfile_ids = $wpdb->get_col( $sql );
			// we do this otherwise WP_Query's post__in gets an empty array and returns all posts
			if ( ! count( $sharedfile_ids ) ) {
				$sharedfile_ids = array( '0' );
			}
			set_transient( 'mdd_sharedfile_ids', $sharedfile_ids );
		}

		$this->shared_filename_ids = $sharedfile_ids;
		return $this->shared_filename_ids;

	}

	/**
	 * Clears out cached IDs and counts.
	 */
	private static function delete_transients() {
		delete_transient( 'mdd_duplicate_ids' ); // Attachments that share hashes.
		delete_transient( 'mdd_sharedfile_ids' ); // Attachments that share files.
		delete_transient( 'mdd_count_all' ); // All attachments, period.
		delete_transient( 'mdd_count_indexed' ); // All attachments with known hashes and sizes.
	}

	/**
	 * Add the 'smartdelete' bulk action to the Manage Duplicates screen.
	 */
	public function get_bulk_actions( $actions ) {

		$screen = get_current_screen();

		if ( 'media_page_media-deduper' === $screen->base ) {
			$actions['smartdelete'] = __( 'Delete Preserving Featured', 'media-deduper' );
		}

		return $actions;
	}

	/**
	 * Process a bulk action performed on the media table.
	 */
	public function handle_bulk_actions( $redirect_url, $doaction, $items ) {

		// If the 'delete preserving featured' bulk action is triggered.
		if ( 'smartdelete' === $doaction ) {

			if ( $items ) {
				// Loop over the array of record IDs and delete them.
				foreach ( $items as $id ) {
					self::smart_delete_media( $id );
				}
			}

			// Redirect before upload.php can.
			$redirect_url = add_query_arg( array(
				'page' => 'media-deduper',
				'smartdeleted' => $this->smart_deleted_count . ',' . $this->smart_skipped_count,
			), $redirect_url );
		}

		return $redirect_url;
	}

	/**
	 * Handle bulk actions for pre-4.7 versions of WordPress, which don't have
	 * the handle_bulk_actions-{$screen_id} filter hook.
	 */
	public function compat_bulk_actions() {

		// Make sure we came from the deduper page. If we aren't, don't get involved.
		if ( ! isset( $_REQUEST['_wp_http_referer'] ) || strpos( $_REQUEST['_wp_http_referer'], 'media-deduper' ) === false ) {
			return;
		}

		// Handle the 'smartdelete' action.
		if ( 'smartdelete' === $_REQUEST['action'] || 'smartdelete' === $_REQUEST['action2'] ) {

			// Sanitize the list of post IDs to operate on.
			$post_ids = array_map( 'intval', $_REQUEST['media'] );

			// Get a sane default redirect URL.
			$redirect_url = add_query_arg( array(
				'page' => 'media-deduper',
			), 'upload.php' );

			// Call handle_bulk_actions() as if it were being called by upload.php in
			// WP 4.7 or higher.
			wp_redirect( $this->handle_bulk_actions( $redirect_url, 'smartdelete', $post_ids ) );
			exit;
		}

		// Let upload.php handle the 'delete' action (in 4.7 and up, the delete
		// action doesn't trigger the handle_bulk_actions-upload hook).
	}

	/**
	 * Handle filtering changes on the Manage Duplicates screen.
	 *
	 * The form for setting filtering options submits to upload.php. This
	 * function is attached to the admin_action_-1 hook, so it will only fire
	 * when the 'action' query arg is -1. When that's the case, and we're on
	 * upload.php, check for the 'show_shared' query arg, and redirect to the
	 * Manage Duplicates screen if it's present.
	 */
	public function filter_redirect() {

		$screen = get_current_screen();

		if ( 'upload' === $screen->id && isset( $_REQUEST['show_shared'] ) ) {

			$args = array(
				'page' => 'media-deduper',
			);

			// If ?show_shared is non-zero, add it to the query string for the
			// redirect URL. If it's zero, the show_shared arg will be omitted.
			if ( $show_shared = absint( $_REQUEST['show_shared'] ) ) {
				$args['show_shared'] = $show_shared;
			}

			// Redirect before upload.php can!
			wp_redirect( add_query_arg( $args, 'upload.php' ) );
			exit;
		}
	}

	/**
	 * Declare the 'smartdeleted' query arg to be 'removable'.
	 *
	 * This causes users who visit upload.php?page=media-deduper&smartdeleted=1,0
	 * (which is where you're sent after 'smart-deleting' images) to only see
	 * upload.phpp?page=media-deduper in their URL bar.
	 */
	public function removable_query_args( $args ) {
		$args[] = 'smartdeleted';
		return $args;
	}

	/**
	 * 'Smart-delete' an attachment post.
	 *
	 * See if in postmeta as a featured image
	 * If not, just delete
	 * If so, then...
	 * - find another media item with a matching hash
	 * - delete this item
	 * - reassign the featured image id to the other media item
	 * - if no matching hash was found, don't delete this
	 * - potentially increment $this->smart_deleted_count
	 */
	protected function smart_delete_media( $id ) {

		// Get any posts this media item is featured on.
		$featured_on_posts = new WP_Query( array(
			'posts_per_page'      => 99999, // Because truly killing pagination isn't allowed on VIP.
			'ignore_sticky_posts' => true,
			'post_type'           => 'any',
			'meta_key'            => '_thumbnail_id',
			'meta_value'          => $id,
		));

		if ( ! $featured_on_posts->have_posts() ) {
			// If not featured anywhere, delete the item. Easy!
			if ( wp_delete_attachment( $id ) ) {
				$this->smart_deleted_count++;
			}
		} else {
			// This is featured somewhere. Let's see if there are any copies of this image.
			$this_post_hash = get_post_meta( $id, 'mdd_hash', true );
			if ( ! $this_post_hash ) {
				die( 'Something has gone horribly awry' );
			}
			$duplicate_media = new WP_Query( array(
				'ignore_sticky_posts' => true,
				'post__not_in'        => array( $id ),
				'post_type'           => 'attachment',
				'post_status'         => 'any',
				'orderby'             => 'ID',
				'order'               => 'ASC',
				'meta_key'            => 'mdd_hash',
				'meta_value'          => $this_post_hash,
			));

			// If no other media with this hash was found, don't delete this media item.
			// You'd think this would never happen, since we're deleting *duplicates*,
			// but after the first duplicate in a matching pair is deleted, the second one
			// will actually be unique.
			if ( ! $duplicate_media->have_posts() ) {
				$this->smart_skipped_count++;
			} else {
				// Get the id of the first matching upload.
				$preserved_id = $duplicate_media->posts[0]->ID;
				// Update each of the posts our current media image is featured on to use the $preserved_id.
				foreach ( $featured_on_posts->posts as $post ) {
					update_post_meta( $post->ID, '_thumbnail_id', $preserved_id, $id );
				}

				// Now delete our media and increment.
				if ( wp_delete_attachment( $id ) ) {
					$this->smart_deleted_count++;
				}
			}
		}//end if
	}

	/**
	 * Filters the media columns to add another one for filesize.
	 */
	public function media_columns( $posts_columns ) {
		$posts_columns['mdd_size'] = _x( 'Size', 'column name', 'media-deduper' );
		return $posts_columns;
	}

	/**
	 * Filters the media columns to make the Size column sortable.
	 */
	public function media_sortable_columns( $sortable_columns ) {
		$sortable_columns['mdd_size'] = array( 'mdd_size', true );
		return $sortable_columns;
	}

	/**
	 * Handles the file size column output.
	 */
	public function media_custom_column( $column_name, $post_id ) {
		if ( 'mdd_size' === $column_name ) {
			$filesize = get_post_meta( $post_id, 'mdd_size', true );
			if ( ! $filesize ) {
				echo esc_html__( 'Unknown', 'media-deduper' );
			} else {
				echo esc_html( size_format( $filesize ) );
			}
		}
	}

	/**
	 * Add meta query clauses corresponding to custom 'orderby' values.
	 */
	public function pre_get_posts( $query ) {

		if ( 'mdd_size' === $query->get( 'orderby' ) ) {

			// Get the current meta query.
			$meta_query = $query->get( 'meta_query' );
			if ( ! $meta_query ) {
				$meta_query = array();
			}

			// Add a clause to sort by.
			$meta_query['mdd_size'] = array(
				'key'     => 'mdd_size',
				'type'    => 'NUMERIC',
				'compare' => 'EXISTS',
			);

			// Set the new meta query.
			$query->set( 'meta_query', $meta_query );
		}
	}
}


/**
 * Start up this plugin.
 */
function media_deduper_init() {
	global $MediaDeduper;
	$MediaDeduper = new Media_Deduper();
}
add_action( 'init', 'media_deduper_init' );
