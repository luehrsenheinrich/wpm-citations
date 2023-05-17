<?php
/**
 * Lhtheme\Lazysizes\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\Lazysizes;
use WpMunich\lhpbpt\Component;
use function add_action;
use function WpMunich\lhpbpt\lh_theme;

/**
 * Add a class to handle lazy loading of images.
 */
class Lazysizes extends Component {

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		if ( ! is_admin() ) {
			add_filter( 'wp_get_attachment_image_attributes', array( $this, 'modify_image_attributes' ), 10, 3 );
			add_filter( 'render_block_core/image', array( $this, 'rewrite_image_block' ), 10, 3 );
		}
	}

	/**
	 * Modify image attributes to work with lazysizes.
	 *
	 * @param string[]     $attr       Array of attribute values for the image markup, keyed by attribute name.
	 *                                 See wp_get_attachment_image().
	 * @param WP_Post      $attachment Image attachment post.
	 * @param string|int[] $size       Requested image size. Can be any registered image size name, or
	 *                                 an array of width and height values in pixels (in that order).
	 *
	 * @return string[]                Array of attribute values for the image markup, keyed by attribute name.
	 */
	public function modify_image_attributes( $attr, $attachment, $size ) {
		if ( isset( $attr['srcset'] ) && ! empty( $attr['srcset'] ) ) {

			// Modify the class of the image.
			$attr['class'] = classNames(
				$attr['class'] ? $attr['class'] : null,
				array(
					'lazyload' => true,
				)
			);

			$srcset_array = $this->srcset_to_array( $attr['srcset'] );

			// Set the data-sizes attribute to auto.
			$attr['data-sizes']  = 'auto';
			$attr['data-srcset'] = $this->array_to_srcset( $srcset_array );
			$smallest            = $this->get_smallest_from_wp_srcset( $attr['srcset'] );
			if ( $smallest ) {
				$attr['data-src'] = $attr['src'];
				$attr['src']      = $smallest;
			}

			unset( $attr['sizes'] );
			unset( $attr['srcset'] );
		}

		return $attr;
	}

	/**
	 * Transform a srcset to an array
	 * Note that this currently only works with width descriptor tags and not with
	 * pixel density descriptor.
	 *
	 * @param string $srcset The srcset.
	 */
	public function srcset_to_array( $srcset ) {
		$srcset_array = explode( ',', $srcset );
		$pattern      = "/(?'src'https?:\/\/\S*) (?'width'\d*)w/";

		$return_array = array();
		foreach ( $srcset_array as $image ) {
			preg_match( $pattern, $image, $matches );
			$return_array[] = array(
				'width' => $matches['width'],
				'src'   => esc_url( $matches['src'] ),
			);
		}

		uasort( $return_array, array( $this, 'sort_srcset_array' ) );

		return $return_array;
	}

	/**
	 * Transform an array of srcset-items to a valid srcset-string.
	 *
	 * @param array $srcset_array An array of srcset-items.
	 *
	 * @return string A valid srcset-string.
	 */
	public function array_to_srcset( $srcset_array ) {
		$return_srcset = array();

		foreach ( $srcset_array as $image ) {
			$val = $image['src'];
			if ( isset( $image['width'] ) ) {
				$val .= ' ' . $image['width'] . 'w';
			}

			$return_srcset[] = $val;
		}

		return implode( ', ', $return_srcset );
	}

	/**
	 * Given a srcset this function extracts the smalles source.
	 *
	 * @param string $srcset An html srcset.
	 */
	public function get_smallest_from_wp_srcset( $srcset ) {
		$srcset_array = $this->srcset_to_array( $srcset );
		$smallest     = array_shift( $srcset_array );

		return $smallest['src'];
	}

	/**
	 * Sort the srcset array by width.
	 * Note: The srcset spec does not explicity say that a srcset has to be ordered.
	 * We do it anyways for several reasons, including readability, debugging and
	 * because Lighthouse seems to like it.
	 *
	 * @param array $a A srcset-array-item.
	 * @param array $b A srcset-array-item.
	 */
	private function sort_srcset_array( $a, $b ) {

		if ( ! isset( $a['width'] ) || ! isset( $b['width'] ) ) {
			return 0;
		}

		if ( $a['width'] === $b['width'] ) {
			return 0;
		}

		return ( $a['width'] < $b['width'] ) ? -1 : 1;
	}


	/**
	 * Enqueue needed scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'lhtheme-lazysizes', get_template_directory_uri() . '/dist/js/lazysizes.min.js', array(), lh_theme()->get_theme_version(), true );
	}

	/**
	 * Rewrite the image block to use lazysizes.
	 *
	 * @param string   $block_content The block content.
	 * @param array    $block         The full block, including name and attributes..
	 * @param WP_Block $instance      The block instance.
	 *
	 * @return string The rewritten block content.
	 */
	public function rewrite_image_block( $block_content, $block, $instance ) {

		/**
		 * Check if the block has manual sizes set.
		 */
		$has_width  = $block['attrs']['width'] ?? false;
		$has_height = $block['attrs']['height'] ?? false;
		$is_resized = $has_width && $has_height;

		/**
		 * Suppress errors, as libxml does not support HTML5.
		 */
		libxml_use_internal_errors( true );

		/**
		 * Initialize the DOMDocument.
		 */
		$doc = new \DOMDocument();

		/**
		 * Load the block content into a DOMDocument.
		 * We cannot just load the block content as is, as it will be cast as ISO-8859-1. We need to
		 * tell the DOMDocument to use UTF-8.
		 */
		$doc->loadHTML( '<?xml encoding="utf-8" ?>' . $block_content );

		/**
		 * Get the block element.
		 *
		 * @var \DOMNode|\DOMElement $block_elem
		 */
		$block_elem = $doc->getElementsByTagName( 'body' )->item( 0 )->childNodes->item( 0 );

		/**
		 * Get the image element.
		 *
		 * @var \DOMNode|\DOMElement $image
		 */
		$block_image = $block_elem->getElementsByTagName( 'img' )->item( 0 );

		/**
		 * Initialize the DOMDocument for the new image.
		 */
		$img_doc = new \DOMDocument();

		/**
		 * Create a new image element.
		 */
		$new_image_html = wp_get_attachment_image( $block['attrs']['id'] ?? null, $block['attrs']['sizeSlug'] ?? 'full' );

		/**
		 * If there is no image, we cannot do anything.
		 */
		if ( empty( $new_image_html ) ) {
			return $block_content;
		}

		/**
		 * Load the new image tag into the DOMDocument.
		 */
		$img_doc->loadHTML( '<?xml encoding="utf-8" ?>' . $new_image_html );

		/**
		 * Get the new image element.
		 *
		 * @var \DOMNode|\DOMElement $new_image
		 */
		$new_image = $img_doc->getElementsByTagName( 'body' )->item( 0 )->childNodes->item( 0 );

		/**
		 * Make sure that manual resizing is not overwritten.
		 */
		if ( $is_resized ) {
			$new_image->setAttribute( 'width', $block['attrs']['width'] );
			$new_image->setAttribute( 'height', $block['attrs']['height'] );
		}

		/**
		 * Import the new image element into the block DOMDocument.
		 */
		$imported_image = $doc->importNode( $new_image, true );

		/**
		 * Replace the block image with the new image.
		 */
		$block_image->parentNode->replaceChild( $imported_image, $block_image ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		/**
		 * Get the breadcrumbs block content.
		 *
		 * @var string $block_content
		 */
		$content = $doc->saveHTML( $block_elem );

		return $content;
	}
}
