<?php

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$_SERVER['HTTP_HOST'] = gethostname();
	if ( 'dev.cshp.co' === $_SERVER['HTTP_HOST'] ) {
		// Making this smarter on our dev server
		$dirs = explode( '/', getcwd() );
		$dev_site = $dirs[3] . '.' . $dirs[1] . '.cshp.co';
		$_SERVER['HTTP_HOST'] = $dev_site;
	}
}

$configs = array(
	'cshp.co'                => 'demo',
	'cornershopcreative.com' => 'prod',
	'default'                => 'demo'
);
if ( isset( $dev_site) && !isset( $configs[ $dev_site ] ) ) {
	$configs[ $dev_site ] = 'demo';
}

// ** YOU SHOULDN'T NEED TO CHANGE ANYTHING BELOW THIS LINE, EVER ** //

// Load the appropriate config file
$which_config = $configs['default'];
unset( $configs['default'] );
foreach ( $configs as $domain => $config ) {
	if ( stripos( $_SERVER['HTTP_HOST'], $domain ) !== false ) {
		$which_config = $configs[ $domain ];
		break;
	}
}

require_once( __DIR__ . '/config/wp-config-' . $which_config . '.php' );

define( 'PROTOCOL', ( ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ) ? 'https' : 'http' ) );
define( 'WP_CONTENT_DIR', dirname(__FILE__) . '/assets' );  //might need to recode this to actual path
define( 'WP_CONTENT_URL', PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . '/cms/assets' );
define( 'WP_HOME',        PROTOCOL . '://' . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL',     PROTOCOL . '://' . $_SERVER['HTTP_HOST']. '/cms' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* @since 2.6.0
*/
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	define( 'AUTH_KEY',         '4~3Vf=E{W)mC=E6PD9f,tM$BHS.&g!zPa)LhekrTz9G?mcgUXD-tvt eY{vR?5(J' );
	define( 'SECURE_AUTH_KEY',  'yIu-<bPJi[ns*WD^ pad:1hNc~Gw.<f]7eo_Z9TWejJgy2?$jcgfJ<+V,6svV`6y' );
	define( 'LOGGED_IN_KEY',    '*O)Q ?64UdySo~Sr8nKL[}r[=*K.4RAN2j>IjA+DinQ%Y{B8;8QRq_JH=n<t-5A]' );
	define( 'NONCE_KEY',        'E@#oH?_*@Q]l|FL[(o:_g}Odv##EwG_:=+(Po(DkuW-Q-`{;VR(xQp!:6+ZiHK/R' );
	define( 'AUTH_SALT',        '#Cko9(^{N-E}|0tx>p5a La8/j1p| (K3ZS[h%Z5,OqiTe0#Y.&cfuEncz@hD/^I' );
	define( 'SECURE_AUTH_SALT', '-=yP7gJ7jH*%4sfUrt<#Z;h9Z{qO,4pbZ^Jx/1*mJq,hp[}-a4.h8yz[jc|Ku. +' );
	define( 'LOGGED_IN_SALT',   'b9I&8v.g}c-iP6wq3(i[^F`SrIB2?QG}FRTu2^g]yxCD-9Fvl-*IPxk?<o$o[4/-' );
	define( 'NONCE_SALT',       '#;3GRiU5pKB+o/K0c3;2ZVzt6[c$73~+9I`NP)W[VOO_6j%qJ|<E+XB9z8@(Jaj-' );
}
/**#@-*/

/**
* WordPress Database Table prefix.
*
* You can have multiple installations in one database if you give each a unique
* prefix. Only numbers, letters, and underscores please!
*/
$table_prefix  = 'wpdb_';

/**
* WordPress Localized Language, defaults to English.
*
* Change this to localize WordPress. A corresponding MO file for the chosen
* language must be installed to wp-content/languages. For example, install
* de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
* language support.
*/
define( 'WPLANG', 'en_EN' );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

/** Hack for various filesystem permissions issues. **/
if ( function_exists( 'is_admin' ) && is_admin() ) {
	add_filter( 'filesystem_method', create_function( '$a', 'return "direct";' ) );
	define( 'FS_CHMOD_DIR', 0751 );
}