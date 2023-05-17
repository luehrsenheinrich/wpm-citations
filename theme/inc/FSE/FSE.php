<?php
/**
 * LHPBPT\FSE\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\FSE;

use WpMunich\lhpbpt\Component;
use function add_action;

/**
 * A class to handle fullsite editing & theme.json
 */
class FSE extends Component {
	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'init', array( $this, 'remove_theme_support' ), 9 );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {
		/** Filter the block type metadata */
		add_filter( 'block_type_metadata', array( $this, 'filter_block_type_metadata' ) );
		add_filter( 'block_type_metadata_settings', array( $this, 'filter_block_type_metadata_settings' ), 10, 2 );
		add_filter( 'styles_inline_size_limit', '__return_zero' );
	}

	/**
	 * Remove theme support for core editor components.
	 */
	public function remove_theme_support() {
		/**
		 * Remove the block patterns defined by core.
		 */
		remove_theme_support( 'core-block-patterns' );

		/**
		 * Remove access to block templates.
		 */
		remove_theme_support( 'block-templates' );

		/**
		 * Do not query the block directory when no block is found.
		 */
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	}

	/**
	 * Filter block type metadata to remove unwanted output from the editor.
	 *
	 * @param array $metadata Metadata for the currently processed block type.
	 *
	 * @return array Filtered metadata.
	 */
	public function filter_block_type_metadata( $metadata ) {

		/**
		 * An array of block types that should be given support for alignwide.
		 *
		 * @var string[] $alignwide_blocks
		 */
		$alignwide_blocks = array( 'core/paragraph' );

		/**
		 * Add the alignwide option to the defined blocks.
		 */
		if ( in_array( $metadata['name'], $alignwide_blocks, true ) ) {
			if ( isset( $metadata['supports']['align'] ) && is_array( $metadata['supports']['align'] ) ) {
				$metadata['supports']['align'][] = 'wide';
			} else {
				$metadata['supports']['align'] = array( 'wide' );
			}
		}

		return $metadata;
	}

	/**
	 * Filter block type metadata settings to remove unwanted output from the editor.
	 *
	 * @param array $settings Metadata settings for the currently processed block type.
	 * @param array $metadata Metadata for the currently processed block type.
	 */
	public function filter_block_type_metadata_settings( $settings, $metadata ) {
		return $settings;
	}
}
