/**
 * WordPress dependencies.
 */
import { withSelect } from '@wordpress/data';
import { Icon as WPIcon } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * External dependencies.
 */
import classNames from 'classnames';
import parse, { attributesToProps, domToReact } from 'html-react-parser';

/**
 * Internal dependencies.
 */
import { ICONS_STORE_KEY } from '../../data';

/**
 * Returns a wp.components.Icon element with icon from API.
 */
class LHIcon extends Component {
	constructor() {
		super(...arguments);
		this.getParserOptions = this.getParserOptions.bind(this);
	}

	getParserOptions() {
		return {
			replace: (domNode) => {
				if (domNode.name === 'svg') {
					// Define the custom tag name.
					const CustomTag = domNode.name;
					const props = attributesToProps(domNode.attribs);

					return (
						<CustomTag {...props} key={domNode.attribs.id}>
							{domToReact(domNode.children)}
						</CustomTag>
					);
				}

				return domNode;
			},
		};
	}

	render() {
		const { icon } = this.props;
		if (icon?.svg) {
			const className = classNames(
				this.props.className,
				`lh-icon icon-${this.props.slug}`
			);
			const parsedSvg = parse(icon.svg, this.getParserOptions());
			return (
				<WPIcon
					{...this.props}
					icon={parsedSvg}
					className={className}
				/>
			);
		}

		return <></>;
	}
}

export default withSelect((select, props) => {
	const { getIcons, getIcon } = select(ICONS_STORE_KEY);
	const icons = getIcons();
	let icon = icons.filter(({ slug }) => slug === props.slug)[0] || null;
	if (!icon) {
		icon = getIcon(props.slug);
	}
	return {
		icon,
	};
})(LHIcon);
