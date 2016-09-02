<?php
/**
 * The base configuration of WordPress for the production website
 */

/** CHANGE THESE: Database connection information */
define('DB_NAME',     'cshop_grunt');
define('DB_USER',     'cshopgrunt');
define('DB_PASSWORD', 'oink-oink');
define('DB_HOST',     'mysql.cornershopcreative.com');

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