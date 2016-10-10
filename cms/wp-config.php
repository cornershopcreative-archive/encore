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
define('AUTH_KEY',         '1nbRr+$FGU}=zM+yo@i;sC~Z96^CJZ&p^F b$,m.Fz)B.G*H&s+t_hywyM]YZozW');
define('SECURE_AUTH_KEY',  'h`ZRS`tc-Ffgss=Vv::a]^}9+%}GYV]%Z<lTv+*!ZJK*u!--kc=+Jd^$Sg~V[WNN');
define('LOGGED_IN_KEY',    'Ve;-[C=[}z:@T-ZEN%{4=4/jy-^azV|)l&Wb8!4GmaD-,92[xG3/K`14:D$J6H6T');
define('NONCE_KEY',        'x!m)}`{2 :[PF>W!zdy{-I+wl>-W?)s.Jd}W^(K::j}i%KYLE+h>noS5RNWUrSV}');
define('AUTH_SALT',        'B%s-0,V{^z!V3>p}a2fbkc^E-7bR<B;{Umo8!Ubc -c;B=NMCYmtRM#[3W1Fz)Gu');
define('SECURE_AUTH_SALT', '#YZgCoj.Yx]-k<,H}K-Oa/5BEuV{}TVG>~G?.ge.~=[-|Z2|<P*Wj6pW)=YO}J1;');
define('LOGGED_IN_SALT',   '2>1mBiGzAKO:;jT+1tCLOrp#}cjl*`Z&pd;6S+W0hRtxKgl:LqV!AefPj;M+qqq/');
define('NONCE_SALT',       'oR<ck$++H^>_)F7Dh3Z0W6Uo/:F&]IwP[CmSzzzm4-=k1$j2z,1LW`Q<^j7[d2yC');
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
