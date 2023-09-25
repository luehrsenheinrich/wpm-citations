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
		 * Use regex to find all span tags with the class js--wpm-format-cite.
		 * The class attribute can be anywhere in the tag. Catch the whole tag.
		 */
		$pattern = '/<span[^>]*class=[\'"]*js--wpm-format-cite[\'"]*[^>]*>(.*?)<\/span>/i';
		$content = preg_replace_callback( $pattern, array( $this, 'replace_citation' ), $content );

		return $content;
	}

	/**
	 * Replaces the citation with the bibliography.
	 *
	 * @param array $match The match.
	 *
	 * @return string
	 */
	private function replace_citation( $match ) {
		$whole_tag   = $match[0];
		$tag_content = $match[1];

		/**
		 * The number of citations we have so far.
		 */
		$citation_count = count( $this->citations );

		/**
		 * The human readable citation number. (We start with 1)
		 */
		$c = $citation_count + 1;

		/**
		 * Compile the citation link, that links to the bibliography.
		 */
		$citations_tag = '<a href="#wpm-citation-source-' . $c . '" id="wpm-citation-' . $c . '" class="js--wpm-format-cite-link">[' . $c . ']</a>';

		/**
		 * Use regex to fetch the 'data-cite-text' attribute from the tag.
		 * The attribute can have single quotes.
		 */
		$pattern = '/data-cite-text="([^"]*?)"/i';
		preg_match( $pattern, $whole_tag, $matches );
		if ( ! empty( $matches[1] ) ) {
			$citation = $matches[1];
		}

		/**
		 * Add the citation to the citations array.
		 */
		$this->citations[] = $citation;

		return str_replace( $tag_content, $tag_content . $citations_tag, $whole_tag );
	}
}
