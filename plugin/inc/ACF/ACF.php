<?php
/**
 * Lhplugin\ACF\Component class
 *
 * @package lhpbpp
 */

namespace WpMunich\lhpbpp\ACF;
use WpMunich\lhpbpp\Component;
use function add_action;
use function wp_get_environment_type;
use function acf_add_options_page;
use function WpMunich\lhpbpp\lh_plugin;

/**
 * A class to handle acf related logic..
 */
class ACF extends Component {
	/**
	 * {@inheritDoc}
	 */
	protected function add_actions() {
		add_action( 'acf/init', array( $this, 'add_options_page' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function add_filters() {
		if ( wp_get_environment_type() === 'development' && defined( 'LH_CURRENTLY_EDITING' ) && LH_CURRENTLY_EDITING === 'lhpbp' ) {
			add_filter( 'acf/settings/save_json', array( $this, 'acf_json_save_point' ) );
		}

		add_filter( 'acf/settings/load_json', array( $this, 'acf_json_load_point' ) );
	}

	/**
	 * Add the json save point for acf.
	 *
	 * @param  string $path Save path.
	 *
	 * @return string       Save path.
	 */
	public function acf_json_save_point( $path ) {
		$path = lh_plugin()->get_plugin_path() . 'acf-json';
		return $path;
	}

	/**
	 * Add the json load point for acf.
	 *
	 * @param  array $paths An array of paths.
	 *
	 * @return array        An array of paths.
	 */
	public function acf_json_load_point( $paths ) {
		$paths[] = lh_plugin()->get_plugin_path() . 'acf-json';

		return $paths;
	}

	/**
	 * Add a theme options page.
	 */
	public function add_options_page() {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		$option_page = acf_add_options_page(
			array(
				'page_title' => __( 'L//H Settings', 'lhpbpp' ),
				'menu_title' => __( 'L//H Settings', 'lhpbpp' ),
				'menu_slug'  => 'lhpbpp-plugin-general-settings',
				'icon_url'   => lh_plugin()->svg()->get_admin_menu_icon( 'img/icons/slashes.svg' ),
				'capability' => 'edit_posts',
				'redirect'   => false,
			)
		);
	}
}
