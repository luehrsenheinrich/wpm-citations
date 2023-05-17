/**
 * WordPress dependencies.
 */
import { createReduxStore, register } from '@wordpress/data';

/**
 * Import stores keys and configs.
 */
import {
	STORE_KEY as ICONS_STORE_KEY,
	STORE_CONFIG as ICONS_STORE_CONFIG,
} from './icons';

const ICON_STORE = createReduxStore(ICONS_STORE_KEY, ICONS_STORE_CONFIG);

// Register our stores.
register(ICON_STORE);

// Export store keys.
export { ICONS_STORE_KEY };
