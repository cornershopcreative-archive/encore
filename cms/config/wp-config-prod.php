<?php
/**
 * The base configuration of WordPress for the production website
 * Make sure to UPDATE LINE 17 OF wp-config.php WITH THE LIVE URL
 * OTHERWISE THIS FILE WILL NOT GET READ
 */

/** CHANGE THESE: Database connection information */
define('DB_NAME',     'ENTER_LIVE_DB_NAME_HERE');
define('DB_USER',     'ENTER_LIVE_DB_USER_HERE');
define('DB_PASSWORD', 'ENTER_LIVE_DB_PASSWORD_HERE');
define('DB_HOST',     'ENTER_LIVE_DB_HOST_HERE_COULD_BE_LOCALHOST');

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

define('WP_CACHE',         false);
define('SAVEQUERIES',      false);