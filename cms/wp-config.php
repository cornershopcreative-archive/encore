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
	'ben.cshp.co'                => 'ben',
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
define('AUTH_KEY',         '*E[sye-w0>].:0O4MMc+1h2u+mZ;Y(t0iJwVQ5W3:6-TN0FEWJ [di}rC; O4Jn8');
define('SECURE_AUTH_KEY',  ']Gqzs+:xbzp;shCoo[?n- K-ma^dD~;,.&o15WB|N=9xl{6cgg:6`o=)#lG)-=QQ');
define('LOGGED_IN_KEY',    '0H#V$%WD(O!9uRDC~-wG{!,eYd6RgWz(+a:T4L~Zkw;i|6-*F(C^l1lE~]qe>2zK');
define('NONCE_KEY',        'BR?ipw6@r2:w6C|eZ]7w]3;HhelxE+rrm64SBLaCg$Qt{smvA-9-kCxt.+-_Hg]3');
define('AUTH_SALT',        '|Y5Po$}I<Q!@Q/*kAjNc<d;e++x[w./s5/|[|+;n|QW`]o7~t/$xw)5~->8(wy=>');
define('SECURE_AUTH_SALT', 'aV_zb@^G|YJ&XQ4/nW+a8&!3}MAZZT-Qed=I?9;^I~-h,5;ORU~N=k.9W|7i#0zZ');
define('LOGGED_IN_SALT',   '+J~0DSa;(g<#dBtLtDkC*Y+WX+hyU|1=p_S-jmZ|6L5Z0D:WD=AW%Z0=&GEBi+qt');
define('NONCE_SALT',       'KRM<|2}I,nB9Q_vygAg)6~U6wkQ_OX#6.|<#c;X(fO!p2_x%hY$MHe1mYX$MbP->');
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
