<?php
/**
 * The `lh_plugin()` function.
 *
 * @package citations
 */

namespace WpMunich\citations;

/**
 * Provides access to all available functions of the plugin.
 *
 * When called for the first time, the function will initialize the plugin.
 *
 * @return Plugin The main plugin component.
 */
function lh_plugin() {
	static $plugin = null;

	if ( null === $plugin ) {
		$plugin = new Plugin();
	}

	return $plugin;
}

// Ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase for this function.
// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase, WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

/**
 * A php implementation of the classNames function from the classnames npm package.
 *
 * @return string The generated class names.
 */
function classNames() {
	$args = func_get_args();

	$data = array_reduce(
		$args,
		function ( $carry, $arg ) {
			if ( is_array( $arg ) ) {
				return array_merge( $carry, $arg );
			}

			$carry[] = $arg;
			return $carry;
		},
		array()
	);

	$classes = array_map(
		function ( $key, $value ) {
			$condition = $value;
			$return    = $key;

			if ( is_int( $key ) ) {
				$condition = null;
				$return    = $value;
			}

			$isArray          = is_array( $return );
			$isObject         = is_object( $return );
			$isStringableType = ! $isArray && ! $isObject;

			$isStringableObject = $isObject && method_exists( $return, '__toString' );

			if ( ! $isStringableType && ! $isStringableObject ) {
				return null;
			}

			if ( $condition === null ) {
				return $return;
			}

			return $condition ? $return : null;

		},
		array_keys( $data ),
		array_values( $data )
	);

	$classes = array_filter( $classes );

	return implode( ' ', $classes );
}
