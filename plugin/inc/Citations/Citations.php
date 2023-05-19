<?php
/**
 * The file that handles the citations logic.
 *
 * @package citations
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

namespace WpMunich\citations\Citations;

/**
 * The class that handles the citations logic.
 */
class Citations {

	/**
	 * An array of citations for this page request.
	 *
	 * @var array
	 */
	protected $citations = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'filter_the_content' ), 8 );
	}

	/**
	 * Get the citations.
	 *
	 * @return array The citations.
	 */
	public function get_citations() {
		return $this->citations;
	}

	/**
	 * Filter the content.
	 *
	 * @param string $content The content.
	 *
	 * @return string The filtered content.
	 */
	public function filter_the_content( $content ) {
		// Only filter on singular pages.
		if ( ! is_singular() ) {
			return $content;
		}

		// only filter if we have the citations present.
		if ( false === strpos( $content, 'js--wpm-format-cite' ) ) {
			return $content;
		}

		// only flter if the bibliography block is present.
		if ( ! has_block( 'lh/bibliography' ) ) {
			return $content;
		}

		return $this->prepare_bibliography( $content );
	}

	/**
	 * Prepares the bibliography.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	private function prepare_bibliography( $content = null ) {
		if ( ! $content ) {
			$content = get_post_field( 'post_content' );
		}

		if ( ! empty( $this->citations ) ) {
			return $content;
		}

		/**
		 * Initialize the DOMDocument.
		 */
		$doc = new \DOMDocument();

		/**
		 * Load the block content into a DOMDocument.
		 * We cannot just load the block content as is, as it will be cast as ISO-8859-1. We need to
		 * tell the DOMDocument to use UTF-8.
		 */
		libxml_use_internal_errors( true );
		$doc->loadHTML( '<?xml encoding="utf-8" ?>' . $content );
		libxml_clear_errors();

		$doc->removeChild( $doc->childNodes->item( 1 ) );

		$finder = new \DOMXPath( $doc );

		/**
		 * Find all the citation elements.
		 */
		$citations = $finder->query( '//*[@class="js--wpm-format-cite"]' );

		$citation_link = $doc->createElement( 'a' );
		$citation_link->setAttribute( 'class', 'js--wpm-format-cite-link' );
		$citation_link->setAttribute( 'href', '#' );

		/**
		 * Loop through all the citation elements.
		 */
		foreach ( $citations as $citation ) {
			$specific_link = $citation_link->cloneNode( true );
			$specific_link->setAttribute( 'id', 'wpm-citation-' . ( count( $this->citations ) + 1 ) );
			$specific_link->setAttribute( 'href', '#wpm-citation-source-' . ( count( $this->citations ) + 1 ) );

			// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$specific_link->nodeValue = '[' . ( count( $this->citations ) + 1 ) . ']';

			$citation->appendChild( $specific_link );
			$this->citations[] = $citation->attributes->getNamedItem( 'data-cite-text' )->nodeValue;
		}

		return $doc->saveHTML();
	}
}
