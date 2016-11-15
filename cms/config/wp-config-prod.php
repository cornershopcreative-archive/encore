<?php
/**
 * The base configuration of WordPress for the production website
 * Make sure to UPDATE LINE 17 OF wp-config.php WITH THE LIVE URL
 * OTHERWISE THIS FILE WILL NOT GET READ
 */

/** CHANGE THESE: Database connection information */
define('DB_NAME',     'wp_g2g');
define('DB_USER',     'CVcloud');
define('DB_PASSWORD', 'qCGixMOWQ8Y2UHbt');
define('DB_HOST',     '5430469950a1ec1a42c7815d40e16ee21512cab3.rackspaceclouddb.com');

/**
* For developers: WordPress debugging mode.
*
* Change this to true to enable the display of notices during development.
* It is strongly recommended that plugin and theme developers use WP_DEBUG
* in their development environments.
*/
define('WP_DEBUG',         false);
define('WP_DEBUG_LOG',     false);
define('WP_DEBUG_DISPLAY', false);

define('SCRIPT_DEBUG',     false);

define('WP_CACHE',         true);
define('SAVEQUERIES',      false);
