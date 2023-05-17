<?php
/**
 * LHPBPP\REST\Component class
 *
 * @package lhpbpp
 */

namespace WpMunich\lhpbpp\REST;

use WpMunich\lhpbpp\Component;
use \WP_REST_Server;
use function WpMunich\lhpbpp\lh_plugin;
use function add_action;
use function apply_filters;
use function esc_attr;
use function register_rest_route;
use function rest_ensure_response;

/**
 * A class to register custom REST endpoints.
 */
class REST extends Component {
	/**
	 * The namespace for REST endpoints in this component.
	 *
	 * @var string
	 */
	private $rest_namespace = 'lhpbpp/v1';

	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {}

	/**
	 * Register the needed rest routes for this component.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		// Icons.
		register_rest_route(
			$this->rest_namespace,
			'icons',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icons' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			$this->rest_namespace,
			'icon(?:/(?<slug>[a-z0-9-]+))?',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'rest_get_icon' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'slug' => array(
						'type' => 'string',
					),
				),
			)
		);
	}

	/**
	 * Return all icons from the library via REST.
	 *
	 * @param  WP_REST_Request $request The request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function rest_get_icons( $request ) {
		$slugs     = $request->get_param( 'slugs' );
		$lib_icons = lh_plugin()->svg()->get_icon_library()->get_icons();
		$res_icons = array();

		// Slugs param is expected to be a comma separated value string.
		if ( $slugs && ! empty( $slugs ) ) {
			$slugs = explode( ',', $slugs );
		}

		foreach ( $lib_icons as $icon ) {
			if ( $icon->show_in_rest() ) {
				if ( is_array( $slugs ) && ! in_array( $icon->get_slug(), $slugs, true ) ) {
					continue;
				}

				$res_icons[] = wp_parse_args(
					$icon->jsonSerialize( array( 'slug', 'title' ) ),
					array(
						'svg' => lh_plugin()->svg()->get_svg( $icon->get_slug() ),
					)
				);
			}
		}
		return rest_ensure_response( $res_icons );
	}

	/**
	 * Return a single icon via REST by slug.
	 *
	 * @param  WP_REST_Request $request The request.
	 *
	 * @return WP_REST_Response         The respone.
	 */
	public function rest_get_icon( $request ) {
		$slug = $request->get_param( 'slug' );
		$path = $request->get_param( 'path' );
		$args = $this->get_args_from_request( $request );

		$svg = lh_plugin()->svg()->get_svg( $slug );

		if ( ! $svg && $path && ! empty( $path ) ) {
			$svg = lh_plugin()->svg()->get_svg( $path );
		}

		$icon = $slug && $svg ? lh_plugin()->svg()->get_icon_library()->get_icon( $slug )->jsonSerialize( array( 'slug', 'title' ) ) : array();

		$response = apply_filters(
			'lhpbpp_rest_get_svg_response',
			wp_parse_args(
				$icon,
				array( 'svg' => $svg )
			),
			$slug,
			$args
		);

		return rest_ensure_response( $response );
	}

	/**
	 * Helper function to map flat request args to get_svg $args array.
	 *
	 * @param WP_REST_Request $request The request to check for params.
	 * @return array                   The $args array to use for $this->load.
	 */
	private function get_args_from_request( $request ) {
		$args = array();
		$attr = array();

		if ( isset( $request['class'] ) ) {
			$attr['class'] = esc_attr( $request['class'] );
		}
		if ( isset( $request['id'] ) ) {
			$attr['id'] = esc_attr( $request['id'] );
		}
		if ( isset( $request['width'] ) ) {
			$attr['width'] = esc_attr( $request['width'] );
		}
		if ( isset( $request['height'] ) ) {
			$attr['height'] = esc_attr( $request['height'] );
		}
		if ( isset( $request['fill'] ) ) {
			$attr['fill'] = esc_attr( $request['fill'] );
		}

		// Merge $attr to $args['attributes'] if any are set.
		if ( count( $attr ) ) {
			$args['attributes'] = $attr;
		}

		return $args;
	}

	/**
	 * Get the current REST namespace.
	 *
	 * @return string The namespace.
	 */
	public function get_namespace() {
		return $this->rest_namespace;
	}
}
