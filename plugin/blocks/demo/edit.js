/**
 * WordPress dependencies.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import IconSelectControl from '../../admin/src/js/components/icon-select-control';
import Icon from '../../admin/src/js/components/icon';
import PostSelectControl from '../../admin/src/js/components/post-select-control';

const Edit = (props) => {
	const { attributes, setAttributes } = props;
	const { icon, post, postSingle } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'lhpbpp')}>
					<p>{__('This is a demo block.', 'lhpbpp')}</p>
					<IconSelectControl
						value={icon}
						onChange={(value) => setAttributes({ icon: value })}
					/>
					<PostSelectControl
						value={post}
						onChange={(value) => setAttributes({ post: value })}
					/>
					<PostSelectControl
						value={postSingle}
						onChange={(value) =>
							setAttributes({ postSingle: value })
						}
						multiple={false}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				{icon && (
					<div className="icon">
						<Icon slug={icon} />
					</div>
				)}
				<p>{__('This is a demo block.', 'lhpbpp')}</p>
			</div>
		</>
	);
};

export default Edit;
