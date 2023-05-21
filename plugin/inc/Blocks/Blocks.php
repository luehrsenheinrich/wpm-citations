<?php
/**
 * The file that handles the block logic.
 *
 * @package citations
 */

namespace WpMunich\citations\Blocks;
use function WpMunich\citations\lh_plugin;

/**
 * Main class for the blocks.
 */
class Blocks {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * Register the blocks.
	 */
	public function register_blocks() {
		register_block_type(
			CITATIONS_DIR . 'blocks/bibliography',
			array(
				'render_callback' => array( $this, 'provide_render_callback' ),
			)
		);
	}

	/**
	 * Enqueue the block scripts and styles.
	 */
	public function enqueue_block_editor_assets() {
		$screen = get_current_screen();

		$assets = wp_json_file_decode( lh_plugin()->get_plugin_path() . '/admin/dist/assets.json', array( 'associative' => true ) );

		if ( ! in_array( $screen->id, array( 'widgets' ), true ) ) {
			$block_helper_assets = $assets['js/blocks-helper.min.js'] ?? array();
			wp_enqueue_script(
				'citations-blocks-helper',
				lh_plugin()->get_plugin_url() . 'admin/dist/js/blocks-helper.min.js',
				array_merge( array(), $block_helper_assets['dependencies'] ),
				$block_helper_assets['version'],
				true
			);
		}

		$block_assets = $assets['js/blocks.min.js'] ?? array();
		wp_enqueue_script(
			'citations-blocks',
			lh_plugin()->get_plugin_url() . 'admin/dist/js/blocks.min.js',
			array_merge( array(), $block_assets['dependencies'] ),
			$block_assets['version'],
			true
		);

		wp_enqueue_style(
			'citations-admin',
			lh_plugin()->get_plugin_url() . '/admin/dist/css/admin.min.css',
			array(),
			lh_plugin()->get_plugin_version(),
			'all'
		);

		/**
		 * Load the translations for the block editor assets.
		 */
		$dir  = lh_plugin()->get_plugin_path();
		$path = $dir . '/languages/';

		wp_set_script_translations(
			'citations-blocks',
			'citations',
			$path
		);

		wp_set_script_translations(
			'citations-blocks-helper',
			'citations',
			$path
		);
	}

	/**
	 * Provide the render callback for the block.
	 *
	 * @param array    $attributes The block attributes.
	 * @param string   $content The block content.
	 * @param WP_Block $block The block type.
	 *
	 * @return string The rendered block.
	 */
	public function provide_render_callback( $attributes, $content, $block ) {
		$blocks_path = lh_plugin()->get_plugin_path() . 'blocks/';
		ob_start();

		switch ( $block->name ) {
			case 'lh/bibliography':
				include $blocks_path . 'bibliography/template.php';
				break;
		}

		$block_html = ob_get_clean();

		return $block_html;
	}
}
