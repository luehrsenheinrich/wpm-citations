import { components } from 'react-select';
import { useDroppable } from '@dnd-kit/core';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

const MultiValue = (props) => {
	const onMouseDown = (e) => {
		e.preventDefault();
		e.stopPropagation();
	};
	const innerProps = { ...props.innerProps, onMouseDown };
	const { attributes, listeners, setNodeRef, transform, transition } =
		useSortable({
			id: props.data.value,
		});

	const style = {
		transform: CSS.Transform.toString(transform),
		transition,
	};

	return (
		<div style={style} ref={setNodeRef} {...attributes} {...listeners}>
			<components.MultiValue {...props} innerProps={innerProps} />
		</div>
	);
};

const MultiValueContainer = (props) => {
	const { isOver, setNodeRef } = useDroppable({
		id: 'droppable',
	});

	const style = {
		color: isOver ? 'green' : undefined,
	};

	return (
		<div content={'Customise your multi-value container!'}>
			<div style={style} ref={setNodeRef}>
				<components.MultiValueContainer {...props} />
			</div>
		</div>
	);
};

const MultiValueRemove = (props) => {
	return (
		<components.MultiValueRemove
			{...props}
			innerProps={{
				onPointerDown: (e) => e.stopPropagation(),
				...props.innerProps,
			}}
		/>
	);
};

export { MultiValue, MultiValueContainer, MultiValueRemove };
