/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * This HOC add's a string as "branding" at the top of the sidebar of a selected block.
 *
 * @param {Function} BlockEdit The blocks edit component.
 * @return {Function} Wrapped block edit.
 */
const withBranding = (BlockEdit) => {
	return (props) => (
		<>
			<BlockEdit {...props} />
			<InspectorControls>
				<PanelBody>
					<span className="lh-block-branding">
						{__('This block is made with ♥ by ', 'lhpbpp')}
						<span>Luehrsen&nbsp;//&nbsp;Heinrich</span>
					</span>
				</PanelBody>
			</InspectorControls>
		</>
	);
};

export default withBranding;
