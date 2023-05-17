<?php
/**
 * LHPBPT\Theme_Supports\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\Theme_Supports;
use WpMunich\lhpbpt\Component;
use function add_action;

/**
 * Add theme supports.
 */
class Theme_Supports extends Component {
	/**
	 * {@inheritdoc}
	 */
	public function add_actions() {
		add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_filters() {}

	/**
	 * Enable several theme supports for this theme.
	 *
	 * @return void
	 */
	public function add_theme_supports() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
	}
}
