/**
 * WordPress dependencies.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

const Edit = () => {
	return (
		<>
			<InspectorControls>
				<p>Test</p>
			</InspectorControls>
			<div {...useBlockProps()}>
				<p>{__('This is a demo block.', 'citations')}</p>
			</div>
		</>
	);
};

export default Edit;
