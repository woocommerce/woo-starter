<?php
/**
 * PHPUnit Bootstrap
 */

// Define the path to the wp-env tests directory
define( 'WP_TESTS_DIR', getenv( 'WP_TESTS_DIR' ) );

define( 'UNIT_TESTS', true );


// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file.
$_phpunit_polyfills_path = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( false !== $_phpunit_polyfills_path ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $_phpunit_polyfills_path );
}

// Load the WordPress testing environment
require_once WP_TESTS_DIR . '/includes/functions.php';

// Activate the plugin being tested
function _manually_load_plugin() {
	$plugin_dir  = dirname( dirname( __FILE__ ) );
	$plugin_file = $plugin_dir . '/plugin-name.php'; // Replace with your actual plugin file
	require_once $plugin_file;
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WordPress test environment
require WP_TESTS_DIR . '/includes/bootstrap.php';
