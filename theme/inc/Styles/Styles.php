<?php
/**
 * LHPBPT\Styles\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\Styles;

use WpMunich\lhpbpt\Component;
use function add_action;
use function wp_enqueue_style;
use function wp_register_style;
use function WpMunich\lhpbpt\lh_theme;

/**
 * A class to enqueue the needed styles.
 */
class Styles extends Component {

	/**
	 * The variable where our CSS files are saved.
	 *
	 * @var mixed
	 */
	protected $css_files = false;

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ) );
		add_action( 'wp_head', array( $this, 'action_preload_styles' ) );
		add_action( 'wp_footer', array( $this, 'action_print_preloaded_styles' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_styles' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

		/** Remove WP Emoji */
		add_action( 'init', array( $this, 'remove_wp_emoji' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}
	/**
	 * Gets all CSS files.
	 *
	 * @return array Associative array of $handle => $data pairs.
	 */
	protected function get_css_files() : array {

		if ( is_array( $this->css_files ) ) {
			return $this->css_files;
		}

		$css_files = array(
			'lhpbpt-vars' => array(
				'file'   => 'vars.min.css',
				'global' => true,
			),
			'lhpbpt-base' => array(
				'file'   => 'base.min.css',
				'global' => true,
			),
			'lhpbpt-font-fira-sans' => array(
				'file'   => 'font-fira-sans.min.css',
				'global' => true,
			),
			'lhpbpt-blocks' => array(
				'file'             => 'blocks.min.css',
				'preload_callback' => '__return_true',
			),
			'lhpbpt-footer' => array(
				'file'             => 'footer.min.css',
				'preload_callback' => '__return_true',
			),
			'lhpbpt-loop' => array(
				'file' => 'loop.min.css',
			),
			'lhpbpt-archive' => array(
				'file'             => 'archive.min.css',
				'preload_callback' => 'is_archive',
			),
		);

		/**
		 * Filters default CSS files.
		 *
		 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
		 *                         $data must be an array with keys 'file' (file path relative to 'css'
		 *                         directory), and optionally 'global' (whether the file should immediately be
		 *                         enqueued instead of just being registered) and 'preload_callback' (callback)
		 *                         function determining whether the file should be preloaded for the current request).
		 */
		$css_files = apply_filters( 'lh_theme_css_files', $css_files );

		$this->css_files = array();
		foreach ( $css_files as $handle => $data ) {
			if ( is_string( $data ) ) {
				$data = array( 'file' => $data );
			}

			if ( empty( $data['file'] ) ) {
				continue;
			}

			$this->css_files[ $handle ] = array_merge(
				array(
					'global'           => false,
					'preload_callback' => null,
					'media'            => 'all',
					'enqueued'         => false,
					'preloaded'        => false,
				),
				$data
			);
		}

		return $this->css_files;
	}

	/**
	 * Determines whether to preload stylesheets and inject their link tags directly within the page content.
	 *
	 * Using this technique generally improves performance, however may not be preferred under certain circumstances.
	 * For example, since AMP will include all style rules directly in the head, it must not be used in that context.
	 * By default, this method returns true unless the page is being served in AMP. The
	 * {@see 'lhpbpt_preloading_styles_enabled'} filter can be used to tweak the return value.
	 *
	 * @return bool True if preloading stylesheets and injecting them is enabled, false otherwise.
	 */
	protected function preloading_styles_enabled() {
		$preloading_styles_enabled = true;

		/**
		 * Filters whether to preload stylesheets and inject their link tags within the page content.
		 *
		 * @param bool $preloading_styles_enabled Whether preloading stylesheets and injecting them is enabled.
		 */
		return apply_filters( 'lh_theme_preloading_styles_enabled', $preloading_styles_enabled );
	}

	/**
	 * Prints stylesheet link tags directly.
	 *
	 * This should be used for stylesheets that aren't global and thus should only be loaded if the HTML markup
	 * they are responsible for is actually present. Template parts should use this method when the related markup
	 * requires a specific stylesheet to be loaded. If preloading stylesheets is disabled, this method will not do
	 * anything.
	 *
	 * If the `<link>` tag for a given stylesheet has already been printed, it will be skipped.
	 *
	 * @param string ...$handles One or more stylesheet handles.
	 */
	public function print( string ...$handles ) {
		// If preloading styles is disabled (and thus they have already been enqueued), return early.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$css_files = $this->get_css_files();

		$handles = array_filter(
			$handles,
			function( $handle ) use ( $css_files ) {
				$is_valid = isset( $css_files[ $handle ] ) && ! $css_files[ $handle ]['global'];

				if ( ! $is_valid ) {
					/* translators: %s: stylesheet handle */
					_doing_it_wrong( __CLASS__ . '::print()', esc_html( sprintf( __( 'Invalid theme stylesheet handle: %s', 'lhpbpt' ), $handle ) ), 'lhpbpt' );
				}

				return $is_valid;
			}
		);

		if ( empty( $handles ) ) {
			return;
		}

		wp_print_styles( $handles );

		/* Mark the printed style as enqueued */
		foreach ( $handles as $handle ) {
			$this->css_files[ $handle ]['enqueued'] = true;
		}
	}


	/**
	 * Registers or enqueues stylesheets.
	 *
	 * Stylesheets that are global are enqueued. All other stylesheets are only registered, to be enqueued later.
	 */
	public function action_enqueue_styles() {
		$css_uri = get_theme_file_uri( '/dist/css/' );

		$preloading_styles_enabled = $this->preloading_styles_enabled();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {
			$src = $css_uri . $data['file'];

			/*
			 * Enqueue global stylesheets immediately and register the other ones for later use
			 * (unless preloading stylesheets is disabled, in which case stylesheets should be immediately
			 * enqueued based on whether they are necessary for the page content).
			 */
			if ( $data['global'] || ! $preloading_styles_enabled && is_callable( $data['preload_callback'] ) && call_user_func( $data['preload_callback'] ) ) {
				wp_enqueue_style( $handle, $src, array(), lh_theme()->get_theme_version(), $data['media'] );
				$this->css_files[ $handle ]['enqueued'] = true;
			} else {
				wp_register_style( $handle, $src, array(), lh_theme()->get_theme_version(), $data['media'] );
			}

			wp_style_add_data( $handle, 'precache', true );
		}
	}

	/**
	 * Preloads in-body stylesheets depending on what templates are being used.
	 *
	 * Only stylesheets that have a 'preload_callback' provided will be considered. If that callback evaluates to true
	 * for the current request, the stylesheet will be preloaded.
	 *
	 * Preloading is disabled when AMP is active, as AMP injects the stylesheets inline.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
	 */
	public function action_preload_styles() {

		// If preloading styles is disabled, return early.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$wp_styles = wp_styles();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {

			// Skip if stylesheet not registered.
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}

			// Skip if no preload callback provided.
			if ( ! is_callable( $data['preload_callback'] ) ) {
				continue;
			}

			// Skip if preloading is not necessary for this request.
			if ( ! call_user_func( $data['preload_callback'] ) ) {
				continue;
			}

			$preload_uri = $wp_styles->registered[ $handle ]->src . '?ver=' . $wp_styles->registered[ $handle ]->ver;

			echo '<link rel="preload" id="' . esc_attr( $handle ) . '-preload" href="' . esc_url( $preload_uri ) . '" as="style">';
			echo "\n";

			$this->css_files[ $handle ]['preloaded'] = true;
		}
	}

	/**
	 * Enqueues preloaded stylesheets in the footer, if they have not yet been printed.
	 */
	public function action_print_preloaded_styles() {

		$css_uri = get_theme_file_uri( '/dist/css/' );

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {
			$src = $css_uri . $data['file'];

			if ( ! $data['global'] && $data['preloaded'] && ! $data['enqueued'] ) {
				wp_enqueue_style( $handle, $src, array(), lh_theme()->get_theme_version(), $data['media'] );
			}
		}
	}

	/**
	 * Enqueues WordPress theme styles for the editor.
	 */
	public function action_add_editor_styles() {

		// Enqueue block editor stylesheet.
		add_editor_style( 'dist/css/font-fira-sans.min.css' );
		add_editor_style( 'dist/css/editor-styles.min.css' );
	}

	/**
	 * Enqueue assets directly for the editor.
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_style( 'lhpbpt-editor-vars', get_theme_file_uri( '/dist/css/vars.min.css' ), array(), lh_theme()->get_theme_version() );
	}

	/**
	 * Remove WP Emojis.
	 *
	 * @return void
	 */
	public function remove_wp_emoji() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}
}
