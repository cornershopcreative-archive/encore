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
define('AUTH_KEY',         '%=vwN7LxYJ4.|hRsAt+9Q|iFerTr6Rx_+|+;~|8|;Z68)X0St$ xL-ULrb#tw[&G');
define('SECURE_AUTH_KEY',  'HU8>M%`@E[&Q%V{a01|$+s5H-Iwab6Xa<Q==$8VO!GqAsb$Z&FIjK+}NE)1/0(!/');
define('LOGGED_IN_KEY',    ';_Kh;pg]r,qVa]m|RbZ{{V{__,hh+bFHZ{G1+t-Zb=AmJ3:H5I]$Qm0.(dR5AzeS');
define('NONCE_KEY',        'f|k^]S)Dh1JH?aGG<}qka#E;N?.2]Q6}51GhGG[waH#O>0foDpwZ1Par78/r9q$X');
define('AUTH_SALT',        'YZ9samjHt1zq-&&&D&gv.:Bg:/akFs4wj^[:m<OagEj(|7[TO#x/Q+UpVWhFKU0H');
define('SECURE_AUTH_SALT', 'RZ-amz__<=v50vvRv9AXmH=w|/;aL~:HeVpyoq0N#0qHP6 b8,)(A+vYl=.=tY1e');
define('LOGGED_IN_SALT',   '4<D?L)0C<_Zta2FN{w#}6k=_y;Y3+; LAxF@vH5SPueE?at?q!bY#5v /qJ!*}-_');
define('NONCE_SALT',       'Al|t#3.t}=C_2Yj[b[1X-9u(B%6:9UYnZVw3 2viG|.nP=-Q|Eq&/v/^=&.YM,vw');
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
