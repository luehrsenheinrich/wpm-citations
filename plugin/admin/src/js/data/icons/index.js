/**
 * Wordpress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * External dependencies.
 */
import { sortBy, unionBy } from 'lodash';

/**
 * Export the store key.
 */
export const STORE_KEY = 'lh/icons';

/**
 * Store actions.
 *
 * Init as own object since resolvers use them as well.
 */
const actions = {
	setIcons(icons, args) {
		return {
			type: 'SET_ICONS',
			icons,
			args,
		};
	},
	fetchFromAPI(path) {
		return {
			type: 'FETCH_FROM_API',
			path,
		};
	},
};

/**
 * Export the store config.
 */
export const STORE_CONFIG = {
	actions,
	controls: {
		FETCH_FROM_API(action) {
			return apiFetch({ path: action.path });
		},
	},
	reducer(state = { icons: [] }, action) {
		switch (action.type) {
			case 'SET_ICONS':
				// Return a copy of a set of arrays with unique elements.
				// This is due to getIcon() and getIcons() consuming state.icons.
				const newState = {
					...state,
					icons: [
						...sortBy(unionBy(state.icons, action.icons, 'slug'), [
							'slug',
						]),
					],
				};
				return newState;
		}
		return state;
	},
	resolvers: {
		*getIcon(slug) {
			const icon = yield actions.fetchFromAPI(`/lhpbpp/v1/icon/${slug}`);
			// Make use of setIcons by adding a single icon within an array.
			return actions.setIcons([icon]);
		},
		*getIcons(path = '/lhpbpp/v1/icons') {
			const icons = yield actions.fetchFromAPI(path);
			return actions.setIcons(icons);
		},
	},
	selectors: {
		getIcon(state, slug) {
			// Get a single icon from the store.
			const filteredIcons = state.icons.filter(
				(icon) => icon.slug === slug
			);
			// Return the first match or empty object if no icon was found.
			return filteredIcons[0] || {};
		},
		getIcons(state) {
			return state.icons;
		},
	},
};
