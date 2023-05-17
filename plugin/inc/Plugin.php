<?php
/**
 * LHPBPP\Plugin class
 *
 * @package lhpbpp
 */

namespace WpMunich\lhpbpp;
use InvalidArgumentException;

/**
 * Main class for the plugin.
 *
 * This class takes care of initializing plugin features and available template tags.
 */
class Plugin {

	/**
	 * ACF component.
	 *
	 * @var ACF\ACF;
	 */
	protected $acf;

	/**
	 * Blocks component.
	 *
	 * @var Blocks\Blocks;
	 */
	protected $blocks;

	/**
	 * I18N component.
	 *
	 * @var i18n\I18N;
	 */
	protected $i18n;

	/**
	 * SVG component.
	 *
	 * @var SVG\SVG;
	 */
	protected $svg;

	/**
	 * SVRESTG component.
	 *
	 * @var REST\REST;
	 */
	protected $rest;

	/**
	 * Constructor.
	 *
	 * @param ACF\ACF       $acf ACF component.
	 * @param Blocks\Blocks $blocks Blocks component.
	 * @param i18n\I18N     $i18n I18N component.
	 * @param SVG\SVG       $svg SVG component.
	 * @param REST\REST     $rest REST component.
	 */
	public function __construct(
		ACF\ACF $acf,
		Blocks\Blocks $blocks,
		i18n\I18N $i18n,
		SVG\SVG $svg,
		REST\REST $rest
	) {
		$this->acf    = $acf;
		$this->blocks = $blocks;
		$this->i18n   = $i18n;
		$this->svg    = $svg;
		$this->rest   = $rest;
	}

	/**
	 * Get the SVG component.
	 *
	 * @return SVG\SVG The SVG component.
	 */
	public function svg() {
		return $this->svg;
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
		$plugin_data = get_plugin_data( LHPBPP_FILE );

		return $plugin_data['Version'] ?? '0.0.1';
	}

	/**
	 * Get the main plugin file.
	 *
	 * @return string The main plugin file.
	 */
	public function get_plugin_file() {
		return LHPBPP_FILE;
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
		return 'lhpbpp';
	}
}
