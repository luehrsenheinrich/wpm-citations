<?php
/**
 * LHPBPT\Nav_Menus\Component class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt\Nav_Menus;
use WpMunich\lhpbpt\Component;
use function add_action;
use function register_nav_menus;
use function esc_html__;
use function has_nav_menu;
use function wp_nav_menu;

/**
 * Class for managing navigation menus.
 */
class Nav_Menus extends Component {
	/**
	 * Associative array of theme navigations, keyed by their slug.
	 *
	 * @var array
	 */
	protected $nav_menu_list = array();

	/**
	 * Constructor function to populate theme vars.
	 */
	public function __construct() {
		$this->add_actions();
		$this->add_filters();

		$this->nav_menu_list = array(
			'header' => esc_html__( 'Header', 'lhpbpt' ),
			'footer' => esc_html__( 'Footer', 'lhpbpt' ),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function add_actions() {}

	/**
	 * {@inheritdoc}
	 */
	protected function add_filters() {}

	/**
	 * Registers the navigation menus.
	 */
	public function action_register_nav_menus() {
		register_nav_menus( $this->nav_menu_list );
	}

	/**
	 * Checks whether the primary navigation menu is active.
	 *
	 * @param string $slug The slug if the menu.
	 * @return bool True if the primary navigation menu is active, false otherwise.
	 */
	public function is_nav_menu_active( $slug ) {
		if ( ! isset( $this->nav_menu_list[ $slug ] ) ) {
			return false;
		}

		return (bool) has_nav_menu( $slug );
	}

	/**
	 * Displays the primary navigation menu.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_nav_menu()` documentation for a list of supported
	 *                    arguments.
	 */
	public function display_nav_menu( array $args = array() ) {
		// Return if no theme location is defined.
		if ( ! isset( $args['theme_location'] ) ) {
			return;
		}

		// Get the navs slug.
		$slug = $args['theme_location'];

		// Define defaults.
		$defaults = array(
			'container'       => 'nav',
			'container_class' => $slug . '-menu ' . $slug . '-menu--main',
			'menu_class'      => 'menu ' . $slug,
		);

		// Merge args with defaults.
		$args = wp_parse_args( $args, $defaults );

		// Output the nav.
		wp_nav_menu( $args );
	}

	/**
	 * Returns an <ul> with pagination links for a given query.
	 *
	 * @param  array $args An array of arguments.
	 *
	 * @return string       The list for the query links.
	 */
	public function paginate_links( array $args = array() ) {
		global $wp_query;
		$args = wp_parse_args(
			$args,
			array(
				'current'   => $wp_query->query_vars['paged'] ? (int) $wp_query->query_vars['paged'] : 1,
				'total'     => isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1,
			)
		);

		$range = $this->generate_range( $args['current'], $args['total'] );

		$html = '<ul class="page-numbers">';
		foreach ( $range as $p ) {
			$list_classnames = classnames(
				array(
					'delta-' . $p['delta'] => $p['delta'],
				)
			);

			if ( ! empty( $list_classnames ) ) {
				$list_class = 'class="' . $list_classnames . '"';
			} else {
				$list_class = null;
			}

			$html .= sprintf( '<li %s>', $list_class );
			if ( $p['current'] ) {
				$html .= '<span class="page-numbers current">';
				$html .= $p['page_number'];
				$html .= '</span>';
			} elseif ( $p['dots'] ) {
				$html .= '<span class="page-numbers dots">';
				$html .= '...';
				$html .= '</span>';
			} else {
				$html .= sprintf( '<a href="%s" class="page-numbers" data-page-target="%d">', get_pagenum_link( $p['page_number'] ), $p['page_number'] );
				$html .= $p['page_number'];
				$html .= '</a>';
			}
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Generate a range for which we display page numbers.
	 *
	 * @param  int $current The current page.
	 * @param  int $last    The maximum numbers of pages.
	 * @param  int $delta   The width of the pagination range.
	 *
	 * @return array        An array of page numbers.
	 */
	private function generate_range( $current, $last, $delta = 1 ) {

		/**
		 * Define some constants.
		 */
		$left            = intval( $current - $delta );
		$right           = intval( $current + $delta );
		$last            = intval( $last );
		$range           = array();
		$range_with_dots = array();

		for ( $i = 1; $i <= $last; $i++ ) {
			if ( $i === 1 || $i === $last || ( $i >= $left && $i <= $right ) ) {
				$range[] = $i;
			}
		}

		$l = null;
		foreach ( $range as $i ) {
			if ( $i === $last || $i === 1 ) {
				$delta = false;
			} else {
				$delta = abs( $i - $current );
			}

			if ( $l ) {
				if ( $i - $l === 2 ) {
					$range_with_dots[] = array(
						'page_number' => $l + 1,
						'current'     => $l === $current,
						'dots'        => false,
						'delta'       => $delta,
					);
				} elseif ( $i - $l !== 1 ) {
					$range_with_dots[] = array(
						'page_number' => null,
						'current'     => false,
						'dots'        => true,
						'delta'       => 0,
					);
				}
			}

			$range_with_dots[] = array(
				'page_number' => $i,
				'current'     => $i === $current,
				'dots'        => false,
				'delta'       => $delta,
			);

			$l = $i;
		}

		return $range_with_dots;
	}
}
