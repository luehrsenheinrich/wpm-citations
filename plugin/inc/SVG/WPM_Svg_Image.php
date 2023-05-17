<?php
/**
 * The class that holds one svg image.
 *
 * @package lhpbpp/SVG
 */

namespace WpMunich\lhpbpp\SVG;
use \DOMDocument;

defined( 'ABSPATH' ) || exit;

/**
 * Class that handles one svg image.
 */
class WPM_Svg_Image {

	/**
	 * The DOMDocument representation of the SVG.
	 *
	 * @var DOMDocument
	 */
	private $dom_document;

	/**
	 * The SVG DOMElement.
	 *
	 * @var DOMElement
	 */
	private $svg;

	/**
	 * An array of html attributes that are applied to the svg tag.
	 *
	 * @var array
	 */
	private $attributes = array();

	/**
	 * An array of allowed svg attributes.
	 *
	 * @var array
	 */
	private $allowed_attributes = array( 'class', 'id', 'width', 'height', 'fill', 'role' );

	/**
	 * The return type for the render function.
	 *
	 * @var string
	 */
	private $return_type = 'tag';

	/**
	 * The class constructor.
	 *
	 * @param array $args An array of arguments for the SVG class.
	 *                    [
	 *                      'svg_path'    => (string) The absolute path to the svg image file.
	 *                      'attributes'  => (array) An array of HTML attributes that are applied to the SVG tag.
	 *                      'return_type' => (string) Can be one of 'tag', 'base64'. Defaults to 'tag'.
	 *                    ].
	 */
	public function __construct( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'svg_path'    => false,
				'attributes'  => array(),
				'return_type' => 'tag',
			)
		);

		$this->attributes  = $this->parse_attributes( $args['attributes'] );
		$this->return_type = $args['return_type'];

		if ( $args['svg_path'] ) {
			$this->load( $args['svg_path'] );
		}
	}

	/**
	 * Load the svg and store it as a DOMDocument.
	 *
	 * @param  string $svg_path The absolute path to the SVG.
	 *
	 * @return boolean          True on success, false on failure
	 */
	public function load( $svg_path ) {
		if ( ! file_exists( $svg_path ) ) {
			return false;
		}

		if ( ! in_array( mime_content_type( $svg_path ), array( 'image/svg', 'image/svg+xml' ), true ) ) {
			return false;
		}

		$this->dom_document = new DOMDocument();
		$this->dom_document->loadXML( file_get_contents( $svg_path ) );
		$this->svg = $this->dom_document->getElementsByTagName( 'svg' )->item( 0 );

		return true;
	}

	/**
	 * Render the SVG tag.
	 *
	 * @return string The SVG HTML tag.
	 */
	public function render() {
		if ( ! $this->svg instanceof \DOMElement ) {
			return false;
		}

		$this->apply_attributes();
		$html = $this->svg->C14N();

		if ( $this->return_type === 'base64' ) {
			return 'data:image/svg+xml;base64,' . base64_encode( $html );
		}

		return $html;
	}

	/**
	 * Apply the parsed attributes to the SVG.
	 *
	 * @return void
	 */
	private function apply_attributes() {
		foreach ( $this->attributes as $key => $value ) {
			$this->svg->setAttribute( $key, $value );
		}
	}

	/**
	 * Parse attributes, so only valid and allowed attributes get applied.
	 *
	 * @param  array $attributes An array of HTML attributes.
	 *
	 * @return array             A parsed array of HTML attributes.
	 */
	private function parse_attributes( $attributes ) {
		$allowed_attributes = apply_filters( 'wpm_svg_allowed_attributes', $this->allowed_attributes );
		$parsed_attributes  = array();
		foreach ( $attributes as $key => $value ) {
			if ( in_array( $key, $allowed_attributes, true ) ) {
				$parsed_attributes[ $key ] = esc_attr( $value );
			}
		}

		return $parsed_attributes;
	}
}
