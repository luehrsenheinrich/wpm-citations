<?php
/**
 * LHPBPT\Component_Interface interface
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

/**
 * Interface for a component.
 */
abstract class Component {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Add the needed WordPress actions for the component.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 */
	abstract protected function add_actions();

	/**
	 * Add the needed WordPress filters for the component.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference
	 */
	abstract protected function add_filters();

	/**
	 * Get the parent class.
	 *
	 * @return Object The parent class.
	 */
	public function get_parent() {
		return get_parent_class( $this );
	}
}
