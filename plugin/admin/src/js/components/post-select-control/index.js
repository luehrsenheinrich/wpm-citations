/**
 * A control to select a set of posts.
 */

/**
 * WordPress dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import { PanelRow } from '@wordpress/components';

/**
 * External dependencies.
 */
import AsyncSelect from 'react-select/async';
import { DndContext } from '@dnd-kit/core';
import { restrictToParentElement } from '@dnd-kit/modifiers';
import {
	arrayMove,
	horizontalListSortingStrategy,
	SortableContext,
} from '@dnd-kit/sortable';

import {
	MultiValue,
	MultiValueRemove,
	MultiValueContainer,
} from './multi-value';

const PostSelectControl = ({
	value,
	onChange,
	endpoint = 'posts',
	query = {},
	label = '',
	help = '',
	multiple = true,
	max,
}) => {
	const [selectedOptions, setSelectedOptions] = useState([]);

	if (typeof label === 'undefined') {
		label = __('Select a post', 'jitmp');
	}

	/**
	 * Endpoint path.
	 *
	 * @type {string}
	 */
	const endpointPath = `/wp/v2/${endpoint}`;

	/**
	 * Handle a change in the selected option.
	 *
	 * @param {*} option The selected option
	 */
	const onSelectPost = (option) => {
		setSelectedOptions(option);

		if (!multiple) {
			onChange(option?.value);
		} else {
			onChange(option.map((o) => o.value));
		}
	};

	let defaultSelectedOptionValues = [];

	/**
	 * Iterate over the value and compose an array of selected values.
	 */
	if (multiple) {
		defaultSelectedOptionValues = value;
	} else {
		defaultSelectedOptionValues = value;
	}

	/**
	 * A set of options that are available by default.
	 *
	 * @type {Array}
	 */
	const defaultOptions = [];

	/**
	 * Load the default options. Must include the options that are already
	 * selected.
	 */
	const loadDefaultOptions = () => {
		let newSelectedOptions = [];

		// Check if selectedOptions is an array.
		if (Array.isArray(selectedOptions)) {
			newSelectedOptions = selectedOptions;
		} else {
			newSelectedOptions.push(selectedOptions);
		}

		return apiFetch({
			path: addQueryArgs(endpointPath, {
				per_page: 10,
				include: defaultSelectedOptionValues,
				...query,
			}),
		}).then((response) => {
			/**
			 * Iterate over the response and add the options to the default
			 * options.
			 */
			const responseOptions = response.map((post) => {
				const option = {
					value: post.id,
					// First: Post, Sec: search, Fallback: Taxonomy/Term.
					label: post?.title?.rendered || post?.title || post.name,
				};

				/**
				 * If this post is in the defaultSelectedOption, add it to the state.
				 */
				if (
					multiple &&
					defaultSelectedOptionValues?.includes(post.id)
				) {
					newSelectedOptions.push(option);
				} else if (post.id === defaultSelectedOptionValues) {
					newSelectedOptions.push(option);
				}

				defaultOptions.push(option);

				return option;
			});

			/**
			 * Update the state with the new options after we've completed
			 * iterating over the response.
			 */
			setSelectedOptions(newSelectedOptions);

			return responseOptions;
		});
	};

	const onSortEnd = (event) => {
		const { active, over } = event;

		if (!active || !over) return;

		// const sortItems = (items) => {
		// 	console.log({items});
		// 	const oldIndex = items.findIndex(
		// 		(item) => item.value === active.id
		// 	);
		// 	const newIndex = items.findIndex((item) => item.value === over.id);

		// 	return arrayMove(items, oldIndex, newIndex);
		// };

		const oldIndex = selectedOptions.findIndex(
			(item) => item.value === active.id
		);
		const newIndex = selectedOptions.findIndex(
			(item) => item.value === over.id
		);

		const sortedItems = arrayMove(selectedOptions, oldIndex, newIndex);

		setSelectedOptions(sortedItems);
		onChange(sortedItems);
	};

	useEffect(() => {
		if (!selectedOptions?.length) {
			loadDefaultOptions();
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [selectedOptions]);

	/**
	 * When the endpoint changes, we need to reset the selected options.
	 */
	useEffect(() => {
		setSelectedOptions([]);
		defaultOptions.length = 0;
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [endpoint]);

	/**
	 * Load the options for the select control from the API.
	 *
	 * @param {string} inputValue The search term.
	 * @return {Promise} The options to display.
	 */
	const loadOptions = (inputValue = null) => {
		return new Promise((resolve) => {
			return apiFetch({
				path: addQueryArgs(endpointPath, {
					per_page: 10,
					search: inputValue,
					...query,
				}),
			}).then((response) => {
				resolve(
					response.map((post) => ({
						value: post.id,
						// First: Post, Sec: search, Fallback: Taxonomy/Term.
						label:
							post?.title?.rendered || post?.title || post.name,
						post,
					}))
				);
			});
		});
	};

	return (
		<PanelRow className="post-type-select__row">
			<span className="post-type-select__label">{label}</span>
			{multiple && (
				<DndContext
					modifiers={[restrictToParentElement]}
					onDragEnd={onSortEnd}
				>
					<SortableContext
						items={selectedOptions.map((o) => o.value)}
						strategy={horizontalListSortingStrategy}
					>
						<AsyncSelect
							value={selectedOptions}
							onChange={onSelectPost}
							loadOptions={loadOptions}
							defaultOptions={true}
							className={'react-select'}
							classNamePrefix={'react-select'}
							isClearable
							isMulti
							isOptionDisabled={() => max && value?.length >= max}
							closeMenuOnSelect={false}
							components={{
								MultiValue,
								MultiValueContainer,
								MultiValueRemove,
							}}
						/>
					</SortableContext>
				</DndContext>
			)}
			{!multiple && (
				<AsyncSelect
					value={selectedOptions}
					onChange={onSelectPost}
					loadOptions={loadOptions}
					defaultOptions={true}
					className={'react-select'}
					classNamePrefix={'react-select'}
					isClearable
					isOptionDisabled={() => max && value?.length >= max}
				/>
			)}
			{help && <p className="post-type-select__help">{help}</p>}
		</PanelRow>
	);
};

export default PostSelectControl;
