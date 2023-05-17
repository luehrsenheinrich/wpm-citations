<?php
/**
 * Lhplugin\Blocks\Component class
 *
 * @package lhpbpp
 */

namespace WpMunich\lhpbpp\Blocks;
use WpMunich\lhpbpp\Component;
use function add_action;
use function acf_register_block_type;
use function WpMunich\lhpbpp\lh_plugin;

/**
 * A class to handle the plugins blocks.
 */
class Blocks extends Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		if ( function_exists( 'acf_register_block_type' ) ) {
			add_action( 'acf/init', array( $this, 'register_acf_block_types' ) );
		}

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		add_filter( 'block_categories_all', array( $this, 'add_block_categories' ), 10, 2 );
	}

	/**
	 * Register ACF driven blocks.
	 *
	 * @return void
	 */
	public function register_acf_block_types() {
		acf_register_block_type(
			array(
				'name'            => 'acf-demo-block',
				'title'           => __( 'Demo Block', 'lhpbpp' ),
				'description'     => __( 'A demo block to show that everything is working.', 'lhpbpp' ),
				'category'        => 'lhpbpp-blocks',
				'icon'            => 'screenoptions',
				'keywords'        => array( __( 'ACF', 'lhpbpp' ), __( 'Demo', 'lhpbpp' ), __( 'Block', 'lhpbpp' ) ),
				'render_template' => apply_filters( 'lh_acf_block_template_path', lh_plugin()->get_plugin_path() . 'blocks/acf/template.php', 'acf-demo-block' ),
				'mode'            => 'auto',
				'supports'        => array(
					'align' => array( 'wide', 'full' ),
					'mode'  => 'auto',
				),
			)
		);
	}

	/**
	 * Register the plugins custom block category.
	 *
	 * @param array   $categories The block categories.
	 * @param WP_Post $post     The current post that is edited.
	 */
	public function add_block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'lhpbpp-blocks',
					'title' => __( 'Luehrsen // Heinrich', 'lhpbpp' ),
				),
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
				'lhpbpp-blocks-helper',
				lh_plugin()->get_plugin_url() . 'admin/dist/js/blocks-helper.min.js',
				array_merge( array(), $block_helper_assets['dependencies'] ),
				$block_helper_assets['version'],
				true
			);
		}

		$block_assets = $assets['js/blocks.min.js'] ?? array();
		wp_enqueue_script(
			'lhpbpp-blocks',
			lh_plugin()->get_plugin_url() . 'admin/dist/js/blocks.min.js',
			array_merge( array(), $block_assets['dependencies'] ),
			$block_assets['version'],
			true
		);

		wp_enqueue_style(
			'lhpbpp-admin-components',
			lh_plugin()->get_plugin_url() . '/admin/dist/css/components.min.css',
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
			'lhpbpp-blocks',
			'lhpbpp',
			$path
		);

		wp_set_script_translations(
			'lhpbpp-blocks-helper',
			'lhpbpp',
			$path
		);
	}

	/**
	 * Register the blocks.
	 */
	public function register_blocks() {
		$blocks_path = lh_plugin()->get_plugin_path() . 'blocks/';

		$custom_blocks = array(
			'demo',
		);

		foreach ( $custom_blocks as $block ) {
			register_block_type(
				$blocks_path . $block . '/',
				array(
					'render_callback' => array( $this, 'provide_render_callback' ),
				)
			);
		}
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
			case 'lh/demo':
				include $blocks_path . 'demo/template.php';
				break;
		}

		$block_html = ob_get_clean();

		return $block_html;
	}
}
