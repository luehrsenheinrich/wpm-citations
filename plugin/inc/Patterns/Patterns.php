<?php
/**
 * The file that handles the patterns logic.
 *
 * @package citations
 */

namespace WpMunich\citations\Patterns;

use function WpMunich\citations\lh_plugin;

/**
 * The class that handles the patterns logic.
 */
class Patterns {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_patterns' ) );
	}

	/**
	 * Load the block patterns..
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_pattern/
	 * @return void
	 */
	public function register_patterns() {
		register_block_pattern(
			'citations/demo',
			array(
				'title'         => _x( 'Citations Demo', 'pattern title', 'citations' ),
				'description'   => __( 'A simple example pattern that provides a demo for the citations plugin.', 'citations' ),
				// phpcs:disable
				'content'       => $this->get_block_pattern_string( lh_plugin()->get_plugin_path() . '/inc/Patterns/p-demo.php' ),
				// phpcs:enable
				'categories'    => array( 'text' ),
				'keywords'      => array(
					_x( 'demo', 'block pattern keywords', 'citations' ),
					_x( 'cite', 'block pattern keywords', 'citations' ),
					_x( 'bibliography', 'block pattern keywords', 'citations' ),
					_x( 'footnotes', 'block pattern keywords', 'citations' ),
					_x( 'citation', 'block pattern keywords', 'citations' ),
				),
				'viewportWidth' => 1440,
			)
		);
	}

	/**
	 * Get the block pattern file.
	 *
	 * @param  string $path The path to the block pattern php file.
	 *
	 * @return string       The block pattern code.
	 */
	private function get_block_pattern_string( $path = '' ) {
		$block_pattern = '';

		if ( file_exists( $path ) ) {
			ob_start();
			include( $path ); // phpcs:ignore
			$block_pattern = ob_get_contents();
			ob_end_clean();
		}

		return $block_pattern;
	}
}
