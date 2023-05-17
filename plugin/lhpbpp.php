<?php
/**
 * The main file of the plugin.
 *
 * @package citations
 *
 * Plugin Name: WordPress Project Boilerplate
 * Plugin URI: https://www.luehrsen-heinrich.de
 * Description: A base boilerplate for Luehrsen // Heinrich WordPress projects.
 * Author: Luehrsen // Heinrich
 * Author URI: https://www.luehrsen-heinrich.de
 * Version: 0.0.16
 * Text Domain: citations
 * Domain Path: /languages
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

// Load the autoloader.
require plugin_dir_path( CITATIONS_FILE ) . 'vendor/autoload.php';

// Load the `wp_citations()` entry point function.
require plugin_dir_path( CITATIONS_FILE ) . 'inc/functions.php';

// Initialize the plugin.
call_user_func( 'WpMunich\citations\lh_plugin' );
