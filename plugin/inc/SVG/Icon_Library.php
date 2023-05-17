<?php
/**
 * The class to represent the theme's icon library.
 *
 * @package lhpbpp/SVG
 */

namespace WpMunich\lhpbpp\SVG;

/**
 * Icon Library Class.
 */
class Icon_Library {
	/**
	 * The single icons from our icon library.
	 *
	 * @var Icon[] The icon object.
	 */
	protected $icons = array();

	/**
	 * Check if an icon to a given slug is registered in our lib.
	 *
	 * @param  string $slug The icon's slug.
	 * @return boolean      True if the item exists, false otherwise.
	 */
	public function exists( $slug ) {
		$icons = $this->get_icons();
		return isset( $icons[ $slug ] );
	}

	/**
	 * Get the path of an icon by given slug.
	 *
	 * @param  string $slug The icon's slug.
	 * @return mixed        The icon's slug if it exists, null otherwise.
	 */
	public function get_path( $slug ) {
		if ( $this->exists( $slug ) ) {
			$icon = $this->get_icon( $slug );
			return $icon->get_path();
		}
		return null;
	}

	/**
	 * Get all registered icons.
	 *
	 * @return array Array of icons.
	 */
	public function get_icons() {
		return $this->icons;
	}

	/**
	 * Get a single icon by slug.
	 *
	 * @param  string $slug The icon's slug.
	 * @return mixed        The requests icon if it exists, null otherwise.
	 */
	public function get_icon( string $slug ) {
		if ( $this->exists( $slug ) ) {
			return $this->icons[ $slug ];
		}
		return null;
	}

	/**
	 * Set a given collection of icons.
	 *
	 * @param  Icon[] $icons The icon's to set.
	 * @return void
	 */
	public function set_icons( $icons = array() ) {
		$this->icons = array();
		$this->add_icons( $icons );
	}

	/**
	 * Add a given collection of icons.
	 *
	 * @param  Icon[] $icons The icon's to add.
	 * @return void
	 */
	public function add_icons( $icons = array() ) {
		foreach ( $icons as $icon ) {
			$this->add_icon( $icon );
		}
	}

	/**
	 * Add a single icon.
	 *
	 * @param  Icon $icon The icon to add.
	 * @return void
	 */
	public function add_icon( Icon $icon ) {
		$this->icons[ $icon->get_slug() ] = $icon;
	}
}
