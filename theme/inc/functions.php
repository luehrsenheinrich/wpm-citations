<?php
/**
 * The `lh_theme()` function.
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

/**
 * Provides access to the business logic of the theme.
 *
 * When called for the first time, the function will initialize the theme.
 *
 * @return Theme The main theme component.
 */
function lh_theme() {

	/**
	 * Check if the requirements for the current theme are met.
	 * If the requirements are not met, we might get severe errors. Therefore, we
	 * return null and do not initialize the theme.
	 */
	if ( ! theme_requirements_are_met() ) {
		return null;
	}

	static $theme = null;

	if ( null === $theme ) {
		$builder   = new \DI\ContainerBuilder();
		$container = $builder->build();

		$theme = $container->get( Theme::class );
	}

	return $theme;
}

/**
 * Check if the requirements for the current theme are met.
 *
 * @return bool True if requirements are met, false otherwise.
 */
function theme_requirements_are_met() {
	/**
	 * The accompanying plugin is required.
	 */
	if ( ! function_exists( '\WPMunich\lhpbpp\lh_plugin' ) || \WPMunich\lhpbpp\lh_plugin() === null ) {
		return false;
	}

	/**
	 * The Advanced Custom Fields plugin is required.
	 */
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	return true;
}

/**
 * Display a template if the requirements are not met.
 */
function requirements_template() {
	if ( ! theme_requirements_are_met() ) {
		wp_die( 'The requirements for this theme are not met.' );
	}
}
add_action( 'template_redirect', '\WpMunich\lhpbpt\requirements_template' );

/**
 * Display an admin notice if the requirements are not met.
 */
function theme_requirements_notice__error() {
	if ( theme_requirements_are_met() ) {
		return;
	}

	$class   = 'notice notice-error';
	$message = 'The requirements for this theme are not met.';

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}
add_action( 'admin_notices', '\WpMunich\lhpbpt\theme_requirements_notice__error' );
