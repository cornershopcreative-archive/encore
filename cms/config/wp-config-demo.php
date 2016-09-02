<?php
/**
 * The base configuration of WordPress for Cornershop Dev
 */

/*
 * CHANGE THESE
 */
define('DB_NAME',     'demo_wordpress');
define('DB_USER',     'demo');
define('DB_PASSWORD', 'lamecanyon');
define('DB_HOST',     'localhost');

/**
 * For developers: WordPress debugging mode.
 */
define('WP_DEBUG',         true);
define('WP_DEBUG_LOG',     true);
define('WP_DEBUG_DISPLAY', false);
define('WP_CACHE',         false);
define('SAVEQUERIES',      true);
define('SCRIPT_DEBUG',     false);

/**
 * Fancy tricks for Crate
 */
define('ENVIRONMENT',      'dev');
// This should match what's on line ~10 of crate/Gruntfile.js
define('LIVERELOAD_PORT',  12345);