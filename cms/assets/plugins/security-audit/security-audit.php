<?php
/**
 * Plugin Name: Security Audit
 * Plugin URI: https://cornershopcreative.com
 * Description: Security Audit for WordPress including PHPSecInfo and WPScan
 * Version: 0.1
 * Author: Cornershop Creative
 * Author URI: https://cornershopcreative.com
 */

defined( 'ABSPATH' ) or die( 'Forbidden' );

/**
 * TO DO items (in no particular order):
 * 1. pass everything thru __() / _e() for i18n
 * 2. cache JSON listings to reduce hits on data sources
 *    (and/or build as jquery fetch so API only gets hit with one item at a time)
 * 3. Fetch individual theme/plugin info via async JS rather than PHP for better load times
 * 4. Reorganize and reformat code to be more DRY
 */

// Create Custom Page in tools.php
add_action( 'admin_menu', 'secaudit_custom_page' );
function secaudit_custom_page() {
	add_submenu_page( 'tools.php', 'Security Audit', 'Security Audit', 'manage_options', 'security-audit', 'secaudit_tab_content' );
}

// Actual page content callback, see above
function secaudit_tab_content() {

	echo '<div class="wrap">';

	$tab = ( isset( $_GET['tab'] ) ) ? sanitize_html_class( $_GET['tab'] ) : 'homepage';
	secaudit_admin_tabs( $tab );

	if ( 'homepage' == $tab ) {
		run_PHPSecInfo();
	} else {
		$scan = new WPSecurityAudit( $tab );
		$scan->show_results;
	}

	if ( 'themes' == $tab ) {
		?>
			<form role="search" method="post" id="searchform" action="">
				<div>
					<label class="screen-reader-text" for="themesearch">Search for:</label>
					<input type="search" placeholder="Search for..." value="" name="themesearch" id="themesearch">
					<?php wp_nonce_field( 'security-audit','theme-search-nonce' ); ?>
					<input type="submit" id="searchsubmit" value="Search">
				</div>
			</form>
		<?php
	}

	echo '</div>';
}

// Add our plugin's Tabs
function secaudit_admin_tabs( $current = 'homepage' ) {

	echo '<div id="icon-themes" class="icon32"><br></div>';
	$tabs = array(
		'homepage' => 'PHPSec Info',
		'plugin' => 'Plugin Scanner',
		'theme' => 'Theme Scanner',
		'core' => 'WordPress Core Scanner',
	);

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=security-audit&tab=$tab'>$name</a>";
	}
	echo '</h2>';
}

// Add our plugin's CSS, but only on our page
add_action( 'admin_enqueue_scripts', 'secaudit_enqueue' );
function secaudit_enqueue( $suffix ) {
	if ( 'tools_page_security-audit' == $suffix ) {
		wp_enqueue_style( 'wp-phpsecinfo', plugins_url( 'css/wp-secaudit.css', __FILE__ ) );
	}
}

/* =================== */
// PHPSecInfo
/* =================== */
function run_PHPSecInfo(){
	echo '<h2>' . __( 'PHP Security Audit', 'security-audit' ) . '</h2>';
	require_once('PhpSecInfo/PhpSecInfo.php');

	echo '<p>These checks are performed on the configuration of PHP your server is running. Functionality for this scan is provided by <a href="' . PHPSECINFO_URL . '" target="_blank">PHPSecInfo v' . PHPSECINFO_VERSION . '</a>.</p>';
	echo "<p>To address these issues, you'll need to edit your .htaccess file to change some PHP settings and/or contact your sysadmin/host and request that they update the PHP configuration accordingly.</p>";

		// instantiate the class
		$psi = new PhpSecInfo();

		// load and run all tests
		$psi->loadAndRun();

		// Show the Stats
	$psi->_outputRenderStatsTable();
	echo '<hr/>';

	// Display any tests that weren't run
	$psi->_outputRenderNotRunTable();
	echo '<hr/>';
	echo '<h2>Test Results</h2>';

	//Create tables with information
	foreach ( $psi->test_results as $group_name => $group_results ) {
		$psi->_outputRenderTable( $group_name, $group_results );
		echo '<hr/>';
	}
}

/* =================== */
// WP Security Audit
/* =================== */
class WPSecurityAudit {

	// $type can be any of the following
	//    - core
	//    - plugin
	//    - theme
	function __construct( $type ){
		global $wp_version;
		$this->wp_version = $wp_version;
		$this->type 	  = $type;

		$api_url          = $this->get_apiurl_by_type( $type );
		$items            = $this->get_items_by_type( $type );
		$vulns            = $this->get_vulnlist( $api_url, $type, $items );

		// heading based upon type
		$this->heading    = $this->get_heading( $type );

		// string containing result markup
		$this->results    = $this->get_results( $vulns, $items, $type );

		// Display Function
		$this->show_results = $this->show_results( $vulns, $items, $type );
	}

	public function show_results($vulns, $items, $type ){
		echo $this->heading;
		echo $this->results;
	}

	/*
	 *  Heading Markup
	 *
	 *  Displays the type of scan running and any relevant details before scan is run
	 */
	private function get_heading( $type ){
		$heading = '<h2>WordPress '. $this->type .' Scanner</h2>';
		$heading .= '<p>' . __( 'Vulnerability data for this scan is provided by <a href="https://wpvulndb.com/" target="_blank">https://wpvulndb.com/</a>.', 'security-audit' ) . '</p>';

		if ( 'core' == $this->type ) {
			global $wp_version;
			$heading .= '<p>You’re currently running WordPress version ' . $wp_version . '</p>';
		} elseif ( 'plugin' == $this->type ) {
			$items = get_plugins();
			if ( $items ) {
				$heading .= '<p>You currently have ' . count( $items ) . ' plugins installed.</p>';
			} else {
				$heading .= "<p>Couldn't find any installed plugins</p>";
			}
		} elseif ( 'theme' == $this->type ) {
			$items = wp_get_themes( array( 'errors' => false, 'allowed' => null, 'blog_id' => 0 ) );
			$heading .= '<p>You currently have ' . count( $items ) . ' themes installed.</p>';
		}

		return $heading;
	}

	/*
	 *  Sets the URL that the API will used based on which kind of scan it's running
     *
	 *  Type options:
	 *   -core
	 *   -plugin
	 *   -theme
	 */
	private function get_apiurl_by_type( $type ) {
		if ( 'core' == $type ){
			$api_url = 'https://wpvulndb.com/api/v1/wordpresses/';
		} elseif ( 'theme' == $type ){
			$api_url = 'https://wpvulndb.com/api/v1/themes/';
		} else {
			$api_url = 'https://wpvulndb.com/api/v1/plugins/';
		}
		return $api_url;
	}

	private function get_items_by_type( $type ) {
		if ( 'core' == $type ) {
			global $wp_version;
			$items  = $wp_version;
		} elseif ( 'theme' == $type ) {
			if ( $_POST ) {
				$items = sanitize_text_field( $_POST['themesearch'] );
			} else {
				$items = wp_get_themes( array( 'errors' => false, 'allowed' => null, 'blog_id' => 0 ) );
			}
		} else {
			$items = get_plugins();
		}
		return $items;
	}
	/*
	 *  Create Vulnerability List
	 *
	 *  Checks against the API for vulnerabilities
	 *  Needs $api_url of which API URL to hit
	 *  Needs $items array of pre-formatted plugin/theme/core data
	 *  Needs $type so we can format our item data appropriately
	 */
	private function get_vulnlist( $api_url, $type, $items ){
		if ( 'core' == $type ){
			// remove periods to format for API
			$version = str_replace( '.', '', $items );
			$core_vuln_data = json_decode( @file_get_contents( $api_url . '/' . $version ), true );
			$found_vulns = array();

			// check for vulnerabilities in installed version of WP
			if ( $core_vuln_data ){
				$cleaned = array_keys( $core_vuln_data );
				$found_vulns[ $version ] = array_shift( $core_vuln_data );
			}
		} elseif ( 'plugin' == $type ){
			$items = array_keys( $items );
			foreach ( $items as $plugin ) {
				// remove php filename from plugin (we only want the directory)
				$plugin = strstr( $plugin, '/', true );
				$plugin_vuln_data = json_decode( @file_get_contents( $api_url . '/' . $plugin ), true );

				if ( $plugin_vuln_data ){
					$cleaned = array_keys( $plugin_vuln_data );
					$found_vulns[ $plugin ] = array_shift( $plugin_vuln_data );
				}
			}
		} else {
			//normal behavior
			if ( is_array( $items ) ) {
				$items = array_keys( $items );
				// user search
			} elseif ( is_string( $items ) ) {
				// build a list of variants based on the user-entered value
				$items = array(
					$items,
					strtolower( $items ),
					str_replace( ' ', '-', strtolower( $items ) ),
					str_replace( ' ', '', strtolower( $items ) ),
				);
			}

			$found_vulns = array();

			// for all provided themes, see if there's a vulnerability entry
			foreach ( $items as $theme ) {
				$theme_vuln_data = json_decode( @file_get_contents( $api_url . '/' . $theme ), true );
				if ( $theme_vuln_data ) {
					$found_vulns[ $theme ] = array_shift( $theme_vuln_data );
				}
			}
		}
		return $found_vulns;
	}

	public function get_results( $found_vulns, $all_items, $type = 'plugins' ){
		$warn_count   = 0;
		$notice_count = 0;
		$ok_count 	  = 0;
		$total_count  = 0;
		$format       = get_option( 'date_format' ) . ' \<\b\r\> ' . get_option( 'time_format' );

		$output = '';

		foreach ( $found_vulns as $vuln_name => $vulns ) {
			$output .= '<hr>';
			$output .= '<table class="results">';
				$output .= '<tbody>';

					// for some reason, plugins is structured differently than themes. Maybe fix this...
					$vulns = $vulns['vulnerabilities'];

					// get plugin/theme details based on vuln_name
					$item = $this->get_item_info( $vuln_name, $all_items, $type );

			if ( 'core' == $type ) {
				global $wp_version;
				$output .= '<h3>WordPress Version ' . $wp_version . '</h3>';
			} else {
				d( $item );
				$output .= '<h3>' . $item['Name'] . '</h3>';
				$output .= '<span id="details">';
				if ( $item['Version'] ){
					$output .= '<p><b>';
						$output .= __( 'Installed version', 'security-audit' );
					$output .= ':</b> ' . $item['Version'] . '<br></p>';
				}
				$output .= '</span>';
			}

					$output .= '<tr>';
			foreach ( array( 'title', 'created', 'updated', 'fixed in' ) as $heading ) {
				$output .= "<th class='$heading'>" . ucfirst( $heading ) . '</th>';
			}
					$output .= '</tr>';

			foreach ( $vulns as $vulnerabilities ) {
				if ( ! isset( $vulnerabilities['fixed_in'] ) ) {
					$output .= '<tr class="value-notice">';
					$notice_count++;
					$total_count++;
				} elseif ( version_compare( $vulnerabilities['fixed_in'], $item['Version'],  '>' ) ) {
					$output .= '<tr class="value-warn">';
					$warn_count++;
					$total_count++;
				} elseif ( version_compare( $vulnerabilities['fixed_in'], $item['Version'],  '<=' ) ) {
					$output .= '<tr class="value-ok">';
					$ok_count++;
					$total_count++;
				} else {	// fallback
					$output .= '<tr>';
				}

				$output .= '<td>';
				if ( isset( $vulnerabilities['url'] ) ) {
					$output .= '<a href="' . $vulnerabilities['url'][0] . '" target="_blank">' . $vulnerabilities['title'] . '</a></td>';
				} else {
					$output .= $vulnerabilities['title'] . '</td>';
				}
				$output .= '<td>';
				$output .= date( $format, strtotime( $vulnerabilities['created_at'] ) );
				$output .= '</td>';
				$output .= '<td>';
				if ( isset( $vulnerabilities['updated_at'] ) ) {
					$output .= date( $format, strtotime( $vulnerabilities['updated_at'] ) );
				} else {
					_e( 'N/A', 'security-audit' );
				}
				$output .= '</td>';
				$output .= '<td>';
				if ( isset( $vulnerabilities['fixed_in'] ) ) {
					$output .= $vulnerabilities['fixed_in'];
				} else {
					$output .= __( '?', 'security-audit' );
				}

				$output .= '</tr>';
			}
				$output .= '</tbody>';
			$output .= '</table>';
		}
		$output .= '<hr>';

		if ( count( $found_vulns ) > 0 ) {
			if ( 'core' == $type ) {
				$type = 'WordPress versions';
				$type .= ". Please <a href='" . network_admin_url( 'update-core.php' ) . "'>update WordPress immediately</a>";
			} elseif ( 'theme' == $type ) {
				$type = 'theme(s)';
			}

			$output .= '<h3>Scan Summary</h3>';
			$output .= '<p><b>Found</b> ' . $total_count . ' <b><i>potential</i></b> vulnerabilities in ' . count( $found_vulns ) . ' different ' . $type .'.</p>';

			$output .= '<table class="results">';
				$output .= '<tbody>';
					$output .= '<tr>';
						$output .= '<td>currently active vulnerabilities</td>';
						$output .= '<td class="value-warn">'.  $warn_count .' of ' . $total_count . ' ' . $this->calc_percent( $warn_count, $total_count ) .'</td>';
					$output .= '</tr>';
					$output .= '<tr>';
						$output .= '<td>vulnerabilities without data on when/if they were fixed</td>';
						$output .= '<td class="value-notice">' . $notice_count .' of ' . $total_count . ' ' . $this->calc_percent( $notice_count, $total_count ) .'</td>';
					$output .= '</tr>';
					$output .= '<tr>';
						$output .= '<td>vulnerabilities that were fixed in previous versions</td>';
						$output .= '<td class="value-ok">'. $ok_count .' of '. $total_count . ' ' . $this->calc_percent( $ok_count, $total_count ) .'</td>';
					$output .= '</tr>';
				$output .= '</tbody>';
			$output .= '</table>';
		}

		if ( ! count( $found_vulns ) ) {
			$output = '<h3>' . __( 'No Vulnerabilities Found', 'security-audit' ) . '</h3>';
		}
		return $output;
	}

	/**
	 * Utility
	 * Extract name, version, etc from an array of plugins or themes based on a vulnerability identifier
	 */
	private function get_item_info( $label, $list, $type ) {
		if ( 'plugin' == $type ) {
			$plugin_key  = preg_grep( '/' . $label . '/', array_keys( $list ) );
			$plugin_key  = array_shift( $plugin_key );
			$plugin_info = $list[ $plugin_key ];
			return $plugin_info;
		} elseif ( 'theme' == $type ) {
			d( $label );
			d( $list );
			return array(
				'Name'    => $list[ $label ]->name,
				'Version' => $list[ $label ]->version,
			);
		} else {
			return array(
				'Name'    => 'Core',
				'Version' => $list,
			);
		}
	}
	/**
	 * formatting utility to calculate percentage
	 */
	private function calc_percent( $topval, $bottomval ) {
		$percent = (float) $topval / (float) $bottomval;
		$percent = round( (float) $percent * 100 ) . '%';
		return '(' . $percent . ')';
	}
}