<?php
/*
 * Plugin Name: Encore.org Story/Purpose Prize Importer
 * Description: Import data as stories/purpose prizes from a CSV file. Based on the script by Denis Kobozev
 * Version: 1.0.0
 * Author: Computer Courage
 * Author URI: http://www.computercourage.com
*/

class CSVImporterPlugin {
    	
	function CSVImporterPlugin() {
		add_action( 'wp_ajax_encore_import_stories', array( &$this, 'import_stories_ajax' ) );
		add_action( 'wp_ajax_encore_import_cleanup', array( &$this, 'import_cleanup_ajax' ) );
		add_action( 'admin_menu', array( &$this, 'csv_admin_menu') );
	}
	
	function csv_admin_menu() {
		$this->menu_id = add_management_page( 'Story Importer', 'Story Importer', 'manage_options', 'story-importer', array(&$this, 'form') );
	}

    /**
     * Plugin's interface
     *
     * @return void
     */
    function form() {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->post();
        }
		
		$upload_size_unit = $max_upload_size = wp_max_upload_size();
		$sizes = array( 'KB', 'MB', 'GB' );
	
		for ( $u = -1; $upload_size_unit > 1024 && $u < count( $sizes ) - 1; $u++ ) {
			$upload_size_unit /= 1024;
		}
	
		if ( $u < 0 ) {
			$upload_size_unit = 0;
			$u = 0;
		} else {
			$upload_size_unit = (int) $upload_size_unit;
		}

        // form HTML {{{
?>

<div class="wrap">

    <h2><?php _e( 'Import CSV' ); ?></h2>

    <p><strong>Before importing, please be sure that any images to be imported are valid URLs (ex. http://www.encore.org/files/image.jpg).</strong></p>
    
    <noscript><p><em><?php _e( 'You must enable Javascript in order to proceed!' ) ?></em></p></noscript>

    <form method="post" enctype="multipart/form-data">
        <p>
			<label for="status"><?php _e( 'Status:' ); ?></label>
			<select name="status">
				<option value="publish"><?php _e( 'Publish' ); ?></option>
				<option value="draft"><?php _e( 'Draft' ); ?></option>
			</select>
		</p>

        <p>
			<label for="type"><?php _e( 'Type:' ); ?></label>
			<select name="type">
				<option value="story"><?php _e( 'Story' ); ?></option>
				<option value="purpose_prize"><?php _e( 'Purpose Prize' ); ?></option>
			</select>
		</p>

        <p>
        	<label for="csv_import"><?php _e( 'Upload file:' ); ?></label><br/>
            <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" />
            <span class="max-upload-size"><?php printf( __( 'Maximum upload file size: %d%s.' ), esc_html($upload_size_unit), esc_html($sizes[$u]) ); ?></span>
        </p>

        <p class="submit">
        	<input type="submit" class="button" name="submit" value="<?php _e( 'Import' ); ?>" />
        	<?php wp_nonce_field( 'import-stories', 'import_nonce' ); ?>
       	</p>
    </form>
</div>

<?php
        // end form HTML }}}

    }

    function print_messages() {
        if (!empty($this->log)) {

        // messages HTML {{{
?>

<div class="wrap">
    <?php if (!empty($this->log['error'])): ?>

    <div class="error">

        <?php foreach ($this->log['error'] as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>

    <?php if (!empty($this->log['notice'])): ?>

    <div class="updated fade">

        <?php foreach ($this->log['notice'] as $notice): ?>
            <p><?php echo $notice; ?></p>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>
</div><!-- end wrap -->

<?php
        // end messages HTML }}}

            $this->log = array();
        }
    }

    /**
     * Handle POST submission
     *
     * @return void
     */
    function post() {
    	if( !isset($_POST['import_nonce']) || !wp_verify_nonce( $_POST['import_nonce'], 'import-stories' ) ) {
            $this->log['error'][] = 'Verification error occurred, please go back and try again.';
            $this->print_messages();
            return;
    	}

		ignore_user_abort(1);
        if (empty($_FILES['csv_import']['tmp_name'])) {
            $this->log['error'][] = 'No file uploaded, aborting.';
            $this->print_messages();
            return;
        }
		
		$upload_dir = wp_upload_dir();
		$path = $upload_dir['basedir'];

		$csv_filename = wp_unique_filename($path, 'story_import.csv');
		$csv_file = $path . "/" . $csv_filename;
		if( !move_uploaded_file($_FILES["csv_import"]["tmp_name"], $csv_file) ) {
			$this->log['error'][] = 'File could not be uploaded, aborting.';
			$this->print_messages();
			return;
		}
		
		global $wpdb;
		
		// get the total line count of the file
		$linecount = 0;
		$handle = fopen($csv_file, "r");
		while(!feof($handle)){
		  $line = fgetcsv($handle);
		  $linecount++;
		}
		$linecount--; // to account for header
		fclose($handle);
?>
		<h1><?php _e( 'Importing Stories' ); ?></h1>
                
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				var statusBox = $('#import_status'),
					importFile = '<?php echo $csv_file; ?>',
					status = '<?php echo $_POST['status']; ?>',
					type = '<?php echo $_POST['type']; ?>';
								
				function loadCSV(currentLine) {				
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: { action: "encore_import_stories", file: importFile, currentLine: currentLine, postStatus: status, postType: type },
						success: function( response ) {
							response = jQuery.parseJSON(response);
							if ( response.success ) {
								statusBox.append('<p>' + response.success + '</p>');
								
								if( response.currentLine < <?php echo $linecount; ?> )
									loadCSV(response.currentLine);
								else {
									$.ajax({
										type: 'POST',
										url: ajaxurl,
										data: { action: 'encore_import_cleanup', file: importFile },
									});

									statusBox.append('<p><strong><?php _e( 'Import complete!' ); ?></strong></p>');
									$('#loading').hide();								
								}
							} else {
								statusBox.append('<p>' + response.error + '</p>');
							}
						},
						error: function( response ) {
							statusBox.append('<p>' + response.error + '</p>');
						}
					});
				}

				statusBox.append('<p><?php echo sprintf( __( 'Loading CSV data (%d entries to import) ...' ), $linecount ); ?></p>');
				loadCSV(0);
			});
		</script>
        
        <div id="import_status">
        	<p><strong><?php _e( 'Please leave this page open, this process may take awhile.' ); ?></strong></p>
        </div>
        <div id="loading">
        	<img src="<?php echo plugins_url( 'img/loading.gif' , __FILE__ ); ?>" />
        </div>
<?php
		return;
    }
	
	function connect($headers, $rows) {
		$ret_arr = array();
		
        foreach( $rows as $record ) {
            $item_array = array();
            foreach( $record as $column => $value ) {
                $header = $headers[$column];
				$item_array[$header] = $value;
            }

            // do not append empty results
            if( $item_array !== array() ) {
                array_push($ret_arr, $item_array);
            }
        }

        return $ret_arr;
	}

	function import_cleanup_ajax() {
		$file = $_POST['file'];
		unlink($file);
	}
	
	function import_stories_ajax() {
		$current = $_POST['currentLine'];
		$status = $_POST['postStatus'];
		$post_type = $_POST['postType'];
		$file = $_POST['file'];

		if( !$file || !file_exists($file) )
			die( json_encode( array( 'success' => 0, 'error' => 'File does not exist. Exiting.' ) ) );
		
		$handle = fopen($file, 'r');
		$header = fgetcsv($handle);

		$i = 0;
		// need to skip ahead to the current position
		while( $i < $current ) {
			fgetcsv($handle);
			$i++;
		}

		// now get the relevant line
		$rows = array();
		if( $line = fgetcsv($handle) ) {
			$rows[] = $line;
		}
	    fclose($handle);
		
		// make line into an associative array
		$data = $this->connect($header, $rows);
		$csv_data = array_pop($data);

		$output = array( 'currentLine' => $current + 1 );

        if( $post_id = $this->create_post($csv_data, $post_type, $status) ) {
        	$output['success'] = sprintf( __("%d. \"%s\" successfully created/updated!"), $current + 1, get_the_title($post_id) );

        } else {
        	$output['success'] = sprintf( __("%d. Entry External ID #%s could not be imported."), $current + 1, $data['External ID'] );
        }
		
		die( json_encode( $output ) );
	}

    function create_post($data, $post_type, $status) {	
		$lookup_id = '';
    	if( is_numeric($data['External ID']) ) {
			$old_id = (int) $data['External ID'];
			$lookup = get_posts( array( 'posts_per_page' => 1, 'post_type' => $post_type, 'meta_key' => 'external_id', 'meta_value' => $old_id, 'fields' => 'ids' ) );

			if( !empty($lookup) )
				$lookup_id = array_pop($lookup);
		}

        $args = array(
            'post_title'	=> esc_attr( trim($data['Storyteller: First Name'] . ' ' . $data['Storyteller: Last Name']) ),
            'post_content'	=> wpautop(esc_attr(utf8_encode( $data['Final Copy'] ))),
            'post_type'		=> $post_type,
            'post_excerpt' 	=> wpautop(esc_attr(utf8_encode( $data['Story Summary'] ))),
			'ID'			=> $lookup_id,
			'post_status' 	=> $status
        );

        $id = wp_insert_post($args);

        if( $id ) {
        	$this->create_custom_fields($id, $data);
        	$this->create_terms($id, $data);
        	$this->create_thumbnail($id, $data['Headshot Photo']);

        	return $id;
        }
		
        return false;
    }

    function create_custom_fields($post_id, $data) {
    	$fields = array(
    		// meta_key => $data_key
            'first_name'	=> 'Storyteller: First Name',
            'last_name'		=> 'Storyteller: Last Name',
    		'external_id'	=> 'External ID',
    		'post_excerpt'  => 'Story Summary', 
    		'title' 		=> 'Current Job Title',
    		'year' 			=> 'Year of Participation',
    		'video_url' 	=> 'Video URL',
    		'company' 		=> 'Encore Company Name', 
    		'company_url' 	=> 'Encore Company Website',
    		'intro'			=> 'Storyteller Quote', /*fixed on July 29 2015. Was uploading to 'quotes'*/
    		'city'          => "Contact's City",
    		'state'         => "Contact's State",
    	);

    	foreach($fields as $meta_key => $field) {
    		update_post_meta( $post_id, $meta_key, $data[$field] );
    	}

    	if( function_exists('update_field') ) {
    		if( $company_url = trim($data['Encore Company Website']) ) {
    			if( strpos( $company_url, 'http://' ) !== 0 || strpos( $company_url, 'https://' ) !== 0 )
    				$company_url = 'http://' . $company_url;
    			
	    		$company = $data['Encore Company Name'];

		    	$links = array(
		    		array( 
		    			'link_text' => $company ? sprintf( __('Visit the %s website'), $company ) : __( 'Visit the company website' ),
		    			'link_url' => $company_url
		    		)
		    	);
		    	update_field('field_542d9e27600d2', $links, $post_id);
		    }

		    if( $quote = trim($data['Storyteller Quote']) ) {
		    	$quotes = array(
		    		array(
		    			'intro' => $quote, /* Also fixed July 29. */
		    			'position_top' => 0
		    		)
		    	);
		    	update_field('field_542da1d44ea6c', $quotes, $post_id);
		    }
	    }
    }

    function create_terms($post_id, $data) {
    	$tax_prefix = get_post_type($post_id) == 'purpose_prize' ? 'prize_' : 'story_';

    	if( $fields = $data['Field of Work'] ) {
    		$fields = explode(";", $fields);
    		$fields = array_filter( array_map('trim', $fields), 'strlen' );

    		$this->create_tax_group($post_id, $tax_prefix . 'field', $fields);
    	}

    	if( $winner = $data['Winner or Fellow?'] ) {
    		$types = array( $winner );
    		if( ($year = $data['Year of Participation']) )
    			$types[] = $winner . '|' . $year;

    		$this->create_tax_group($post_id, $tax_prefix . 'type', $types);
    	}

    	if( $rel = $data["Contact's State"] ) {
    		$rel = explode(";", $rel);
    		$rel = array_filter( array_map('trim', $rel), 'strlen' );

    		$this->create_tax_group($post_id, $tax_prefix . 'state', $rel);
    	}
    }

    function create_tax_group($post_id, $taxonomy, $term_names) {
    	if( !taxonomy_exists($taxonomy) ) return false;

		$term_ids = array();
		if( !empty($term_names) ) {
			foreach($term_names as $name) {
				$parent = 0;
				$name_parts = explode("|", $name);
				if( count($name_parts) > 1 ) {
					$parent_term = get_term_by('name', $name_parts[0], $taxonomy);
					$parent = $parent_term->term_id;
					$name = $name_parts[1];
				} else {
					$name = array_pop($name_parts);
				}
				
				if( !($term = term_exists($name, $taxonomy, $parent)) )  {
					$term = wp_insert_term($name, $taxonomy, array('parent' => $parent));
				}

				if( $term )
					$term_ids[] = $term['term_id'];
			}
		}
		return wp_set_post_terms($post_id, $term_ids, $taxonomy, true);
    }

    function create_thumbnail($post_id, $image_url) {
    	if( empty($image_url) ) return false;

    	if( strpos($image_url, 'http://') === false && strpos($image_url, 'https://') === false )
    		$image_url = get_bloginfo('url') . '/' . $image_url;

    	$image_url = str_replace(array(" ", ",", "(", ")"), "%20", $image_url);

		if( parse_url($image_url, PHP_URL_SCHEME) )
			$image_url = $this->remote_get_file($image_url);

		if( $image_url && file_exists($image_url) ) {
			$mime_type = '';
			$wp_filetype = wp_check_filetype(basename($image_url), null);
			if (isset($wp_filetype['type']) && $wp_filetype['type'])
				$mime_type = $wp_filetype['type'];
			unset($wp_filetype);
			
			$title = preg_replace('/\.[^.]+$/', '', basename($image_url));
			$attachment = array(
				'post_mime_type' => $mime_type ,
				'post_parent'    => $post_id,
				'post_title'     => $title ,
				'post_status'    => 'inherit',
			);
			$attachment_id = wp_insert_attachment($attachment, $image_url, $post_id);
			unset($attachment);

			if( !is_wp_error($attachment_id) ) {
				$attachment_data = wp_generate_attachment_metadata($attachment_id, $image_url);
				wp_update_attachment_metadata($attachment_id,  $attachment_data);
				unset($attachment_data);

				set_post_thumbnail($post_id, $attachment_id);

				return $attachment_id;
			}
		}

		return false;
    }

	function remote_get_file($url = null, $file_dir = '') {
		if (!$url)
			return false;

		if (empty($file_dir)) {
			 $upload_dir = wp_upload_dir();
			 $file_dir = isset($upload_dir['path']) ? $upload_dir['path'] : '';
		}
		$file_dir = trailingslashit($file_dir);

		// make directory
		if (!file_exists($file_dir)) {
			$dirs = explode('/', $file_dir);
			$subdir = '/';
			foreach ($dirs as $dir) {
				if (!empty($dir)) {
					$subdir .= $dir . '/';
					if (!file_exists($subdir)) {
						mkdir($subdir);
					}
				}
			}
		}

		// remote get!
		$photo = $file_dir . basename(str_replace(array("%20", " "), "-", $url));
		if ( !file_exists($photo) ) {
			if (function_exists('wp_safe_remote_get')) {
				$response = wp_safe_remote_get($url);
			} else {
				$response = wp_remote_get($url);
			}
			if ( !is_wp_error($response) && $response["response"]["code"] === 200 ) {
				$photo_data = $response["body"];
				file_put_contents($photo, $photo_data);
				unset($photo_data);
			} else {
				$photo = false;
			}
			unset($response);
		}
		return file_exists($photo) ? $photo : false;
	}    

    /**
     * Delete BOM from UTF-8 file.
     *
     * @param string $fname
     * @return void
     */
    function stripBOM($fname) {
        $res = fopen($fname, 'rb');
        if (false !== $res) {
            $bytes = fread($res, 3);
            if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
                $this->log['notice'][] = 'Getting rid of byte order mark...';
                fclose($res);

                $contents = file_get_contents($fname);
                if (false === $contents) {
                    trigger_error('Failed to get file contents.', E_USER_WARNING);
                }
                $contents = substr($contents, 3);
                $success = file_put_contents($fname, $contents);
                if (false === $success) {
                    trigger_error('Failed to put file contents.', E_USER_WARNING);
                }
            } else {
                fclose($res);
            }
        } else {
            $this->log['error'][] = 'Failed to open file, aborting.';
        }
    }
}


add_action( 'init', 'CSVImporterPlugin' );
function CSVImporterPlugin() {
	global $CSVImporterPlugin;
	$CSVImporterPlugin = new CSVImporterPlugin();
}
