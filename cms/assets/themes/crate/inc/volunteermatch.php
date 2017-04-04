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
		$data['fieldsToDisplay'][] = 'location';
		$data['fieldsToDisplay'][] = 'vmUrl';
		$data['fieldsToDisplay'][] = 'imageUrl';
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
