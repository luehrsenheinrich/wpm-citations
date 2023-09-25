<?php
/**
 * The main file of the plugin.
 *
 * @package citations
 *
 * Plugin Name: Citations
 * Plugin URI: https://wordpress.org/plugins/citations/
 * Description: This WordPress Plugin introduces advanced citation capabilities to the WordPress Block Editor.
 * Author: WP Munich
 * Author URI: https://www.wp-munich.com
 * Version: 0.2.1
 * Text Domain: citations
 */

namespace WpMunich\citations;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Set a constant for the plugin's main file.
if ( ! defined( 'CITATIONS_FILE' ) ) {
	/**
	 * The path to the main file of the plugin.
	 *
	 * @var string
	 */
	define( 'CITATIONS_FILE', __FILE__ );
}

// Set a constant for the plugin's directory.
if ( ! defined( 'CITATIONS_DIR' ) ) {
	/**
	 * The path to the directory of the plugin.
	 *
	 * @var string
	 */
	define( 'CITATIONS_DIR', plugin_dir_path( CITATIONS_FILE ) );
}

// Load the autoloader.
require plugin_dir_path( CITATIONS_FILE ) . 'vendor/autoload.php';

// Load the `wp_citations()` entry point function.
require plugin_dir_path( CITATIONS_FILE ) . 'inc/functions.php';

// Initialize the plugin.
call_user_func( 'WpMunich\citations\lh_plugin' );
