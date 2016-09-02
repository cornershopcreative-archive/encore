<?php
/*
Plugin Name: Media Deduper
Version: 0.9.3
Description: Save disk space and bring some order to the chaos of your media library by removing and preventing duplicate files.
Plugin URI: https://cornershopcreative.com/
Author: Cornershop Creative
Author URI: https://cornershopcreative.com/

	Still to do:
		- make it smart enough to update attachment references to the original when deleting
		- better identification/handling of not-found and other error-producing attachments
		- dashboard panel?
		- PHPDoc commenting
		- indexing via cron for large libraries
		- screen options
		- help text
*/

class Media_Deduper {

	const NOT_FOUND_HASH = 'not-found';

	function __construct() {
		// use an existing capabilty to check for privileges. manage_options may not be ideal, but gotta use something...
		$this->capability = apply_filters( 'media_deduper_cap', 'manage_options' );

		add_action( 'wp_ajax_calc_media_hash', array( $this, 'ajax_calc_media_hash' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );

		add_filter( 'wp_handle_upload_prefilter', array( $this, 'block_duplicate_uploads' ) );
		add_action( 'add_attachment', array( $this, 'upload_calc_media_hash' ) );
		add_action( 'edit_attachment', array( $this, 'upload_calc_media_hash' ) );

		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// class for handling outputting the duplicates
		require_once( dirname( __FILE__ ) . '/class-mdd-media-list-table.php' );

	}

	/**
	 * Remind people they need to do things
	 */
	public function admin_notices() {

		$screen = get_current_screen();
		$html = '';

		// on plugin activation, point to the indexing page
		if ( ! get_option( 'mdd-activated', false ) ) {
			add_option( 'mdd-activated', true, '', 'no' );
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf( __( 'In order to manage duplicate media you must first <strong><a href="%s">generate the media index</a></strong>.', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';
			// also add the meta_value index
			$this->db_index( 'add' );
		}

		// otherwise, complain about incomplete indexing
		else if ( 'upload' === $screen->base && $this->get_count( 'indexed' ) < $this->get_count() ) {
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf( __( 'Media duplication index is not comprehensive, please <strong><a href="%s">update the index now</a></strong>.', 'media-deduper' ), admin_url( 'upload.php?page=media-deduper' ) );
			$html .= '</p></div>';
		}

		echo $html;
	} // end plugin_activation

	/**
	 * Adds/removes DB index on meta_value to facilitate performance in finding dupes
	 */
	private function db_index( $task = 'add' ) {

		global $wpdb;
		if ( 'add' === $task ) {
			$sql = "CREATE INDEX `mdd_hash_index` ON $wpdb->postmeta ( meta_value(32) );";
		} else {
			$sql = "DROP INDEX `mdd_hash_index` ON $wpdb->postmeta;";
		}

		$wpdb->query( $sql );

	}

	/**
	 * On deactivation, get rid of our junk
	 */
	public function deactivate() {

		global $wpdb;
		// kill our mdd_hashes? don't want to pollute the DB, but annoying to re-generate the index...
		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'mdd_hash' ) );

		// kill our index
		$this->db_index( 'remove' );

		// remove the option indicating activation
		delete_option( 'mdd-activated' );

	}

	/**
	 * prevent duplicates from being uploaded
	 */
	function block_duplicate_uploads( $file ) {

		global $wpdb;

		$upload_hash = md5_file( $file['tmp_name'] );

		//does our hash match?
		$sql = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'mdd_hash' AND meta_value = %s LIMIT 1;", $upload_hash );
		$matches = $wpdb->get_var( $sql );
		if ( $matches ) {
			// @todo find a way to actually include HTML so we can use get_edit_post_link()
			$file['error'] = sprintf( __( 'It appears this file is already present in your media library as post %u!', 'media-deduper' ), $matches );
		}
		return $file;
	}

	/**
	 * calculate the hash for a just-uploaded file
	 */
	function upload_calc_media_hash( $post_id ) {
		$mediafile = get_attached_file( $post_id );

		$hash = $this->calculate_hash( $mediafile );

		return $this->save_media_hash( $post_id, $hash );
	}

	// this is where we compute the hash for a given attachment and store it in the DB
	function ajax_calc_media_hash() {

		@error_reporting( 0 ); // Don't break the JSON result

		$id = (int) $_POST['id'];
		$image = get_post( $id );

		if ( ! $image || 'attachment' != $image->post_type ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'Failed hash: %s is an invalid attachment ID.', 'media-deduper' ), esc_html( $_POST['id'] ) ) ) );
		}

		if ( ! current_user_can( $this->capability ) ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'You lack permissions to do this.', 'media-deduper' ) ) ) );
		}

		$mediafile = get_attached_file( $image->ID );

		if ( false === $mediafile || ! file_exists( $mediafile ) ) {
			$this->save_media_hash( $id, self::NOT_FOUND_HASH );
			wp_send_json_error( array(
				'error' => sprintf( __( 'Attachment file for <a href="%s">%s</a> could not be found.', 'media-deduper' ), get_edit_post_link( $id ), get_the_title( $id ) ),
				'post_id' => $id,
			) );
		}

		// @todo actually save fails so that media don't get this reattempted repeatedly
		$hash = $this->calculate_hash( $mediafile );

		$processed = $this->save_media_hash( $image->ID, $hash );

		if ( ! $processed ) {
			wp_send_json_error( array( 'error' => sprintf( __( 'Hash for attachment %s could not be saved', 'media-deduper' ), esc_html( $image->ID ) ) ) );
		}

		wp_send_json_success( array( 'message' => sprintf( __( 'Hash for attachment %s saved.', 'media-deduper' ), esc_html( $image->ID ) ) ) );

	}

	private function calculate_hash( $file ) {
		return md5_file( $file );
	}

	private function save_media_hash( $post_id, $hash_value ) {
		return update_post_meta( $post_id, 'mdd_hash', $hash_value );
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
					WHERE pm.meta_key = 'mdd_hash'
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
	 * Add to admin menu
	 */
	function add_admin_menu() {
		$hook = add_media_page( __( 'Manage Duplicates', 'media-deduper' ), __( 'Manage Duplicates', 'media-deduper' ), $this->capability, 'media-deduper', array( $this, 'admin_screen' ) );

		add_action( 'load-' . $hook, array( $this, 'screen_options' ) );
	}

	/**
	 * Implements screen options
	 */
	function screen_options() {
		$option = 'per_page';
		$args = array(
			'label'   => 'Items',
			'default' => get_option( 'posts_per_page', 10 ),
			'option'  => 'mdd_per_page',
		);
		add_screen_option( $option, $args );
	}

	/**
	 * Saves screen options
	 */
	function save_screen_options( $status, $option, $value ) {
		return $value; // is that it?
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
				// Capability check
				if ( ! current_user_can( $this->capability ) ) {
					wp_die( __( 'Cheatin&#8217; eh?', 'media-deduper' ) ); }

				// Form nonce check
				check_admin_referer( 'media-deduper-index' );

				// build the whole index-generator-progress-bar-ajax-thing
				$this->process_index_screen();

			} else {
			?>

			<p><?php _e( 'Use this tool to identify duplicate media files in your WordPress instance. It only looks at the files themselves, not any data in WordPress (such as title, caption or comments).', 'media-deduper' ); ?></p>
			<p><?php _e( 'In order to identify duplicate files, and index of all media must first be generated.', 'media-deduper' ); ?></p>

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
			<form id="posts-filter" method="get">
				<?php

				$this->get_duplicate_ids();

				// we use $wp_query (the main query) since Media_List_Table does and we extend that
				global $wp_query;
				$wp_query = new WP_Query( array(
					'post__in'    => $this->duplicate_ids,
					'post_type'   => 'attachment',
					'post_status' => 'inherit',
					'posts_per_page' => get_user_option( 'mdd_per_page' ),
					'paged'       => isset($_GET['paged']) ? intval( $_GET['paged'] ) : 1,
				) );

				$wp_list_table = new MDD_Media_List_Table;
				$wp_list_table->prepare_items();
				$wp_list_table->display();

				?>
				</form>
			</div><?php
			} // END else no post
	}


	// Indexing progress page
	private function process_index_screen() {
		?>
		<p><?php _e( 'Please be patient while the index is generated. This can take a while if your server is slow or if you have many large media files. Do not navigate away from this page until this script is done. You will be notified when that happens.', 'media-deduper' ); ?></p>

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
		</style>

		<div id="mdd-bar">
			<div id="mdd-meter"></div>
			<div id="mdd-bar-percent"></div>
		</div>

		<p><input type="button" class="button hide-if-no-js" name="mdd-stop" id="mdd-stop" value="<?php _e( 'Abort', 'media-deduper' ) ?>" /></p>

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

			// init progressbar
			$("#mdd-bar-percent").html( "0%" );

			// listen for abort
			$("#mdd-stop").on('click', function() {
				mdd_active = false;
				$(this).val("<?php esc_attr_e( 'Stopping...', 'media-deduper' ); ?>");
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

				// @todo: i18n of these strings
				if ( mdd_bad > 0 ) {
					$("#message").html("<p><?php _e( 'Media indexing complete;','media-deduper' ); ?> <strong>"+ mdd_bad +" <?php _e( 'files could not be indexed.', 'media-deduper' ); ?></strong></p>");
				} else {
					$("#message").html("<p><?php _e( 'Media indexing complete; <strong>All media successfully indexed.</strong>', 'media-deduper' ); ?></p>");
				}

				$("#message").show();
			}

			// Index an attachment image via AJAX
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
	 * retrieves a list of attachment posts that haven't yet had their file md5 hashes computed
	 */
	private function get_unhashed_ids() {

		global $wpdb;

		$sql = "SELECT ID FROM $wpdb->posts p
						WHERE p.post_type = 'attachment'
						AND NOT EXISTS (
							SELECT * FROM $wpdb->postmeta pm
							WHERE pm.meta_key = 'mdd_hash'
							AND pm.post_id = p.ID
						);";

		return $wpdb->get_col( $sql );

	}

	/**
	 * retrieves an array of post ids that have duplicate hashes
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

}


// Start up this plugin
add_action( 'init', 'media_deduper_init' );
function media_deduper_init() {
	global $MediaDeduper;
	$MediaDeduper = new Media_Deduper();
}