<?php
/**
 * LHPBPP\i18n\Component class
 *
 * @package lhpbpp
 */

namespace WpMunich\lhpbpp\i18n;
use WpMunich\lhpbpp\Component;
use function add_action;
use function load_plugin_textdomain;
use function WpMunich\lhpbpp\lh_plugin;

/**
 * A class to handle textdomains and other i18n related logic..
 */
class I18N extends Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		$dir  = str_replace( WP_PLUGIN_DIR, '', lh_plugin()->get_plugin_path() );
		$path = $dir . '/languages/';

		load_plugin_textdomain(
			'lhpbpp',
			false,
			$path
		);
	}
}
