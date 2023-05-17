/**
 * WordPress dependencies.
 */
import { useBlockProps } from '@wordpress/block-editor';
import { withSelect } from '@wordpress/data';

/**
 * External dependencies.
 */
import classnames from 'classnames';

const Edit = (props) => {
	const { className, postContent } = props;

	// An array of the citations in the post content.
	const citations = [];

	// Parse the post content into a document tree.
	const parsed = new window.DOMParser().parseFromString(
		postContent,
		'text/html'
	);

	// Find all elements with the class 'js--wpm-format-cite'.
	const rawCitations = parsed.querySelectorAll('.js--wpm-format-cite');

	rawCitations.forEach((citation) => {
		// Get the citation from the 'data-cite-text' attribute.
		const citationText = citation.getAttribute('data-cite-text');

		citations.push(citationText);
	});

	/* Element classNames. */
	const blockClassNames = classnames(className, 'lh-bibliography-block', {});
	const blockProps = { ...useBlockProps({ className: blockClassNames }) };

	return (
		<div {...blockProps}>
			<ul className="lh-bibliography-block--citations">
				{citations.map((citation, index) => (
					<li
						key={`citation-${index}`}
						className="lh-bibliography-block--citation"
					>
						<a
							href={`#citation-${index + 1}`}
							className="lh-bibliography-block--citation-link"
						>
							[{index + 1}]
						</a>
						<span className="lh-bibliography-block--citation-text">
							{citation}
						</span>
					</li>
				))}
			</ul>
		</div>
	);
};

export default withSelect((select) => {
	return {
		postContent: select('core/editor').getEditedPostContent(),
	};
})(Edit);
