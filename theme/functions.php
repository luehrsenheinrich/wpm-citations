<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package lhpbpt
 */

use function WpMunich\lhpbpt\lh_theme;
use function WpMunich\lhpbpt\theme_requirements_are_met;

// Get the autoloader.
require get_template_directory() . '/vendor/autoload.php';

// Load the `lh_theme()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'WpMunich\lhpbpt\lh_theme' );

// Initialize the plugin update checker.
if ( class_exists( 'Puc_v4_Factory' ) && theme_requirements_are_met() ) {
	Puc_v4_Factory::buildUpdateChecker(
		'https://www.luehrsen-heinrich.de/updates/?action=get_metadata&slug=' . lh_theme()->get_theme_slug(),
		__FILE__, // Full path to the main plugin file or functions.php.
		lh_theme()->get_theme_slug()
	);
}
