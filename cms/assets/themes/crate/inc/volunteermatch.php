<?php
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
			'url'       => esc_url( urldecode( $opp['vmUrl'] ) ),
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
		<a href="<?php echo esc_url( urldecode( $opp['vmUrl'] ) ); ?>" target="_blank"><img src="<?php echo esc_url( urldecode( $opp['imageUrl'] ) ); ?>" alt="Logo for <?php echo esc_attr( $opp['title'] ) ; ?>"></a>
	<?php

	return ob_get_clean();
}

/**
 * Set up the daily VolunteerMatch sync task.
 */
function vmatch_cron_init() {
	if ( ! wp_next_scheduled( 'vmatch_sync' ) ) {
		// Schedule sync for just before midnight, PST.
		$sync_time = new DateTime();
		$sync_time->setTimezone( new DateTimeZone( 'America/Los_Angeles' ) );
		$sync_time->setTime( 23, 59, 59 );
		// If $sync_time is in the future, subtract one day. This will force sync to
		// occur immediately (or the next time wp-cron is run); then the next sync
		// will occur at midnight.
		if ( $sync_time->getTimestamp() > time() ) {
			$sync_time->sub( new DateInterval( 'P1D' ) );
		}
		wp_schedule_event( $sync_time->getTimestamp(), 'daily', 'vmatch_sync' );
	}
}
add_action( 'init', 'vmatch_cron_init' );

/**
 * Check the vmatch_sync_status option and launch or continue the sync process.
 *
 * This function is triggered by the daily cron task, and is called recursively
 * by vmatch_sync_loop().
 */
function run_vmatch_sync_loop() {

	// Get current sync status.
	$sync_status = get_option( 'vmatch_sync_status' );

	// If no sync status record was found, or if the most recent sync has ended,
	// start anew.
	if ( ! $sync_status || ( 'complete' === $sync_status['stage'] ) ) {
		$sync_status = array(
			'previous_page' => 0,
			'current_page' => 1,
			'retries' => 0,
			'stage' => 'get_local',
			'running' => false,
		);
		update_option( 'vmatch_sync_status', $sync_status );
	}

	// Launch a separate, asynchronous process to fetch and process the next page
	// of VMatch data.
	wp_remote_post( admin_url( 'admin-ajax.php' ), array(
		'blocking' => false,
		'body' => array( 'action' => 'vmatch_sync' ),
	) );
}
add_action( 'vmatch_sync', 'run_vmatch_sync_loop' );

/**
 * Get a page of opportunities using the VolunteerMatch API and convert them to
 * WordPress posts.
 */
function vmatch_sync_loop() {

	$count = 100;

	// Check current sync status.
	$sync_status = get_option( 'vmatch_sync_status' );

	// If no sync status info was found, bail.
	if ( ! $sync_status ) {
		return;
	}

	// If we're done syncing and ready to clean up old posts, go do that.
	if ( 'cleanup' === $sync_status['stage'] ) {
		vmatch_sync_cleanup();
		return;
	}

	// Otherwise, if we're not ready to move to the next page, bail.
	if ( $sync_status['running'] ) {
		return;
	}

	// If we're retrying the same page as the previous request, don't retry more
	// than three times.
	if ( $sync_status['previous_page'] === $sync_status['current_page'] ) {
		if ( $sync_status['retries'] > 2 ) {
			// TODO: save some indication that sync has failed.
			return;
		}
	}

	// Set 'running' flag to prevent running more than one sync task at once.
	$sync_status['running'] = true;
	update_option( 'vmatch_sync_status', $sync_status );

	// Log current status.
	$local_virtual = ( 'get_local' === $sync_status['stage'] ?
		'local' : 'virtual'
	);
	error_log( "VM SYNC: Processing $local_virtual page {$sync_status['current_page']}, $count per page" );

	// Hit up the VolunteerMatch API for opportunities, and save them as posts.
	$result = get_vmatch_results( array(
		'numberOfResults' => max( 1, $count ),
		'pageNumber' => max( 1, $sync_status['current_page'] ),
		'virtual' => ( 'get_virtual' === $sync_status['stage'] ),
	) );

	// If the API request returned an error, bump 'retries' count and try again.
	if ( ! $result || ! is_array( $result ) ) {
		error_log( "VM SYNC:   Request failed: {$result}" );
		$sync_status['retries'] += 1;
		$sync_status['previous_page'] = $sync_status['current_page'];
		$sync_status['running'] = false;
		update_option( 'vmatch_sync_status', $sync_status );
		run_vmatch_sync_loop();
		wp_send_json( $sync_status );
		exit;
	}

	// If the API request returned no opportunities, move on to the next stage.
	if ( empty( $result['opportunities'] ) ) {
		error_log( "VM SYNC:   No opportunities found." );
		$sync_status['retries'] = 0;
		$sync_status['previous_page'] = $sync_status['current_page'];
		$sync_status['running'] = false;

		if ( 'get_local' === $sync_status['stage'] ) {
			// If we haven't covered virtual opportunities yet, do that.
			$sync_status['previous_page'] = 0;
			$sync_status['current_page'] = 1;
			$sync_status['stage'] = 'get_virtual';
		} else {
			// Otherwise, start cleaning up.
			$sync_status['current_page'] = 0;
			$sync_status['stage'] = 'cleanup';
		}

		update_option( 'vmatch_sync_status', $sync_status );
		run_vmatch_sync_loop();
		wp_send_json( $sync_status );
		exit;
	}

	// Log the number of pages remaining.
	if ( ! empty( $result['resultsSize'] ) ) {
		$total_pages = ceil( $result['resultsSize'] / $count );
		$remaining_pages = $total_pages - $sync_status['current_page'];
		error_log( "VM SYNC:   $remaining_pages pages of results remaining." );
	}

	// Create or update posts for each opportunity returned.
	foreach ( $result['opportunities'] as $opp ) {
		// error_log( "VM SYNC:   Processing opportunity {$opp['id']}" );

		// Check for existing posts.
		$update = false; // By default, assume that we'll be inserting a new post.
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
			// If no existing post was found, start with an empty array, to which
			// we'll add a title, etc.
			$opp_post = array();
			// error_log( "VM SYNC:     No existing post found." );
		} else {
			// An existing post was found, so we'll be updating it instead of
			// inserting a new one.
			$update = true;
			$opp_post = array( 'ID' => $existing_posts[0]->ID );
			// error_log( "VM SYNC:     Existing post found: #{$existing_posts[0]->ID}" );
		}

		// Set the post type (duh).
		$opp_post['post_type'] = 'vm-opportunity';

		// Map VM fields to WP post fields.
		// Description -> post content.
		$opp_post['post_content'] = $opp['description'];
		unset( $opp['description'] );
		// Title -> post title.
		$opp_post['post_title'] = $opp['title'];
		unset( $opp['title'] );
		// Created -> post date + post date GMT.
		$opp_date = DateTime::createFromFormat( DATE_ISO8601, $opp['created'] );
		$opp_post['post_date'] = $opp_date->format( 'Y-m-d H:i:s' );
		$opp_date->setTimezone( new DateTimeZone( 'UTC' ) );
		$opp_post['post_date_gmt'] = $opp_date->format( 'Y-m-d H:i:s' );
		unset( $opp['created'] );
		// Updated -> post modified date + post modified date GMT.
		$opp_updated = DateTime::createFromFormat( DATE_ISO8601, $opp['updated'] );
		$opp_post['post_modified'] = $opp_updated->format( 'Y-m-d H:i:s' );
		$opp_updated->setTimezone( new DateTimeZone( 'UTC' ) );
		$opp_post['post_modified_gmt'] = $opp_updated->format( 'Y-m-d H:i:s' );
		unset( $opp['updated'] );

		// Set the post status (otherwise the new post would be left as a draft).
		$opp_post['post_status'] = 'publish';

		// Update/insert the post.
		if ( $update ) {
			$post_result = wp_update_post( $opp_post, true );
		} else {
			$post_result = wp_insert_post( $opp_post, true );
		}

		if ( is_wp_error( $post_result ) ) {
			// Something went wrong inserting the post.
			$verbing = ( $update ? 'updating' : 'inserting' );
			error_log( "VM SYNC:     Error $verbing post for opportunity {$opp['id']}:" );
			error_log( 'VM SYNC:       ' . $post_result->get_error_message() );
			// Stop processing this opportunity.
			continue;
		}

		// If $post_result isn't an error, it's the newly inserted/updated post ID.
		$opp_post_id = $post_result;

		$verbed = ( $update ? 'updated' : 'inserted' );
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

		// Add a meta flag indicating that this post was affected in the most recent
		// sync. After this sync is complete, we'll delete all posts that don't have
		// this flag, then delete this flag from all other posts.
		update_post_meta( $opp_post_id, '_vm__justSynced', 1 );
	}

	// Request the next page.
	$sync_status['previous_page'] = $sync_status['current_page'];
	$sync_status['current_page'] += 1;
	$sync_status['running'] = false;
	$sync_status['retries'] = 0;
	update_option( 'vmatch_sync_status', $sync_status );
	run_vmatch_sync_loop();
	wp_send_json( $sync_status );
	exit;
}
add_action( 'wp_ajax_nopriv_vmatch_sync', 'vmatch_sync_loop' );

/**
 * Clean up posts corresponding to VolunteerMatch opportunities that were not
 * affected by the last sync (which means they've been removed from the site).
 */
function vmatch_sync_cleanup() {

	// Get current sync status info.
	$sync_status = get_option( 'vmatch_sync_status' );

	// If we're not supposed to be in the cleanup stage, bail.
	if ( 'cleanup' !== $sync_status['stage'] ) {
		return;
	}

	error_log( 'VM SYNC: Cleaning up...');

	// Get all posts that don't have the 'justSynced' flag that vmatch_sync_loop()
	// adds to all updated posts.
	$unsynced = get_posts( array(
		'post_type' => 'vm-opportunity',
		'post_status' => 'publish',
		'meta_query' => array(
			'key' => '_vm__justSynced',
			'value' => 1,
			'compare' => '=',
		),
	) );

	error_log( 'VM SYNC:   Found ' . count( $unsynced ) . ' posts to delete.' );

	// Loop over all unsynced posts and trash them.
	foreach ( $unsynced as $old_post ) {
		$old_post->post_status = 'trash';
		$trash_result = wp_delete_post( $old_post->ID );
		if ( is_wp_error( $trash_result ) ) {
			// Something went wrong inserting the post.
			error_log( "VM SYNC:   Error deleting post #{$old_post->ID}:" );
			error_log( 'VM SYNC:     ' . $trash_result->get_error_message() );
		} else {
			error_log( "VM SYNC:   Deleted post #{$old_post->ID}" );
		}
	}

	// Delete justSynced flags from all remaining posts.
	error_log( 'VM SYNC: Deleted all old posts. Cleaning up justSynced flags...' );
	global $wpdb;
	$wpdb->query( "
		DELETE m.* FROM {$wpdb->postmeta} m
		JOIN {$wpdb->posts} p
			ON p.ID = m.post_id
		WHERE p.post_type = 'vm-opportunity'
			AND m.meta_key = '_vm__justSynced';
	" );
	if ( $wpdb->last_error ) {
		error_log( "VM SYNC:   Failed to clean up justSynced flags:" );
		error_log( "VM SYNC:     {$wpdb->last_error}" );
	} else {
		error_log( "VM SYNC:   Cleaned up justSynced flags." );
	}

	// Set sync stage to complete, because this sync is finally over.
	$sync_status['stage'] = 'complete';
	update_option( 'vmatch_sync_status', $sync_status );
}

/**
 * When retrieving a permalink for a VM opportunity, use the _vm_vmUrl field's
 * value if present.
 */
function vmatch_post_permalink( $url, $post ) {
	if ( 'vm-opportunity' === $post->post_type ) {
		if ( $vm_url = get_post_meta( $post->ID, '_vm_vmUrl', true ) ) {
			$url = $vm_url;
		}
	}
	return $url;
}
add_filter( 'post_type_link', 'vmatch_post_permalink', 10, 2 );

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
