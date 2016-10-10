<?php
/*
Plugin Name: Media Deduper
Version: 1.0.3
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

class Media_Deduper {

	const NOT_FOUND_HASH = 'not-found';
	const NOT_FOUND_SIZE = 0;
	const VERSION = '1.0.0';

	protected $smart_deleted_count = 0;
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

		Media_Deduper::db_index( 'add' );
	}

	function __construct() {
		// Use an existing capabilty to check for privileges. manage_options may not be ideal, but gotta use something...
		$this->capability = apply_filters( 'media_deduper_cap', 'manage_options' );

		add_action( 'wp_ajax_calc_media_hash', array( $this, 'ajax_calc_media_hash' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 11 );
		add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );

		add_filter( 'wp_handle_upload_prefilter', array( $this, 'block_duplicate_uploads' ) );
		add_action( 'add_attachment', array( $this, 'upload_calc_media_meta' ) );
		add_action( 'edit_attachment', array( $this, 'upload_calc_media_meta' ) );

		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Class for handling outputting the duplicates.
		require_once( dirname( __FILE__ ) . '/class-mdd-media-list-table.php' );

		// Bulk action listeners.
		add_action( 'admin_action_smartdelete', array( $this, 'process_bulk_action' ) ); // Top drowndown.
		add_action( 'admin_action_-1', array( $this, 'process_bulk_action' ) ); // Bottom dropdown.

		// Column handlers.
		add_filter( 'manage_media_columns', array( $this, 'list_columns' ) );
	}

	/**
	 * Enqueue the media js file from core.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		$screen = get_current_screen();
		if ( 'media_page_media-deduper' === $screen->base ) {
			wp_enqueue_media();
			wp_enqueue_script( 'media-grid' );
			wp_enqueue_script( 'media' );
		}
	}

	/**
	 * Remind people they need to do things.
	 */
	public function admin_notices() {

		$screen = get_current_screen();
		$html = '';

		// Update was just performed, not initial activation.
		if ( get_option( 'mdd-updated', false ) ) {
			$html = '<div class="updated notice is-dismissible"><p>';
			$html .= sprintf( __( 'Thanks for updating Media Deduper. Due to recent enhancements you’ll need to <a href="%s">regenerate the index</a>. Sorry for the inconvenience!', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';
			delete_option( 'mdd-updated' );
		} // On initial plugin activation, point to the indexing page.
		else if ( ! get_option( 'mdd-activated', false ) && $this->get_count( 'indexed' ) < $this->get_count() ) {
			add_option( 'mdd-activated', true, '', 'no' );
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf( __( 'In order to manage duplicate media you must first <strong><a href="%s">generate the media index</a></strong>.', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';
		} // Otherwise, complain about incomplete indexing if necessary.
		else if ( 'upload' === $screen->base && $this->get_count( 'indexed' ) < $this->get_count() ) {
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf( __( 'Media duplication index is not comprehensive, please <strong><a href="%s">update the index now</a></strong>.', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';
		} // Message about smart deletion status.
		else if ( isset( $_GET['smartdeleted'] ) ) {
			list( $deleted, $skipped ) = explode( ',', $_GET['smartdeleted'] );
			$html = '<div class="updated"><p>';
			$html .= sprintf( __( 'Deleted %s items and skipped %d items', 'media-deduper' ), $deleted, $skipped );
			$html .= '</p></div>';
		}
		echo $html;
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

		// Kill our mdd_hashes. It's annoying to re-generate the index but we don't want to pollute the DB.
		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'mdd_hash' ) );

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
		return $this->save_media_meta( $post_id, $hash );
	}

	/**
	 * This is where we compute the hash for a given attachment and store it in the DB.
	 */
	function ajax_calc_media_hash() {

		@error_reporting( 0 ); // Don't break the JSON result.

		$id = (int) $_POST['id'];
		$image = get_post( $id );

		if ( ! $image || 'attachment' !== $image->post_type ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'Failed hash: %s is an invalid attachment ID.', 'media-deduper' ), esc_html( $_POST['id'] ) ) ) );
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

		wp_send_json_success( array( 'message' => sprintf( __( 'Hash and size for attachment %s saved.', 'media-deduper' ), esc_html( $image->ID ) ) ) );

	}

	private function calculate_size( $file ) {
		return filesize( $file );
	}

	private function calculate_hash( $file ) {
		return md5_file( $file );
	}

	private function save_media_meta( $post_id, $value, $meta_key = 'mdd_hash' ) {
		return update_post_meta( $post_id, $meta_key, $value );
	}

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

		$result = wp_cache_get( 'mdd_count_' . $type );
		if ( false === $result ) {
			wp_cache_set( 'mdd_count_' . $type, $wpdb->get_var( $sql ) );
			$result = $wpdb->get_var( $sql );
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
			'id'		=> 'overview',
			'title'		=> __( 'Overview' ),
			'content'	=>
			'<p>' . __( 'Media Deduper was built to help you find and eliminate duplicate images and attachments from your WordPress media library.' ) . '</p>' .
				'<p>' . __( 'Before Media Deduper can identify duplicate assets, it first must build an index of all the files in your media library, which can take some time.' ) . '</p>' .
				'<p>' . __( 'Once its index is complete, Media Deduper will also prevent users from uploading duplicates of files already present in your media library.' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'		=> 'indexing',
			'title'		=> __( 'Indexing' ),
			'content'	=>
			'<p>' . __( 'Media Deduper needs to generate an index of your media files in order to determine which files match. It only looks at the files themselves, not any data in WordPress (such as title, caption or comments). Once that’s done, however, Media Deduper automatically adds new uploads to its index, so you shouldn’t have to generate the index again.' ) . '</p>' .
				'<p>' . __( 'As a part of the indexing process, Media Deduper also stores information about each file’s size so duplicates can be sorted by disk space used, allow you to most efficiently perform cleanup.' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'		=> 'deletion',
			'title'		=> __( 'Deletion' ),
			'content'	=>
			'<p>' . __( 'Once Media Deduper has indexed your files and found duplicates, you can easily delete them in one of two ways:' ) . '</p>' .
				'<p>' . __( 'Option 1: Delete Permanently. This option <em>permanently</em> deletes whichever files you select. This can be <em>very dangerous</em> as it cannot be undone, and you may inadvertently delete all versions of a file, regardless of how they are being used on the site.' ) . '</p>' .
				'<p>' . __( 'Option 2: Delete Preserving Featured. This option preserves images that are assigned as Featured Images on posts. Deduper reassigns a single instance of the image to the post, and only deletes orphaned copies of that image. <em><strong>Please note:</strong></em> Although this option preserves Featured Images, it does <em>not</em> preserve media used in galleries, other shortcodes, custom fields, the bodies of posts, or other meta data. Please be careful.' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'		=> 'about',
			'title'		=> __( 'About' ),
			'content'	=>
			'<p>' . __( 'Media Deduper was built by Cornershop Creative, on the web at <a href="https://cornershopcreative.com">https://cornershopcreative.com</a>' ) . '</p>' .
				'<p>' . __( 'Need support? Got a feature idea? <a href="https://wordpress.org/support/plugin/media-deduper">Contact us on the wordpress.org plugin support page</a>. Thanks!' ) . '</p>',
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
			<h1><?php _e( 'Media Deduper', 'media-deduper' ); ?></h1>
			<?php

			if ( ! empty( $_POST['mdd-build-index'] ) ) {
				// Capability check.
				if ( ! current_user_can( $this->capability ) ) {
					wp_die( __( 'Cheatin&#8217; eh?', 'media-deduper' ) ); }

				// Form nonce check.
				check_admin_referer( 'media-deduper-index' );

				// Build the whole index-generator-progress-bar-ajax-thing.
				$this->process_index_screen();

			} else {
			?>

			<p><?php _e( 'Use this tool to identify duplicate media files in your site. It only looks at the files themselves, not any data in WordPress (such as title, caption or comments).', 'media-deduper' ); ?></p>
			<p><?php _e( 'In order to identify duplicate files, an index of all media must first be generated.', 'media-deduper' ); ?></p>

			<?php if ( $this->get_count( 'indexed' ) < $this->get_count() ) : ?>
				<p><?php echo sprintf( __( 'Looks like %u of %u media items have been indexed. <strong>Please index all media now.</strong>', 'media-deduper' ), $this->get_count( 'indexed' ), $this->get_count() ); ?></p>

				<form method="post" action="">
					<?php wp_nonce_field( 'media-deduper-index' ); ?>
					<p><input type="submit" class="button hide-if-no-js" name="mdd-build-index" id="mdd-build-index" value="<?php _e( 'Index Media', 'media-deduper' ) ?>" /></p>

					<noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!', 'media-deduper' ) ?></em></p></noscript>
				</form><br>

			<?php else : ?>
				<p><?php _e( 'All media have been indexed.', 'media-deduper' ); ?></p>
			<?php endif; ?>

			<!-- the posts table -->
			<h2><?php _e( 'Duplicate Media Files', 'media-deduper' ); ?></h2>
			<style>
				.column-mdd_size {
					width: 6em;
				}
			</style>
			<form id="posts-filter" method="get">

				<?php

				$this->get_duplicate_ids();

				// We use $wp_query (the main query) since Media_List_Table does and we extend that.
				global $wp_query;
				$query_parameters = array(
					'post__in'       => $this->duplicate_ids,
					'post_type'      => 'attachment',
					'post_status'    => 'inherit',
					'posts_per_page' => get_user_option( 'mdd_per_page' ),
					'paged'          => isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1,
				);

				// If sorting by size, handle that.
				if ( 'mdd_size' === $_GET['orderby'] ) {
					$query_parameters['meta_key'] = 'mdd_size';
					$query_parameters['orderby'] = 'meta_value_num';
					$query_parameters['order'] = 'DESC';
					if ( 'asc' === $_GET['order'] ) {
						$query_parameters['order'] = 'ASC';
					}
				}

				$wp_query = new WP_Query( $query_parameters );

				$wp_list_table = new MDD_Media_List_Table;
				$wp_list_table->prepare_items();
				$wp_list_table->display();

				// This stuff makes the 'Attach' dialog work.
				wp_nonce_field( 'find-posts', '_ajax_nonce', false );
				?><input type="hidden" id="find-posts-input" name="ps" value="" /><div id="ajax-response"></div>
				<?php find_posts_div(); ?>
				</form>
			</div><?php
			} // END else no post.
	}


	// Indexing progress page.
	private function process_index_screen() {
		?>
		<p><?php _e( 'Please be patient while the index is generated. This can take a while, particularly if your server is slow or if you have many large media files. Do not navigate away from this page until this script is done.', 'media-deduper' ); ?></p>

		<noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!', 'media-deduper' ) ?></em></p></noscript>

		<style>
			#mdd-bar {
				position: relative;
				height: 30px;
				background-color: white;
				background-color: rgba(255,255,255,0.9);
				max-width: 800px;
				margin: 5px auto;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
				box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
			}
			#mdd-bar-percent {
				position: absolute;
				left: 50%;
				top: 50%;
				width: 300px;
				margin-left: -150px;
				height: 30px;
				margin-top: -10px;
				text-align: center;
				font-weight: bold;
				text-shadow: 0 1px 1px rgba(255,255,255,0.3);
			}
			#mdd-meter {
				height: 30px;
				background-color: #0073aa;	/* fresh */
				width: 0%;
				display: inline-block;
				position: relative;
			}
			.admin-color-light #mdd-meter {
				background-color: #888;
			}
			.admin-color-blue #mdd-meter {
				background-color: #096484;
			}
			.admin-color-coffee #mdd-meter {
				background-color: #c7a589;
			}
			.admin-color-ectoplasm #mdd-meter {
				background-color: #a3b745;
			}
			.admin-color-midnight #mdd-meter {
				background-color: #e14d43;
			}
			.admin-color-ocean #mdd-meter {
				background-color: #9ebaa0;
			}
			.admin-color-sunrise #mdd-meter {
				background-color: #dd823b;
			}
			#mdd-meter:before {
				background: -moz-linear-gradient(top,  rgba(255,255,255,0.35) 0%, rgba(255,255,255,0) 60%); /* FF3.6+ */
				background: -webkit-linear-gradient(top,  rgba(255,255,255,0.35) 0%, rgba(255,255,255,0) 60%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top,  rgba(255,255,255,0.35) 0%, rgba(255,255,255,0) 60%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top,  rgba(255,255,255,0.35) 0%, rgba(255,255,255,0) 60%); /* IE10+ */
				background: linear-gradient(to bottom,  rgba(255,255,255,0.35) 0%, rgba(255,255,255,0) 60%); /* W3C */
				content: "";
				position: absolute;
				top: 0;
				bottom: 0;
				left: 0;
				right: 0;
			}
			#mdd-manage {
				display: none;
			}
		</style>

		<div id="mdd-bar">
			<div id="mdd-meter"></div>
			<div id="mdd-bar-percent"></div>
		</div>

		<p>
			<input type="button" class="button hide-if-no-js" name="mdd-stop" id="mdd-stop" value="<?php _e( 'Abort', 'media-deduper' ) ?>" />
			<input type="button" class="button hide-if-no-js" name="mdd-manage" id="mdd-manage" value="<?php _e( 'Manage Duplicates Now', 'media-deduper' ) ?>" />
		</p>

		<ul class="error-files">
		</ul>

		<script>
		jQuery(document).ready(function($){
			var i,
				mdd_ids     = [<?php echo implode( ',', $this->get_unhashed_ids() ); ?>],
				mdd_total   = mdd_ids.length,
				mdd_count   = 1,
				mdd_percent = 0,
				mdd_good    = 0,
				mdd_bad     = 0,
				mdd_failed_ids = [],
				mdd_active  = true;

			// Initialize progressbar.
			$("#mdd-bar-percent").html( "0%" );

			// Listen for abort.
			$("#mdd-stop").on('click', function() {
				mdd_active = false;
				$(this).val("<?php esc_attr_e( 'Stopping...', 'media-deduper' ); ?>");
			});

			// Listen for manage.
			$("#mdd-manage").on('click', function() {
				window.location = "<?php echo admin_url( 'upload.php?page=media-deduper' ); ?>";
			});

			// Called after each resize. Updates debug information and the progress bar.
			function mdd_increment( id, success, response ) {
				mdd_percent = mdd_count / mdd_total;
				$("#mdd-bar-percent").html( (mdd_percent * 100).toFixed(1) + "%" );
				$("#mdd-meter").css( 'width', (mdd_percent * 100) + "%" );
				mdd_count++;

				if ( success ) {
					mdd_good++;
				} else {
					mdd_bad++;
					mdd_failed_ids.push( id );
				}
			}

			// Called when all images have been processed. Shows the results and cleans up.
			function mdd_results() {

				$('#mdd-stop').hide();
				$('#mdd-manage').show();

				// @todo: i18n of these strings.
				if ( mdd_bad > 0 ) {
					$("#message").html("<p><?php _e( 'Media indexing complete;','media-deduper' ); ?> <strong>"+ mdd_bad +" <?php
						printf(
							__( "files could not be indexed. <a href='%s'>Manage duplicates now.</a>", 'media-deduper' ),
						admin_url( 'upload.php?page=media-deduper' ) );
						?></strong></p>");
				} else {
					$("#message").html("<p><?php _e( 'Media indexing complete; <strong>All media successfully indexed.</strong>', 'media-deduper' ); ?></p>");
				}

				$("#message").show();
			}

			// Index an attachment image via AJAX.
			function index_media( id ) {

				request = $.post( ajaxurl, { action: "calc_media_hash", id: id }, function( response ) {
					if ( typeof response !== 'object' || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
						response = {
							success: false,
							error: "<?php _e( 'Request EPIC FAIL', 'media-deduper' ); ?>"
						};
					} else if ( typeof response === 'object' && typeof response.data.error !== 'undefined' ) {
						$('.error-files').append('<li>' + response.data.error + '</li>');
					}

					mdd_increment( id, response.success, response );

					if ( mdd_ids.length && mdd_active ) {
						index_media( mdd_ids.shift() );
					} else {
						mdd_results();
					}
				})

				.fail( function( response ) {
					mdd_increment( id, false, response );

					if ( mdd_ids.length && mdd_active ) {
						index_media( mdd_ids.shift() );
					} else {
						mdd_results();
					}
				});
			}

			index_media( mdd_ids.shift() );
		});
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

		$duplicate_ids = wp_cache_get( 'mdd_duplicate_ids' );

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

			$this->duplicate_ids = $wpdb->get_col( $sql );
			// we do this otherwise WP_Query's post__in gets an empty array and returns all posts
			if ( ! count( $this->duplicate_ids ) ) {
				$this->duplicate_ids = array( '0' );
			}
			wp_cache_set( 'mdd_duplicate_ids', $wpdb->get_col( $sql ) );
		}

		return $this->duplicate_ids;

	}

	/**
	 * Process a bulk action performed on the media table.
	 */
	public function process_bulk_action() {

		// If the delete bulk action is triggered.
		if ( ( isset( $_REQUEST['action'] ) && 'smartdelete' === $_REQUEST['action'] )
			|| ( isset( $_REQUEST['action2'] ) && 'smartdelete' === $_REQUEST['action2'] )
		) {

			$delete_ids = esc_sql( $_REQUEST['media'] );
			// Loop over the array of record IDs and delete them.
			foreach ( $delete_ids as $id ) {
				self::smart_delete_media( $id );
			}

			// Redirect before upload.php can.
			wp_redirect( add_query_arg( array(
				'page' => 'media-deduper',
				'smartdeleted' => $this->smart_deleted_count . ',' . $this->smart_skipped_count,
			), 'upload.php' ) );
			exit;
		}


		else if ( ( isset( $_REQUEST['action'] ) && 'delete' == $_REQUEST['action'] )
			|| ( isset( $_REQUEST['action2'] ) && 'delete' == $_REQUEST['action2'] )
		) {

			$post_ids = esc_sql( $_REQUEST['media'] );

			if ( !isset( $post_ids ) ) {
				$post_ids = array();
			}

			foreach ( (array) $post_ids as $post_id_del ) {
				if ( !current_user_can( 'delete_post', $post_id_del ) )
					wp_die( __( 'You are not allowed to delete this item.' ) );

				if ( !wp_delete_attachment( $post_id_del ) )
					wp_die( __( 'Error in deleting.' ) );
			}

			// Redirect before upload.php can!
			wp_redirect( add_query_arg( array(
				'page' => 'media-deduper',
				'deleted' => count( $post_ids ),
				), 'upload.php' ) );
			exit;
		}
 	}

	protected function smart_delete_media( $id ) {
		/**
			1. See if in postmeta as a featured image
			If not, just delete
			If so, then...
			- find another media item with a matching hash
			- delete this item
			- reassign the featured image id to the other media item
			- if no matching hash was found, don't delete this
			- potentially increment $this->smart_deleted_count
		 */

		// Get any posts this media item is featured on.
		$featured_on_posts = new WP_Query( array(
			'posts_per_page'      => 99999,	// Because truly killing pagination isn't allowed on VIP.
			'ignore_sticky_posts' => true,
			'post_type'           => 'any',
			'meta_key'            => '_thumbnail_id',
			'meta_value'          => $id,
		));

		// If not featured anywhere, delete the item. Easy!
		if ( ! $featured_on_posts->have_posts() ) {
			if ( wp_delete_attachment( $id ) ) {
				$this->smart_deleted_count++;
			}
		} // This is featured somewhere. Let's see if there are any copies of this image.
		else {
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

			/** If no other media with this hash was found, don't delete this media item.
			 *  You'd think this would never happen, since we're deleting *duplicates*,
			 *  but after the first duplicate in a matching pair is deleted, the second one
			 *  will actually be unique.
			 */
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
		}
	}

	/**
	 * Filters the media columns to add another one for filesize.
	 */
	public function list_columns( $posts_columns ) {
		$posts_columns['mdd_size'] = _x( 'Size', 'column name' );
		return $posts_columns;
	}
}


// Start up this plugin.
add_action( 'init', 'media_deduper_init' );
function media_deduper_init() {
	global $MediaDeduper;
	$MediaDeduper = new Media_Deduper();
}
