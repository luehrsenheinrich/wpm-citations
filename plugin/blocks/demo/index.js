/**
 * A dynamic block for the Gutenberg editor. The edit.js file is used to
 * define the editor interface of the block. The rendering of the
 * frontend component happens in PHP.
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * The block metadata.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 */
import metadata from './block.json';

/**
 * The block edit function.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 */
import withBranding from '../../admin/src/js/hoc/with-branding';
import edit from './edit';
import icon from './icon.svg';

const settings = {
	edit: withBranding(edit),
	icon,

	/**
	 * The save function returns null because the output is generated
	 * within php.
	 *
	 * @return {null} The save function returns null.
	 */
	save: () => null,
};

/**
 * Actually register the block.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration/
 */
registerBlockType(metadata, settings);
