<?php
/**
 * LHPBPT\Scripts\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\Scripts;
use WpMunich\lhpbpt\Component;
use function add_action;
use function WpMunich\lhpbpt\lh_theme;

/**
 * A class to enqueue the needed scripts..
 */
class Scripts extends Component {

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}

	/**
	 * Enqueue needed scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'lhpbpt-script', get_template_directory_uri() . '/dist/js/script.min.js', array(), lh_theme()->get_theme_version(), true );

		$translation_array = array(
			'themeUrl' => get_template_directory_uri(),
			'restUrl'  => get_rest_url(),
		);
		wp_localize_script( 'lhpbpt-script', 'lhpbpt', $translation_array );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'lhpbpt-admin-script', get_template_directory_uri() . '/admin/dist/js/script.min.js', array(), lh_theme()->get_theme_version(), true );
	}
}
