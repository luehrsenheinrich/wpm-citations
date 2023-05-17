/**
 * WordPress dependencies.
 */
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';

/**
 * External dependencies.
 */
import Select from 'react-select';

/**
 * Internal dependencies.
 */
import LHIcon from '../icon';
import { ICONS_STORE_KEY } from '../../data';

const IconSelectControl = ({
	label = null,
	value,
	onChange,
	allowedIcons = [],
	blackList,
	whiteList,
}) => {
	const [selectedOption, setSelectedOption] = useState();

	const { icons } = useSelect((select) => {
		const { getIcons } = select(ICONS_STORE_KEY);
		let iconsFromSelect = getIcons();

		if (allowedIcons.length) {
			iconsFromSelect = [...iconsFromSelect].filter(
				(icon) => allowedIcons.indexOf(icon.slug) > -1
			);
		}

		return {
			icons: iconsFromSelect || [],
		};
	});

	useEffect(() => {
		if (icons?.length && selectedOption?.value !== value) {
			const icon = icons.find((i) => i?.slug === value) || {};
			setSelectedOption({
				icon: { ...icon },
				value: icon.slug,
				label: icon.title,
			});
		}
	}, [icons, value, selectedOption]);

	const onSelectIcon = (option) => {
		onChange(option?.value);

		if (!option?.value) {
			setSelectedOption(null);
		}
	};

	let options = icons;
	// Filter options over black- or whitelist, prefering white over blacklist.
	if (whiteList?.length) {
		options = icons.filter((option) => whiteList.indexOf(option.slug) > -1);
	} else if (blackList?.length) {
		options = icons.filter((option) => blackList.indexOf(option.slug) < 0);
	}
	return (
		<>
			{label?.length && (
				<label htmlFor="lh-icon-select-control">{label}</label>
			)}
			<Select
				openMenuOnClick={true}
				openMenuOnFocus={true}
				classNamePrefix="react-select"
				className="lh-icon-select-control react-select"
				value={selectedOption}
				onChange={onSelectIcon}
				isSearchable={true}
				isDisabled={!icons.length}
				isClearable={true}
				options={options.map((icon) => ({
					icon: { ...icon },
					value: icon.slug,
					label: icon.title,
				}))}
				getOptionLabel={(option) => (
					<div className="lh-icon-select-option-label">
						<LHIcon slug={option.icon.slug} svg={option.icon.svg} />
						<span>{option.label}</span>
					</div>
				)}
			/>
		</>
	);
};

export default IconSelectControl;
