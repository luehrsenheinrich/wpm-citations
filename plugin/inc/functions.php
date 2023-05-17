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
