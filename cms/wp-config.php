<?php

// Setting the HTTP_HOST value in the CLI environment
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$_SERVER['HTTP_HOST'] = gethostname();
	if ( stripos( $_SERVER['HTTP_HOST'], '.cshp.co' ) !== false ) {
		// Making this smarter on our dev server
		$dirs = explode( '/', getcwd() );
		$dev_site = $dirs[4] . '.' . $dirs[2] . '.cshp.co';
		$_SERVER['HTTP_HOST'] = $dev_site;
	}
}

// Logic to control which config file gets loaded when
$configs = array(
	'kenji.cshp.co'          => 'kenji',
	'cshp.co'                => 'demo',
	'CLIENT-LIVE-URL'        => 'prod',
	'default'                => 'demo'
);
if ( isset( $dev_site ) && !isset( $configs[ $dev_site ] ) ) {
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
define('AUTH_KEY',         'G%HEW-*=eif6^o0t|vS^xoNC}1$bB`;Z30Ztl~/Ch[B@&~M!s9<BBNg&Wg4Ef<MY');
define('SECURE_AUTH_KEY',  'H(:&:8P2Cu7s|;8rZ$oJ~m d:[)!L#}p$6T$eX7-;I?!0Gpxaq+wnCZTm=,m/f2J');
define('LOGGED_IN_KEY',    'lC%Ssk>x.GQ-T(7^)LNfb8>S ,?~pNr#A~Vd_D.=XY4U]56IO7@X,4%n)*4;a8;m');
define('NONCE_KEY',        ':7[l9!g^SJv-@QL:y-(%e8F$!EEh%YU qz`KY^dA>-d$jNyJfG2-Jc:J[rr0Jd+*');
define('AUTH_SALT',        'hHl/%uPfGT4bT}1v]MkPd*#!}T/Q~zYhAIuH3:D,SW$I.wGdL|5xqYWLZEOrvuD*');
define('SECURE_AUTH_SALT', 'D#[q~2uNeoPZ)m8KF-%Q:>tgo=fw+K(K/]K[lx`tbQJq,-<@3/trlzY=Z{ANIKYA');
define('LOGGED_IN_SALT',   '(>=0Xn]+tvY?J&#-;dI/MalEai4jVW|JA/?+gx_/Nfw1td&aYMkiZ%Xg`q+YT<u;');
define('NONCE_SALT',       'Q$72Ux*s}xyXX>vYw,dQV1j )X&KKILt9r5M{k:%u-_+&S/eNDu!u 2T&e7}Of|U');
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
