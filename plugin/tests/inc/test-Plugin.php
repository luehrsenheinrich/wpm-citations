<?php
/**
 * The unit tests for the inc/Plugin.php file.
 *
 * @package citations
 */

use function WpMunich\citations\lh_plugin;

/**
 * Class Citations_Plugin_Test
 */
class Citations_Plugin_Test extends WP_UnitTestCase {

	/**
	 * The plugin instance.
	 *
	 * @var \WpMunich\citations\Plugin
	 */
	protected $plugin;

	/**
	 * Set up the plugin instance.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		$this->plugin = lh_plugin();
	}

	/**
	 * Test if the plugin is an instance of the Plugin class.
	 */
	public function test_plugin_instance() {
		$this->assertInstanceOf( 'WpMunich\citations\Plugin', $this->plugin );
	}

	/**
	 * Test if the citations component is an instance of the Citations class.
	 */
	public function test_citations() {
		$this->assertInstanceOf( 'WpMunich\citations\Citations\Citations', $this->plugin->citations() );
	}

	/**
	 * Test the plugin version.
	 */
	public function test_get_plugin_version() {
		$version = $this->plugin->get_plugin_version();

		// Check if the version is a string.
		$this->assertIsString( 'string', $version, 'The plugin version is not a string.' );

		// Check if the version is valid semver.
		$this->assertMatchesRegularExpression( '/^\d+\.\d+\.\d+$/', $version, 'The plugin version is not valid semver.' );
	}

	/**
	 * Test if the nesisary styles are registered.
	 */
	public function test_register_styles() {
		// Check if the styles are registered.
		$this->assertTrue( wp_style_is( 'citations', 'registered' ), 'The citations style is not registered.' );

		// Check if the styles are enqueued.
		$this->assertTrue( wp_style_is( 'citations', 'enqueued' ), 'The citations style is not enqueued.' );
	}
}
