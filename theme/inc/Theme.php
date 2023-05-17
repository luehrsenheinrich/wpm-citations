<?php
/**
 * LHPBPT\Theme class
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

/**
 * Main class for the theme.
 * This is the main container for all theme components. It is used to access
 * the components and to provide some basic information about the theme.
 */
class Theme {

	/**
	 * The i18n component.
	 *
	 * @var i18n\I18N
	 */
	protected $i18n;

	/**
	 * The Nav Menus component.
	 *
	 * @var Nav_Menus\Nav_Menus
	 */
	protected $nav_menus;

	/**
	 * Scripts component.
	 *
	 * @var Scripts\Scripts
	 */
	protected $scripts;

	/**
	 * Styles component.
	 *
	 * @var Styles\Styles
	 */
	protected $styles;

	/**
	 * Theme Supports component.
	 *
	 * @var Theme_Supports\Theme_Supports
	 */
	protected $theme_supports;

	/**
	 * FSE component.
	 *
	 * @var FSE\FSE
	 */
	protected $fse;

	/**
	 * Lazysizes component.
	 *
	 * @var Lazysizes\Lazysizes
	 */
	protected $lazysizes;

	/**
	 * Block patterns component.
	 *
	 * @var Block_Patterns\Block_Patterns
	 */
	protected $block_patterns;

	/**
	 * Constructor.
	 *
	 * @param i18n\I18N                     $i18n I18N component.
	 * @param Nav_Menus\Nav_Menus           $nav_menus Nav_Menus component.
	 * @param Scripts\Scripts               $scripts Scripts component.
	 * @param Styles\Styles                 $styles Styles component.
	 * @param Theme_Supports\Theme_Supports $theme_supports Theme_Supports component.
	 * @param FSE\FSE                       $fse FSE component.
	 * @param Lazysizes\Lazysizes           $lazysizes Lazysizes component.
	 * @param Block_Patterns\Block_Patterns $block_patterns Block_Patterns component.
	 */
	public function __construct(
		i18n\I18N $i18n,
		Nav_Menus\Nav_Menus $nav_menus,
		Scripts\Scripts $scripts,
		Styles\Styles $styles,
		Theme_Supports\Theme_Supports $theme_supports,
		FSE\FSE $fse,
		Lazysizes\Lazysizes $lazysizes,
		Block_Patterns\Block_Patterns $block_patterns
	) {
		$this->i18n           = $i18n;
		$this->nav_menus      = $nav_menus;
		$this->scripts        = $scripts;
		$this->styles         = $styles;
		$this->theme_supports = $theme_supports;
		$this->fse            = $fse;
		$this->lazysizes      = $lazysizes;
		$this->block_patterns = $block_patterns;
	}

	/**
	 * Get the Nav Menus component.
	 *
	 * @return Nav_Menus\Nav_Menus Nav Menus component.
	 */
	public function nav_menus() {
		return $this->nav_menus;
	}

	/**
	 * Get the Scripts component.
	 *
	 * @return Scripts\Scripts Scripts component.
	 */
	public function scripts() {
		return $this->scripts;
	}

	/**
	 * Get the Styles component.
	 *
	 * @return Styles\Styles Styles component.
	 */
	public function styles() {
		return $this->styles;
	}

	/**
	 * Get the current theme slug.
	 *
	 * @return string Theme slug.
	 */
	public function get_theme_slug() {
		return 'lhpbpt';
	}

	/**
	 * Get the current theme version.
	 *
	 * @return string Theme version.
	 */
	public function get_theme_version() {
		return wp_get_theme()->get( 'Version' );
	}
}
