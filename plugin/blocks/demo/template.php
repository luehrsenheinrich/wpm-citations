<?php
/**
 * The block template for the 'lh/separator' block.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content The block content.
 * @param WP_Block $block The block type.
 *
 * @package jitmp
 */

use function WpMunich\citations\lh_plugin;

$attr = wp_parse_args(
	$attributes,
	array(
		'icon' => '',
	)
);

?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<p><?php _e( 'This is a demo block.', 'citations' ); ?></p>
</div>
