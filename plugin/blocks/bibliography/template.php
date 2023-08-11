<?php
/**
 * The block template for the 'lh/bibliography' block.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content The block content.
 * @param WP_Block $block The block type.
 *
 * @package citations
 */

use function WpMunich\citations\lh_plugin;
use function WpMunich\citations\classNames;

$attrs = apply_filters( 'lh_normalize_block_attributes', $attributes, $block->name );

$align            = $attrs['align'] ?? '';
$background_color = $attrs['backgroundColor'] ?? '';
$text_color       = $attrs['textColor'] ?? '';

/**
 * The classes for the block wrapper.
 *
 * @var string
 */
$block_classes = classNames(
	$attrs['className'] ?? '',
	'lh-block-' . sanitize_title( $block->name ),
	'lh-bibliography-block',
	array(
		'align' . $align                                 => $align,
		'has-background'                                 => $background_color,
		'has-' . $background_color . '-background-color' => $background_color,
		'has-' . $text_color . '-color'                  => $text_color,
	)
);

/**
 * Load the citations for the current page request.
 */
$citations = lh_plugin()->citations()->get_citations();

/**
 * Array of extra attributes to render on the block wrapper.
 *
 * @see https://developer.wordpress.org/reference/functions/get_block_wrapper_attributes/
 * @var array
 */
$block_wrapper_attributes = array(
	'id'    => $attrs['block_id'] ?? null,
	'class' => $block_classes,
);

?>
<div <?php echo wp_kses_data( get_block_wrapper_attributes( $block_wrapper_attributes ) ); ?>>
	<ul class="lh-bibliography-block--citations">
		<?php foreach ( $citations as $i => $citation ) : ?>
			<li class="lh-bibliography-block--citation" id="wpm-citation-source-<?php echo esc_attr( $i + 1 ); ?>">
				<a class="lh-bibliography-block--citation-link" href="#wpm-citation-<?php echo esc_attr( $i + 1 ); ?>">
					[<?php echo esc_html( $i + 1 ); ?>]
				</a>
				<span class="lh-bibliography-block--citation-text">
					<?php echo wp_kses_post( $citation ); ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
