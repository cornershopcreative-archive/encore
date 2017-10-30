<?php
/**
 * VolunteerMatch API / syncing features.
 *
 * @package Crate
 */

// Load WP Background Processing classes.
require_once __DIR__ . '/vendor/wp-async-request.php';
require_once __DIR__ . '/vendor/wp-background-process.php';

/**
 * Sample code demonstrating use of VolunteerMatch API
 */
class VolunteerMatchAPI {
	private static $path;
	private static $key;
	private static $username;
	private static $responseBody;
	private static $responseCode;
	private static $errorMessage;

	private static $defaultError = "An error occurred while attempting to access VolunteerMatch";

	private static $errorMap = array(
		"http_400" => "A 400 error occurred while attempting to access VolunteerMatch", // bad request
		"http_403" => "Your account is not authorized to perform the specified action", //unauthorized action
		"http_404" => "Your username was not found in VolunteerMatch", // no account found
		"http_500" => "An error occurred in the VolunteerMatch service", //generic server error
		"http_502" => "The VolunteerMatch service is currently unavailable", // bad gateway
		"http_503" => "The VolunteerMatch service is currently unavailable", // service responded that it's unavailable
		"http_504" => "The VolunteerMatch service is currently unavailable", // timeout
	);

	public static function init($vmPath, $vmKey, $vmUsername) {
		self::$path = $vmPath;
		self::$key = $vmKey;
		self::$username = $vmUsername;
	}

	public function sendRequest($action, $query = NULL, $type = 'GET') {
		$result = "";

		$request_url = self::$path . '?action=' . $action;

		if ($query != NULL) {
			$json_query = json_encode($query);
			$request_url .= '&query=' . urlencode($json_query);
		}

		$curl_handle = curl_init();

		curl_setopt($curl_handle, CURLOPT_URL, $request_url);

		if ($type == 'GET') {
			curl_setopt($curl_handle, CURLOPT_HTTPGET, 1);
		}

		// prevent output of response contents to STDOUT
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

		// prevent self-signed SSL certificate errors.	Remove in production environments.
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);

		// set authentication headers
		// d( VolunteerMatchAPI::getHTTPHeadersForAuthenticationRequest() );
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, VolunteerMatchAPI::getHTTPHeadersForAuthenticationRequest());
		$response = curl_exec($curl_handle);

		if ( ! $response ) {
			$httpStatus = curl_getinfo( $curl_handle, CURLINFO_HTTP_CODE );
			$error = self::$errorMap["http_" . $httpStatus];
			self::$errorMessage = $error ? $error : self::$defaultError;
		} else {
			self::$responseBody = $response;
		}

		self::$responseCode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

		curl_close($curl_handle);
 }

	private function getHTTPHeadersForAuthenticationRequest() {
		$timestamp = time();
		$nonce = mt_rand();
		$date = date('Y-m-d\TH:i:sO', $timestamp);
		$digest = base64_encode(hash('sha256', $nonce . $date . self::$key, TRUE));
		$header_array = array(
		 'Content-Type: application/json',
		 'Authorization: WSSE profile="UsernameToken"',
		 'X-WSSE: UsernameToken Username="' . self::$username . '", ' .
						 'PasswordDigest="' . $digest . '", ' .
						 'Nonce="' . $nonce . '", ' .
						 'Created="' . $date . '"'
		);

		return $header_array;
	}

	private function displayResponse($type = 'none') {
		if (self::$errorMessage) {
			return self::$errorMessage;
		}

		$formattedData = json_decode(self::$responseBody, TRUE);

		switch ($type) {
			case 'methods':
				return $formattedData['methods'];
			case 'member detail':
			case 'member summary':
			case 'reviews detail':
				return self::displayArrayAsHTML($formattedData);
			case 'opp detail':
			case 'opp summary':
			case 'org detail':
			case 'org summary':
			default:
				return $formattedData;
		}
	}

	private function displayArrayAsHTML($arrayname,$tab="&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp",$indent=0) {
		$curtab ="";
		$returnvalues = "";
		while(list($key, $value) = each($arrayname)) {
			for($i=0; $i<$indent; $i++) {
				$curtab .= $tab;
			}
			if (is_array($value)) {
				$returnvalues .= "$curtab $key : Array: <br />$curtab{<br />\n";
				$returnvalues .= self::displayArrayAsHTML($value,$tab,$indent+1)."$curtab}<br />\n";
			}
			else $returnvalues .= "$curtab $key => $value<br />\n";
			$curtab = NULL;
		}
		return $returnvalues;
	}

	public function createOrUpdateMembers($data) {
		$members = array('members' => $data);
		self::sendRequest('createOrUpdateMembers', $members, 'POST');
		return self::displayResponse('member detail');
	}

	public function createOrUpdateReferrals($oppId, $data, $waitList = NULL, $invitedBy = NULL) {
		$referrals = array(
			'oppId' => $oppId,
			//'waitList' => TRUE,
			'referrals' => $data,
		);
		if ($waitList != NULL)
			$referrals['waitList'] = $waitList;
		if ($invitedBy != NULL)
			$referrals['invitedBy'] = $invitedBy;

		self::sendRequest('createOrUpdateReferrals', $referrals, 'POST');
		return self::displayResponse();
	}

	// $data should be in the form:
	//	array(pk, pk, ... )
	public function getMembersDetails($data) {
		$members = array('members' => $data);
		self::sendRequest('getMembersDetails', $members);
		return self::displayResponse('member detail');
	}

	// $data should be in the form:
	//	array(pk, pk, ... )
	public function getMembersReferrals($data) {
		$members = array('members' => $data);
		self::sendRequest('getMembersReferrals', $members);
		return self::displayResponse();
	}

	// $data should be in the form:
	//	array(oppId, oppId, ...)
	public function getOpportunitiesReferrals($data) {
		$opportunities = array('opportunities' => $data);
		self::sendRequest('getOpportunitiesReferrals', $opportunities);
		return self::displayResponse();
	}

	// can display the result set in detail, or as summaries
	public function searchOpportunities($data, $display = 'opp summary') {
		$data['fieldsToDisplay'] = array();
		$data['fieldsToDisplay'][] = 'id';
		$data['fieldsToDisplay'][] = 'title';
		$data['fieldsToDisplay'][] = 'greatFor';
		$data['fieldsToDisplay'][] = 'categoryIds';
		$data['fieldsToDisplay'][] = 'parentOrg';
		$data['fieldsToDisplay'][] = 'created';
		$data['fieldsToDisplay'][] = 'updated';
		$data['fieldsToDisplay'][] = 'location';
		$data['fieldsToDisplay'][] = 'vmUrl';
		$data['fieldsToDisplay'][] = 'imageUrl';
		$data['fieldsToDisplay'][] = 'virtual';
		$data['fieldsToDisplay'][] = 'plaintextDescription';

		if ($display != 'opp summary') {
			$data['fieldsToDisplay'][] = 'minimumAge';
			$data['fieldsToDisplay'][] = 'hasWaitList';
			$data['fieldsToDisplay'][] = 'volunteersNeeded';
			$data['fieldsToDisplay'][] = 'skillsNeeded';
			$data['fieldsToDisplay'][] = 'requirements';
			$data['fieldsToDisplay'][] = 'availability';
			$data['fieldsToDisplay'][] = 'referralFields';
			$data['fieldsToDisplay'][] = 'description';
		}

		self::sendRequest('searchOpportunities', $data);
		return self::displayResponse($display);
	}

	// can display the result set in detail, or as summaries
	public function searchOrganizations($data, $display = 'org summary') {
		$data['fieldsToDisplay'] = array();
		$data['fieldsToDisplay'][] = 'id';
		$data['fieldsToDisplay'][] = 'name';
		$data['fieldsToDisplay'][] = 'description';
		$data['fieldsToDisplay'][] = 'categoryIds';
		$data['fieldsToDisplay'][] = 'type';
		$data['fieldsToDisplay'][] = 'plaintextDescription';
		$data['fieldsToDisplay'][] = 'location';

		if ($display != 'org summary') {
			$data['fieldsToDisplay'][] = 'imageUrl';
			$data['fieldsToDisplay'][] = 'mission';
			$data['fieldsToDisplay'][] = 'url';
			$data['fieldsToDisplay'][] = 'contact';
			$data['fieldsToDisplay'][] = 'vmUrl';
			$data['fieldsToDisplay'][] = 'created';
			$data['fieldsToDisplay'][] = 'avgRating';
			$data['fieldsToDisplay'][] = 'numReviews';
		}
		self::sendRequest('searchOrganizations', $data);
		return self::displayResponse($display);
	}

	public function getOrganizationReviewsSummary($orgId) {
		$data = array('ids' => array($orgId),
						'fieldsToDisplay' => array('avgRating', 'numReviews'));
		self::sendRequest('searchOrganizations', $data);
		return self::displayResponse('reviews summary');
	}

	public function getOrganizationReviews($orgId) {
		$data = array('organization' => $orgId);
		self::sendRequest('getOrganizationReviews', $data);
		return self::displayResponse('reviews detail');
	}

	// this method should be called rarely and cached! results change infrequently
	public function getMetaData($version = NULL) {
		if ($version == NULL)
			self::sendRequest('getMetaData');
		else {
			$query = array('version' => $version);
			self::sendRequest('getMetaData', $query);
		}
		return self::displayResponse();
	}

	public function getKeyStatus() {
		self::sendRequest('getKeyStatus');
		return self::displayResponse();
	}

	// API testing method - not useful to real VM data
	public function testing() {
		self::sendRequest('helloWorld', array('name' => 'World'));
		return self::displayResponse();
	}

	// returns an array of the methods available to self::$key
	public function getMethods() {
		self::getKeyStatus();
		return self::displayResponse('methods');
	}

	// get the last response from VM
	public function getLastResponse($display = 'opp summary') {
		return self::displayResponse($display);
	}

	// service status
	public function getServiceStatus() {
			self::sendRequest('getServiceStatus');
			return self::displayResponse();
	}
}

VolunteerMatchAPI::init('http://www.volunteermatch.org/api/call',
												'3d9bd6e429f0381fe484d1e6c085012d',
												'generationtogeneration');


// use ajax to get more pages
add_action( 'rest_api_init', 'add_vmatch_api');

function add_vmatch_api(){
  register_rest_route( 'vmatch/v1', '/search/page/(?P<page>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_vmatch_basic_page',
  ));
}

/**
 * Get a page of VolunteerMatch API results for the REST API.
 */
function get_vmatch_basic_page( $data ) {

	$page = isset( $data['page'] ) ? $data['page'] : 1;

	$query = array(
		'pageNumber' => (int)$page,
	);

	if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
		// Special handling for Virtual...
		if ( 'virtual' === strtolower( $_GET['location'] ) ) {
			$query['virtual'] = true;
			$query['sortCriteria'] = 'update';
			$query['location'] = '';
		// Otherwise, use provided location
		} else {
			$query['location'] = $_GET['location'];
			$query['sortCriteria'] = 'distance';
		}
	}

	if ( isset( $_GET['keywords'] ) && ! empty( $_GET['keywords'] ) ) {
		$query['keywords'] = split( ' ', $_GET['keywords'] );
	}

	if ( isset( $_GET['radius'] ) && ! empty( $_GET['radius'] ) && is_numeric( $_GET['radius'] ) ) {
		$query['radius'] = (string) ( $_GET['radius'] / 0.6214 );	// convert miles to km, and stringify
	}

	// Get API results.
	$results = get_vmatch_results( $query );

	// Format them for the Mustache template.
	$results = format_vmatch_results( $results );

	return $results;
}

/**
 * Get results for a VolunteerMatch API call.
 */
function get_vmatch_results( $query ) {

	$api = new VolunteerMatchAPI();

	// Set up some search defaults.
	// 22 is the ID of the "Children & Youth" Cause Area
	// greatFor = 's' (great for 55+), 't' (great for teens), 'g' (great for groups), 'k' (great for kids)
	// Easiest way to lookup IDs is to visit http://www.volunteermatch.org/search
	$query = wp_parse_args( $query, array(
		'location'        => 'United States',
		'sortCriteria'    => 'default',
		'categoryIds'     => array(22),
		'opportunityTypes' => array( 'public' ),
		'numberOfResults' => 12,
		'keywords'        => array(),
		'greatFor'        => array('s'),
		'pageNumber'      => 1,
	) );

	$cache_key = 'vmatch_result_' . md5( serialize( $query ) );

	// If there's a cached result for this query, return it.
	// if ( get_transient( $cache_key ) ) return get_transient( $cache_key );

	// No cached result? Query the API.
	$results = $api->searchOpportunities( $query, 'opp detail' );

	// We can't do anything if VolunteerMatchAPI didn't give us anything useful
	if ( ! isset( $results['opportunities'] ) ) {
		return array( 'page' => $query['pageNumber'] );
	}

	// Cache results, unless this is a keyword search (saving every keyword
	// search could get expensive, db-wise).
	if ( empty( $query['keywords'] ) ) {
		set_transient( $cache_key, $results, 5 * MINUTE_IN_SECONDS );
	}

	return $results;
}

/**
 * Reformat VM query results to conform to what the mustache template needs.
 */
function format_vmatch_results( $results ) {

	foreach ( $results['opportunities'] as &$opp ) {
		$opp = array(
			'url'       => esc_url( vmatch_add_utm_args( urldecode( $opp['vmUrl'] ) ) ),
			'imagehtml' => _get_vmatch_opp_image_html( $opp ),
			'name'      => $opp['title'],	// dangerous but neccesarry
			'summary'   => wp_trim_words( stripslashes( wp_kses_post( $opp['plaintextDescription'] ) ), 30 ),
			'city'      => $opp['location']['city'],
			'region'    => $opp['location']['region'],
		);
	}

	return $results;
}

/**
 * Converts $org data returned by API into useful image html
 */
function _get_vmatch_opp_image_html( $opp ) {

	if ( empty( $opp['imageUrl'] ) ) return '<div class="no-image"></div>';

	ob_start(); ?>
		<a href="<?php echo esc_url( vmatch_add_utm_args( urldecode( $opp['vmUrl'] ) ) ); ?>" target="_blank"><img src="<?php echo esc_url( urldecode( $opp['imageUrl'] ) ); ?>" alt="Logo for <?php echo esc_attr( $opp['title'] ) ; ?>"></a>
	<?php

	return ob_get_clean();
}

class VolunteerMatch_Sync_Process extends WP_Background_Process {

	protected $action = 'vmatch_sync';

	/**
	 * Start processing the next page of VolunteerMatch opportunities.
	 */
	public function process_next_page() {

		// Get current sync status.
		$sync_status = get_option( 'vmatch_sync_status' );

		// If no sync status record was found, start anew.
		if ( ! $sync_status ) {

			// Get batch size option. We'll store this in the sync status because
			// otherwise, if a user were to change the batch size mid-sync, that would
			// throw pagination off.
			$batch_size = (int) get_field( 'vm_batch_size', 'option' );

			// Default to 100 if no batch size was set.
			if ( ! $batch_size ) {
				$batch_size = 100;
			}

			// Clamp to min/max values.
			$batch_size = min( 200, max( 1, $batch_size ) );

			$now = new DateTime();
			$now->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );

			// Set initial values for sync status info.
			$sync_status = update_vmatch_sync_status( array(
				'start' => time(),
				'start_string' => $now->format( 'D M j, Y g:i:sa' ),
				'last_active' => time(),
				'batch_size' => $batch_size,
				'previous_page' => 0,
				'current_page' => 1,
				'total_pages' => 0,
				'retries' => 0,
				'stage' => 'get_local',
				'added' => 0,
				'updated' => 0,
				'skipped' => 0,
				'deleted' => 0,
				'errors' => 0,
			) );
		}

		// If we're retrying the same page as the previous request, don't retry more
		// than three times.
		if ( $sync_status['previous_page'] === $sync_status['current_page'] ) {
			if ( $sync_status['retries'] > 2 ) {
				// Move on to the next page. Who knows what's going on here.
				$sync_status = update_vmatch_sync_status( array(
					'previous_page' => $sync_status['current_page'],
					'current_page'  => $sync_status['current_page'] + 1,
					'retries'       => 0,
				) );
			}
		}

		// Log current status.
		$local_virtual = ( 'get_local' === $sync_status['stage'] ?
			'local' : 'virtual'
		);
		error_log( "VM SYNC: Processing $local_virtual page {$sync_status['current_page']}, {$sync_status['batch_size']} per page" );

		// Hit up the VolunteerMatch API for opportunities, and save them as posts.
		$result = get_vmatch_results( array(
			'numberOfResults' => $sync_status['batch_size'],
			'pageNumber' => max( 1, $sync_status['current_page'] ),
			'virtual' => ( 'get_virtual' === $sync_status['stage'] ),
		) );

		// If the API request returned an error, bump 'retries' count and try again.
		if ( ! $result || ! is_array( $result ) ) {
			error_log( "VM SYNC:   Request failed: {$result}" );
			$sync_status = update_vmatch_sync_status( array(
				'retries'       => $sync_status['retries'] + 1,
				'previous_page' => $sync_status['current_page'],
			) );
			$this->process_next_page();
			return;
		}

		// If the API request returned no opportunities, move on to the next stage.
		if ( empty( $result['opportunities'] ) ) {
			error_log( "VM SYNC:   No opportunities found." );
			$sync_status = update_vmatch_sync_status( array(
				'retries'       => 0,
				'previous_page' => $sync_status['current_page'],
			) );

			if ( 'get_local' === $sync_status['stage'] ) {
				// If we haven't covered virtual opportunities yet, do that.
				$sync_status = update_vmatch_sync_status( array(
					'previous_page' => 0,
					'current_page'  => 1,
					'stage'         => 'get_virtual',
				) );
			} else {
				// Otherwise, start cleaning up.
				$sync_status = update_vmatch_sync_status( array(
					'current_page' => 0,
					'stage'        => 'cleanup',
				) );
				global $vmatch_cleanup_process;
				$vmatch_cleanup_process->begin();
				return;
			}

			$this->process_next_page();
			return;
		}

		// Log the number of pages remaining.
		if ( ! empty( $result['resultsSize'] ) ) {
			$total_pages = (int) ceil( $result['resultsSize'] / $sync_status['batch_size'] );
			$remaining_pages = $total_pages - $sync_status['current_page'];
			error_log( "VM SYNC:   $remaining_pages pages of results remaining." );
			// Store total page count.
			$sync_status = update_vmatch_sync_status( 'total_pages', $total_pages );
		}

		// If there aren't any opportunities to sync, call complete().
		if ( empty( $result['opportunities'] ) ) {
			$this->complete();
			return;
		}

		// Save VM data and start processing it.
		foreach ( $result['opportunities'] as $opp ) {
			$this->push_to_queue( $opp );
		}
		$this->save()->dispatch();
	}

	/**
	 * Create or update a WordPress post based on VolunteerMatch data.
	 *
	 * @param array $opp VolunteerMatch opportunity data.
	 */
	protected function task( $opp ) {

		if ( ! $opp ) {
			error_log( 'VM SYNC:   Falsey opportunity found in queue... something is amiss.' );
			return false;
		}

		error_log( "VM SYNC:   Processing opportunity {$opp['id']}" );

		// Check for existing posts.
		$is_update = false; // By default, assume that we'll be inserting a new post.
		$existing_posts = get_posts( array(
			'post_type'   => 'vm-opportunity',
			'post_status' => 'any',
			'meta_key'    => '_vm_id',
			'meta_value'  => $opp['id'],
			'numberposts' => 1,
			'order'       => array(
				'post_date' => 'desc',
			),
		) );

		if ( empty( $existing_posts ) ) {
			// If no existing post was found, start with an empty object, to which
			// we'll add a title, etc.
			$opp_post = new StdClass();
			error_log( "VM SYNC:     No existing post found." );
		} else {
			// An existing post was found, so we'll be updating it instead of
			// inserting a new one.
			$is_update = true;
			// Get current post data.
			$opp_post = get_post( $existing_posts[0]->ID );
			// Get the 'last updated' time that was stored the last time we synced this post.
			$old_modified_date = get_post_meta( $opp_post->ID, '_vm_updated', true );
			// If the creation + modification dates match, don't bother updating this
			// post -- just add the _vm__justSynced flag so this post doesn't get
			// deleted during cleanup.
			if ( $old_modified_date === $opp['updated'] ) {
				error_log( "VM SYNC:     Skipping update for opportunity {$opp['id']} -- post already exists and dates match." );
				update_post_meta( $opp_post->ID, '_vm__justSynced', 1 );
				$sync_status = increment_vmatch_sync_count( 'skipped' );
				return false;
			}
			error_log( "VM SYNC:     Existing post found: #{$existing_posts[0]->ID}" );
		}

		// Set the post type (duh).
		$opp_post->post_type = 'vm-opportunity';

		// Map VM fields to WP post fields.
		// Description -> post content.
		$opp_post->post_content = $opp['description'];
		unset( $opp['description'] );

		// Title -> post title.
		$opp_post->post_title = $opp['title'];
		unset( $opp['title'] );

		// Created -> post date + post date GMT.
		$opp_date = DateTime::createFromFormat( DATE_ISO8601, $opp['created'] );
		$opp_post->post_date = $opp_date->format( 'Y-m-d H:i:s' );
		$opp_date->setTimezone( new DateTimeZone( 'UTC' ) );
		$opp_post->post_date_gmt = $opp_date->format( 'Y-m-d H:i:s' );
		unset( $opp['created'] );

		// Set the post status (otherwise the new post would be left as a draft).
		$opp_post->post_status = 'publish';

		// Update/insert the post.
		if ( $is_update ) {
			$post_result = wp_update_post( $opp_post, true );
		} else {
			$post_result = wp_insert_post( $opp_post, true );
		}

		if ( is_wp_error( $post_result ) ) {
			// Something went wrong inserting the post.
			$verbing = ( $is_update ? 'updating' : 'inserting' );
			error_log( "VM SYNC:     Error $verbing post for opportunity {$opp['id']}:" );
			error_log( 'VM SYNC:       ' . $post_result->get_error_message() );
			// Increment error count.
			$sync_status = increment_vmatch_sync_count( 'errors' );
			// Stop processing this opportunity.
			return false;
		}

		// If $post_result isn't an error, it's the newly inserted/updated post ID.
		$opp_post_id = $post_result;

		$verbed = ( $is_update ? 'updated' : 'inserted' );
		error_log( "VM SYNC:     Post #$opp_post_id $verbed successfully." );

		// Remove URL escape sequences from URL fields, and change HTTP protocols
		// to HTTPS.
		$opp['imageUrl'] = crate_sanitize_vmatch_url( $opp['imageUrl'] );
		$opp['vmUrl'] = crate_sanitize_vmatch_url( $opp['vmUrl'] );

		// If no image URL is present, get the org image URL.
		if ( ! $opp['imageUrl'] ) {
			// TODO: use a global API object?
			$api = new VolunteerMatchAPI();
			$org_results = $api->searchOrganizations( array(
				'ids' => array( $opp['parentOrg']['id'] ),
			), 'opp detail' );
			if ( ! empty( $org_results['organizations'] ) ) {
				$opp['orgImageUrl'] = crate_sanitize_vmatch_url( $org_results['organizations'][0]['imageUrl'] );
			}
		}

		// TODO: process geolocation data.

		// Map remaining VM fields to post meta fields.
		foreach ( $opp as $key => $val ) {
			$prefix = '_vm_';
			// Split associative arrays into multiple fields.
			if ( in_array( $key, array( 'location', 'availability', 'parentOrg' ), true ) ) {
				$prefix = $prefix . $key . '_';
				foreach ( $val as $sub_key => $sub_val ) {
					if ( is_array( $sub_val ) ) {
						foreach ( $sub_val as $subsub_key => $subsub_val ) {
							update_post_meta( $opp_post_id, "_vm_{$key}_{$sub_key}_{$subsub_key}", $subsub_val );
						}
					} else {
						update_post_meta( $opp_post_id, "_vm_{$key}_{$sub_key}", $sub_val );
					}
				}
			} else {
				update_post_meta( $opp_post_id, "_vm_$key", $val );
				// For Virtual positions, add an empty 'state' meta value. This helps make FacetWP filtering simpler.
				if ( ( 'virtual' === $key ) && $val ) {
					update_post_meta( $opp_post_id, '_vm_location_region', 'virtual' );
				}
			}
		}

		// Add to added/updated count in status option.
		$sync_status = increment_vmatch_sync_count( $is_update ? 'updated' : 'added' );

		// Add a meta flag indicating that this post was affected in the most recent
		// sync. After this sync is complete, we'll delete all posts that don't have
		// this flag, then delete this flag from all other posts.
		update_post_meta( $opp_post_id, '_vm__justSynced', 1 );
		return false;
	}

	/**
	 * After processing a page of results, maybe get another page, or maybe start cleanup.
	 */
	protected function complete() {

		// Call parent method in order to clean up scheduled cron events.
		parent::complete();

		$sync_status = get_option( 'vmatch_sync_status' );

		// Request the next page.
		update_vmatch_sync_status( array(
			'previous_page' => $sync_status['current_page'],
			'current_page'  => $sync_status['current_page'] + 1,
			'retries'       => 0,
		) );
		$this->process_next_page();
	}
}

/**
 * Async task for cleaning up after a VolunteerMatch sync.
 */
class VolunteerMatch_Cleanup_Process extends WP_Background_Process {

	/**
	 * Identifier for cleanup process data.
	 *
	 * @var string
	 */
	protected $action = 'vmatch_cleanup';

	/**
	 * Begin the cleanup process.
	 */
	public function begin() {

		error_log( 'VM SYNC: Cleaning up...' );

		// Get all posts that don't have the 'justSynced' flag that the sync adds to all updated posts.
		$unsynced = get_posts( array(
			'numberposts' => -1,
			'post_type' => 'vm-opportunity',
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => '_vm__justSynced',
					'value' => 1,
					'compare' => 'NOT EXISTS',
				),
			),
		) );

		error_log( 'VM SYNC:   Found ' . count( $unsynced ) . ' posts to delete.' );

		// If there aren't any posts to delete, call the complete handler (otherwise it will never be
		// called!).
		if ( empty( $unsynced ) ) {
			$this->complete();
			return;
		}

		// Loop over all unsynced posts and delete them.
		foreach ( $unsynced as $old_post ) {
			$this->push_to_queue( $old_post->ID );
		}
		$this->save()->dispatch();
	}

	/**
	 * Delete a post by ID.
	 *
	 * @param int $post_id The ID of the post to delete.
	 */
	protected function task( $post_id ) {

		// First, a sanity check: is this a VolunteerMatch post? If not, bail.
		$post = get_post( $post_id );
		if ( ! $post || 'vm-opportunity' !== $post->post_type ) {
			return false;
		}

		$delete_result = wp_delete_post( $post_id, true ); // Force deletion, don't just trash them.

		if ( is_wp_error( $delete_result ) ) {
			// Something went wrong deleting the post.
			error_log( "VM SYNC:   Error deleting post #$post_id:" );
			error_log( 'VM SYNC:     ' . $delete_result->get_error_message() );
			increment_vmatch_sync_count( 'errors' );
		} else {
			error_log( "VM SYNC:   Deleted post #$post_id" );
			increment_vmatch_sync_count( 'deleted' );
		}

		return false;
	}

	/**
	 * Fire actions after VM sync cleanup.
	 */
	protected function complete() {

		error_log( 'VM SYNC: Deleted all old posts. Cleaning up justSynced flags...' );

		// Delete justSynced flags from all remaining posts.
		global $wpdb;
		$wpdb->query( "
			DELETE m.* FROM {$wpdb->postmeta} m
			JOIN {$wpdb->posts} p
				ON p.ID = m.post_id
			WHERE p.post_type = 'vm-opportunity'
				AND m.meta_key = '_vm__justSynced';
		" );

		if ( $wpdb->last_error ) {
			error_log( 'VM SYNC:   Failed to clean up justSynced flags:' );
			error_log( "VM SYNC:     {$wpdb->last_error}" );
		} else {
			error_log( 'VM SYNC:   Cleaned up justSynced flags.' );
		}

		// Set sync stage to complete, because this sync is finally over. Store a
		// human-readable end time string too.
		$now = new DateTime();
		$now->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );
		update_vmatch_sync_status( array(
			'stage'      => 'complete',
			'end_string' => $now->format( 'D M j, Y g:i:sa' ),
		) );

		// Call parent method in order to clean up scheduled cron events.
		parent::complete();

		// Now that we're fully sunc, tell FacetWP to re-index.
		if ( function_exists( 'FWP' ) ) {
			FWP()->indexer->index();
		}
	}
}

// Instantiate global process objects.
global $vmatch_sync_process, $vmatch_cleanup_process;
$vmatch_sync_process = new VolunteerMatch_Sync_Process();
$vmatch_cleanup_process = new VolunteerMatch_Cleanup_Process();

/**
 * Set up the daily VolunteerMatch sync task.
 */
function vmatch_cron_setup( $post_id ) {

	// Bail if the user didn't just update the Options page.
	if ( 'options' !== $post_id ) {
		return;
	}

	global $vmatch_sync_process, $vmatch_cleanup_process;

	// If automatic sync is enabled, make sure there's a sync task scheduled for
	// the proper time.
	if ( get_field( 'vm_sync_enabled', 'option' ) ) {

		// Get sync time option.
		$desired_sync_time = get_field( 'vm_sync_time', 'option' );

		// Default to 11:59 PM if no time was set.
		if ( ! $desired_sync_time ) {
			$desired_sync_time = '23:59:00';
		}

		// Split into hours, minutes, and seconds.
		list( $h, $m, $s ) = explode( ':', $desired_sync_time );

		// Convert to Pacific Time.
		$sync_time = new DateTime();
		$sync_time->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );
		$sync_time->setTime( $h, $m, $s );
		$sync_timestamp = $sync_time->getTimestamp();

		// Get timestamp for the next scheduled auto-sync.
		$next_sync = wp_next_scheduled( 'vmatch_sync' );

		if ( ! $next_sync || $next_sync !== $sync_timestamp ) {

			// Clear any previously scheduled sync task.
			wp_clear_scheduled_hook( 'vmatch_sync' );

			// Check the sync timestamp to make sure that the event we're scheduling will be in the
			// future, otherwise we can run into awful endless loops.
			if ( $sync_timestamp < time() ) {
				$sync_time->add( new DateInterval( 'P1D' ) );
				$sync_timestamp = $sync_time->getTimestamp();
			}
			// Schedule the auto-sync job.
			wp_schedule_event( $sync_timestamp, 'daily', 'vmatch_sync' );
		}
	} elseif ( wp_next_scheduled( 'vmatch_sync' ) ) {

		// If sync is disabled but an auto-sync task is scheduled, un-schedule it.
		wp_clear_scheduled_hook( 'vmatch_sync' );

	}
}
add_action( 'acf/save_post', 'vmatch_cron_setup', 20 );

/**
 * Check the vmatch_sync_status option and launch or continue the sync process.
 */
function run_vmatch_sync() {

	$sync_status = get_option( 'vmatch_sync_status' );
	if ( ! $sync_status || 'complete' === $sync_status['stage'] ) {
		// Clear sync status, so that when we call process_next_page() below, the sync process knows it's
		// supposed to start over completely.
		delete_option( 'vmatch_sync_status' );

		global $vmatch_sync_process;
		$vmatch_sync_process->process_next_page();
	}
}
add_action( 'vmatch_sync', 'run_vmatch_sync' );

/**
 * When retrieving a permalink for a VM opportunity, use the _vm_vmUrl field's
 * value if present.
 */
function vmatch_post_permalink( $url, $post ) {
	if ( 'vm-opportunity' === $post->post_type ) {
		if ( $vm_url = get_post_meta( $post->ID, '_vm_vmUrl', true ) ) {
			$url = vmatch_add_utm_args( $vm_url );
		}
	}
	return $url;
}
add_filter( 'post_type_link', 'vmatch_post_permalink', 10, 2 );

/**
 * Add UTM query args to a VolunteerMatch URL.
 */
function vmatch_add_utm_args( $url ) {
	return add_query_arg( array(
		'_sid'         => 'gen2gen',
		'utm_source'   => 'gen2gen',
		'utm_medium'   => 'referral',
		'utm_campaign' => 'gen2gen',
	), $url );
}

/**
 * Customize the VolunteerMatch State facet so it displays full state names
 * instead of abbreviations, and so it includes an option for 'anywhere' /
 * 'virtual'.
 */
function vmatch_facetwp_index_row( $params, $class ) {
	if ( 'vm-state' === $params['facet_name'] ) {
		if ( $state_name = vmatch_get_state_name( $params['facet_value'] ) ) {
			$params['facet_display_value'] = $state_name;
		} elseif ( 'virtual' === $params['facet_value'] ) {
			$params['facet_display_value'] = 'Anywhere (Virtual)';
		}
	}
	return $params;
}
add_filter( 'facetwp_index_row', 'vmatch_facetwp_index_row', 10, 2 );

/**
 * Add an alternate value for the VolunteerMatch State facet if no post meta
 * value is present.
 */
function vmatch_facetwp_indexer_row_data( $rows, $params ) {
	if ( 'vm-state' === $params['facet']['name'] ) {
		if ( empty( $rows ) ) {
			$rows[] = array_merge( $params['defaults'], array(
				'facet_value' => 'virtual',
				'facet_display_value' => 'Anywhere (Virtual)',
			) );
		}
	}
	return $rows;
}
// add_filter( 'facetwp_indexer_row_data', 'vmatch_facetwp_indexer_row_data', 10, 2 );

function vmatch_get_state_name( $abbrev ) {
	// props https://gist.github.com/maxrice/2776900
	$state_names = array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AS' => 'American Samoa',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FM' => 'Federated States of Micronesia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'GU' => 'Guam gu',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MH' => 'Marshall Islands',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'MP' => 'Northern Mariana Islands',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PW' => 'Palau',
		'PA' => 'Pennsylvania',
		'PR' => 'Puerto Rico',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VI' => 'Virgin Islands',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
		'AE' => 'Armed Forces Africa \ Canada \ Europe \ Middle East',
		'AA' => 'Armed Forces America (except Canada)',
		'AP' => 'Armed Forces Pacific',
	);
	if ( isset( $state_names[ $abbrev ] ) ) {
		return $state_names[ $abbrev ];
	} else {
		return false;
	}
}

/**
 * Sanitize a URL returned from a VMatch API call. Most (if not all) URLs
 * returned are urlencoded and use http:.
 */
function crate_sanitize_vmatch_url( $url ) {
	return str_replace( 'http://', '//', urldecode( $url ) );
}

/**
 * Add an AJAX action for getting info on the current VM sync process.
 */
function crate_get_vmatch_sync_status() {
	// Get sync status info.
	$sync_status = get_option( 'vmatch_sync_status' );
	// Add server time, so client-sie script can compare it to last_active time.
	$sync_status['server_time'] = time();
	// Output status data.
	wp_send_json( $sync_status );
	exit;
}
add_action( 'wp_ajax_vmatch_sync_status', 'crate_get_vmatch_sync_status' );

/**
 * Add an AJAX action for manually starting a VolunteerMatch sync.
 */
function crate_vmatch_sync_start() {
	// Clear out current/old sync status, if any.
	delete_option( 'vmatch_sync_status' );
	// Start syncing all over again.
	run_vmatch_sync();
	wp_send_json( array( 'status' => 1 ) );
	exit;
}
add_action( 'wp_ajax_vmatch_sync_start', 'crate_vmatch_sync_start' );

// TODO: add a function to run the cleanup process + start again, if sync breaks?
/**
 * Update one or more keys in the vmatch_sync_status option without overriding
 * any other existing keys, and automatically set the last_active timestamp.
 *
 * Can be called with one argument (an array of keys and values) or two
 * arguments (a key and a value).
 */
function update_vmatch_sync_status( $key_or_array, $value = null ) {

	// Get current option value, or initialize to an empty array.
	$sync_status = get_option( 'vmatch_sync_status' );
	if ( ! $sync_status ) $sync_status = array();

	// Handle alternate method signatures.
	if ( is_array( $key_or_array) ) {
		$new_values = $key_or_array;
	} else {
		$new_values = array( $key_or_array => $value );
	}

	// Merge new values into old array.
	$sync_status = array_merge( $sync_status, $new_values, array(
		'last_active' => time(),
	) );

	// Update.
	update_option( 'vmatch_sync_status', $sync_status );

	// Return the new value, so the caller doesn't have to use get_option() again.
	return $sync_status;
}

/**
 * Increment a sync status count.
 */
function increment_vmatch_sync_count( $key ) {
	$sync_status = get_option( 'vmatch_sync_status' );
	return update_vmatch_sync_status( $key, $sync_status[$key] + 1 );
}
