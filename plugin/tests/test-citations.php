<?php
/**
 * The basic tests for the plugin.
 *
 * @package citations
 */

use function WpMunich\citations\lh_plugin;

/**
 * Class Citations_Basic_Test
 */
class Citations_Basic_Test extends WP_UnitTestCase {

	/**
	 * Test if the plugin exists.
	 */
	public function test_plugin_exists() {
		$this->assertTrue( function_exists( 'WpMunich\citations\lh_plugin' ) );
	}

	/**
	 * Check if the citations file constant is defined.
	 */
	public function test_citations_file_constant() {
		$this->assertTrue( defined( 'CITATIONS_FILE' ) );
	}

	/**
	 * Check if the citations directory constant is defined.
	 */
	public function test_citations_dir_constant() {
		$this->assertTrue( defined( 'CITATIONS_DIR' ) );
	}
}
