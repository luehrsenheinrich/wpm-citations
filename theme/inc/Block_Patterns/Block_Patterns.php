<?php
/**
 * LHPBPT\Block_Patterns\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\Block_Patterns;

use WpMunich\lhpbpt\Component;
use function add_action;

/**
 * A class to handle Block Patterns.
 */
class Block_Patterns extends Component {
	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'unregister_core_block_pattern_categories' ) );
		add_action( 'init', array( $this, 'register_block_pattern_categories' ) );
		add_action( 'init', array( $this, 'register_block_patterns' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}

	/**
	 * Unregister core block pattern categories.
	 *
	 * @return void
	 */
	public function unregister_core_block_pattern_categories() {
		remove_theme_support( 'core-block-patterns' );

		unregister_block_pattern_category( 'buttons' );
		unregister_block_pattern_category( 'columns' );
		unregister_block_pattern_category( 'gallery' );
		unregister_block_pattern_category( 'header' );
		unregister_block_pattern_category( 'text' );
		unregister_block_pattern_category( 'query' );
	}

	/**
	 * Register custom pattern categories.
	 *
	 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
	 * @return void
	 */
	public function register_block_pattern_categories() {
		register_block_pattern_category(
			'lhpbpt-pattern',
			array( 'label' => __( 'Lhpbpt Pattern', 'lhpbpt' ) )
		);
	}

	/**
	 * Load the block patterns..
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_pattern/
	 * @return void
	 */
	public function register_block_patterns() {
		register_block_pattern(
			'lhpbpt/example-pattern',
			array(
				'title'         => _x( 'Example Pattern', 'pattern title', 'lhpbpt' ),
				'description'   => __( 'A simple example pattern. If you can read this at prod call an admin.', 'lhpbpt' ),
				// phpcs:disable
				'content'       => $this->get_block_pattern_string( get_stylesheet_directory() . '/inc/Block_Patterns/bp-example.php' ),
				// phpcs:enable
				'categories'    => array( 'lhpbpt-pattern' ),
				'keywords'      => array(
					_x( 'Example', 'block pattern keywords', 'lhpbpt' ),
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
