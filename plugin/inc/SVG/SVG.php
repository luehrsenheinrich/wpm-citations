<?php
/**
 * LHPBPP\SVG\Component class
 *
 * @package lhpbpp
 */

namespace WpMunich\lhpbpp\SVG;
use WpMunich\lhpbpp\Component;
use function WpMunich\lhpbpp\lh_plugin;

/**
 * The Component
 */
class SVG extends Component {

	/**
	 * Library of icons supported by the theme.
	 *
	 * @var Icon_Library
	 */
	private $icon_library;

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_html' ), 10, 2 );
	}

	/**
	 * Returns the supported block library.
	 *
	 * @return Icon_library The icon library
	 */
	public function get_icon_library() {
		if ( ! $this->icon_library instanceof Icon_Library ) {
			$base_path = lh_plugin()->get_plugin_path();

			$this->icon_library = new Icon_Library();
			$this->icon_library->set_icons(
				array(
					new Icon(
						$base_path . 'img/icons/slashes.svg',
						'slashes',
						__( 'Slashes', 'lhpbpp' ),
					),
					new Icon(
						$base_path . 'img/icons/chevron--left.svg',
						'chevron--left',
						__( 'Chevron Left', 'lhpbpp' ),
					),
					new Icon(
						$base_path . 'img/icons/chevron--right.svg',
						'chevron--right',
						__( 'Chevron Right', 'lhpbpp' ),
					),
				)
			);
		}
		return $this->icon_library;
	}

	/**
	 * Get an SVG from the theme or plugin folder.
	 *
	 * @param string $pointer The SVG slug or path to be loaded. The path is relative to the theme or plugin folder.
	 *                        It first checks if the SVG is in the theme folder and falls back to the plugin folder.
	 * @param array  $args    An array of arguments for the SVG class. See the WP_SVG_Image class constructor for more details.
	 *
	 * @return string The SVG code.
	 */
	public function get_svg( string $pointer, $args = array() ) {
		$final_path = get_template_directory() . $pointer;
		$icon_lib   = $this->get_icon_library();

		switch ( $pointer ) {
			case ( $icon_lib->exists( $pointer ) ):
				$final_path = $icon_lib->get_path( $pointer );
				break;
			case ( file_exists( get_template_directory() . $pointer ) ):
				$final_path = get_template_directory() . $pointer;
				break;
			case ( file_exists( lh_plugin()->get_plugin_path() . $pointer ) ):
				$final_path = lh_plugin()->get_plugin_path() . $pointer;
				break;
			default:
				return false;
				break;
		}

		if ( ! file_exists( $final_path ) ) {
			return false;
		}

		if ( ! in_array( mime_content_type( $final_path ), array( 'image/svg', 'image/svg+xml' ), true ) ) {
			return false;
		}

		$args['svg_path'] = $final_path;

		$svg = new WPM_Svg_Image( $args );

		return $svg->render();
	}

	/**
	 * Get an SVG icon for use in WP Admin Menus.
	 *
	 * @param  string $path The relative path of the image to the plugin / theme root.
	 *
	 * @return string       The base64 encoded svg.
	 */
	public function get_admin_menu_icon( $path ) {
		$args = array(
			'return_type' => 'base64',
			'attributes'  => array(
				'fill'   => '#a0a5aa',
				'width'  => '20',
				'height' => '20',
			),
		);

		return $this->get_svg( $path, $args );
	}

	/**
	 * Adds allowed HTML tags to the allowed tags array.
	 *
	 * @param array  $allowed_tags The allowed tags.
	 * @param string $context The context.
	 *
	 * @return array The allowed tags.
	 */
	public function allowed_html( $allowed_tags, $context ) {
		$allowed_tags['svg'] = array(
			'class'                                => true,
			'xmlns'                                => true,
			'width'                                => true,
			'height'                               => true,
			'viewbox'                              => true,
			'role'                                 => true,
			'preserveaspectratio'                  => true,
			'preserveaspectratio.align'            => true,
			'preserveaspectratio.meetorslice'      => true,
			'preserveaspectratio.slice'            => true,
			'preserveaspectratio.slice.fill'       => true,
			'preserveaspectratio.slice.fill.meet'  => true,
			'preserveaspectratio.slice.fill.slice' => true,
			'preserveaspectratio.slice.meet'       => true,
			'preserveaspectratio.x'                => true,
			'preserveaspectratio.y'                => true,
			'preserveaspectratio.align'            => true,
			'preserveaspectratio.meetorslice'      => true,
			'preserveaspectratio.slice'            => true,
			'preserveaspectratio.slice.fill'       => true,
			'preserveaspectratio.slice.fill.meet'  => true,
			'preserveaspectratio.slice.fill.slice' => true,
			'preserveaspectratio.slice.meet'       => true,
			'preserveaspectratio.x'                => true,
			'preserveaspectratio.y'                => true,
			'preserveaspectratio.align'            => true,
			'preserveaspectratio.meetorslice'      => true,
			'preserveaspectratio.slice'            => true,
			'preserveaspectratio.slice.fill'       => true,
			'preserveaspectratio.slice.fill.meet'  => true,
		);

		$allowed_tags['path'] = array(
			'd'                 => true,
			'fill'              => true,
			'fill-rule'         => true,
			'fill-opacity'      => true,
			'stroke'            => true,
			'stroke-width'      => true,
			'stroke-linecap'    => true,
			'stroke-linejoin'   => true,
			'stroke-miterlimit' => true,
			'stroke-dasharray'  => true,
			'stroke-dashoffset' => true,
			'stroke-opacity'    => true,
		);

		$allowed_tags['g'] = array(
			'fill'              => true,
			'fill-rule'         => true,
			'fill-opacity'      => true,
			'stroke'            => true,
			'stroke-width'      => true,
			'stroke-linecap'    => true,
			'stroke-linejoin'   => true,
			'stroke-miterlimit' => true,
			'stroke-dasharray'  => true,
			'stroke-dashoffset' => true,
			'stroke-opacity'    => true,
		);

		$allowed_tags['circle'] = array(
			'cx'             => true,
			'cy'             => true,
			'r'              => true,
			'fill'           => true,
			'fill-opacity'   => true,
			'stroke'         => true,
			'stroke-width'   => true,
			'stroke-opacity' => true,
		);

		$allowed_tags['animate'] = array(
			'attributename' => true,
			'begin'         => true,
			'dur'           => true,
			'end'           => true,
			'values'        => true,
			'calcmode'      => true,
			'repeatcount'   => true,
		);

		return $allowed_tags;
	}
}
