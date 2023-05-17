/**
 * WordPress dependencies.
 */
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Popover, TextControl, Button } from '@wordpress/components';
import {
	registerFormatType,
	toggleFormat,
	useAnchor,
	applyFormat,
} from '@wordpress/rich-text';
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import icon from './../../../../img/icons/citation.svg';

const CiteFormatButton = (props) => {
	const { isActive, onChange, value, contentRef, activeAttributes } = props;
	const anchor = useAnchor({
		editableContentElement: contentRef.current,
		value,
		settings,
	});

	const [popoverOpen, setPopoverOpen] = useState(false);
	const [popoverHasFocus, setPopoverHasFocus] = useState(false);
	const [nextAttributes, setNextAttributes] = useState();
	const [isEditing, setIsEditing] = useState(false);

	// Control the open state of the popover.
	useEffect(() => {
		if (isActive) {
			setPopoverOpen(true);
		} else if (!isActive && !popoverHasFocus) {
			setPopoverOpen(false);
			setIsEditing(false);
		}
	}, [isActive, popoverHasFocus]);

	// Update the attributes when another format is selected.
	useEffect(() => {
		setNextAttributes(activeAttributes);
		setIsEditing(false);
	}, [activeAttributes]);

	const updateAttributes = () => {
		const attributes = { ...activeAttributes, ...nextAttributes };

		const newValue = applyFormat(value, {
			type: settings.name,
			attributes,
		});

		onChange(newValue);
	};

	return (
		<>
			<RichTextToolbarButton
				title={__('Cite', 'citations')}
				icon={icon}
				onClick={() => {
					onChange(
						toggleFormat(value, {
							type: settings.name,
						})
					);
					setIsEditing(true);
				}}
				isActive={isActive}
			/>
			{popoverOpen && (
				<Popover
					anchor={anchor}
					focusOnMount={false}
					noArrow={false}
					position="bottom center"
					onFocus={() => {
						setPopoverHasFocus(true);
					}}
					offset={8}
					onBlur={() => {
						setPopoverHasFocus(false);
					}}
				>
					<div className="block-editor-cite-control">
						<div className="block-editor-cite-control__field">
							{!isEditing && (
								<div className="block-editor-cite-control__preview">
									<div className="block-editor-cite-control__preview-text">
										{activeAttributes.citeText ?? (
											<span className="no-cite-text">
												{__(
													'Please set a citation',
													'citations'
												)}
											</span>
										)}
									</div>
									<Button
										variant="link"
										icon="edit"
										onClick={() => {
											setIsEditing(true);
										}}
									/>
								</div>
							)}

							{isEditing && (
								<div className="block-editor-cite-control__edit">
									<TextControl
										value={nextAttributes.citeText ?? ''}
										onChange={(citeText) => {
											setNextAttributes({
												...nextAttributes,
												citeText,
											});
										}}
									/>
									<Button
										variant="primary"
										icon="editor-break"
										onClick={() => {
											updateAttributes();
											setIsEditing(false);
										}}
									/>
								</div>
							)}
						</div>
					</div>
				</Popover>
			)}
		</>
	);
};

const settings = {
	name: 'wpm-format/cite',
	title: __('Cite', 'citations'),
	keywords: [__('bibliography'), __('source')],
	tagName: 'span',
	className: 'js--wpm-format-cite',
	attributes: {
		citeText: 'data-cite-text',
	},
	edit: CiteFormatButton,
};

registerFormatType(settings.name, settings);
