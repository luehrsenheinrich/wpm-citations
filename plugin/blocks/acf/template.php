<?php
/**
 * The template for displaying a generic acf block.
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package lhpbpp
 */

// Create id attribute allowing for custom "anchor" value.
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
} else {
	$block_id = $block['id'];
}

$block_classes = classNames(
	// Those checks are neccessary for debugbar, would throw warnings otherwise.
	( isset( $block['className'] ) ? $block['className'] : null ),
	'wp-block-' . sanitize_title( $block['name'] ),
	array(
		'align' . $block['align'] => isset( $block['align'] ) && ! empty( $block['align'] ),
	)
);
?>

<div class="<?php echo esc_attr( $block_classes ); ?>" id="<?php echo esc_attr( $block_id ); ?>">
	This template is supposed to be overriden in the theme.
</div>
