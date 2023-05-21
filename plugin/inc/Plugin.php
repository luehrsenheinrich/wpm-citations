<?php
/**
 * Plugin class
 *
 * @package citations
 */

namespace WpMunich\citations;
use function wp_json_file_decode;

/**
 * Main class for the plugin.
 *
 * This class takes care of initializing plugin features and available template tags.
 */
class Plugin {

	/**
	 * The blocks component.
	 *
	 * @var Blocks\Blocks
	 */
	private $blocks;

	/**
	 * The Citations component.
	 *
	 * @var Citations\Citations
	 */
	private $citations;

	/**
	 * The Patterns component.
	 *
	 * @var Patterns\Patterns
	 */
	private $patterns;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->blocks    = new Blocks\Blocks();
		$this->citations = new Citations\Citations();
		$this->patterns  = new Patterns\Patterns();

		add_action( 'init', array( $this, 'register_styles' ) );
	}

	/**
	 * Get the plugin version.
	 *
	 * @return string The plugin version.
	 */
	public function get_plugin_version() {
		// Check if we can use the `get_plugin_data()` function.
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		/**
		 * The plugin data as an array.
		 * We use this to avoid updating plugin data on multiple locations. This makes
		 * the file header of the plugin main file the single source of truth.
		 */
		$plugin_data = get_plugin_data( CITATIONS_FILE );

		return $plugin_data['Version'] ?? '0.0.1';
	}

	/**
	 * Get the main plugin file.
	 *
	 * @return string The main plugin file.
	 */
	public function get_plugin_file() {
		return CITATIONS_FILE;
	}

	/**
	 * Get the plugin directory path.
	 *
	 * @return string The plugin directory path.
	 */
	public function get_plugin_path() {
		return plugin_dir_path( $this->get_plugin_file() );
	}

	/**
	 * Get the plugin directory URL.
	 */
	public function get_plugin_url() {
		return plugin_dir_url( $this->get_plugin_file() );
	}

	/**
	 * Get the plugin slug.
	 *
	 * @return string The plugin slug.
	 */
	public function get_plugin_slug() {
		return 'citations';
	}

	/**
	 * Get the citations component.
	 *
	 * @return Citations\Citations The citations component.
	 */
	public function citations() {
		return $this->citations;
	}

	/**
	 * Register styles needed for the plugin.
	 */
	public function register_styles() {
		wp_register_style(
			'citations',
			lh_plugin()->get_plugin_url() . '/dist/css/style.min.css',
			array(),
			lh_plugin()->get_plugin_version(),
			'all'
		);
	}
}
