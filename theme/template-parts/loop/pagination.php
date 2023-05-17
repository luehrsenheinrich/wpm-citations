<?php
/**
 * The template for the pagination within the loop.
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;
use function WpMunich\lhpbpp\lh_plugin;

$args = wp_parse_args(
	$args,
	array(
		'query'      => $GLOBALS['wp_query'],
		'classNames' => '',
	)
);

// Get max pages and current page out of the current query, if available.
$total   = isset( $args['query']->max_num_pages ) ? $args['query']->max_num_pages : 1;
$current = $args['query']->query_vars['paged'] ? (int) $args['query']->query_vars['paged'] : 1;

$classnames = classNames(
	$args['classNames'],
	'loop-pagination',
);

if ( $args['query']->max_num_pages > 1 ) : ?>
<nav class="<?php echo esc_attr( $classnames ); ?>" role="navigation">
	<?php if ( $args['query']->query_vars['paged'] > 1 ) : ?>
		<div class="prev">
			<a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>" data-page-target="<?php echo esc_attr( $current - 1 ); ?>" class="prev-link">
				<span class="screen-reader-text"><?php echo esc_attr( 'Previous', 'lhpbpt' ); ?></span>
				<?php
				echo lh_plugin()->svg()->get_svg( //phpcs:ignore
					'chevron--left',
					array(
						'attributes' => array(
							'class' => 'icon icon-chevron',
						),
					)
				);
				?>
			</a>
		</div>
	<?php endif; ?>
	<?php
	/*
		* NUMBERS Mobile.
		*/
	?>
	<div class="page-numbers page-numbers-mobile">
		<span class="has-primary-color"><?php echo esc_attr_x( 'Page', 'lhpbpt' ) . ' ' . esc_attr( $current ); ?></span> <span class="has-gray-color">/ <?php echo esc_attr( $total ); ?></span>
	</div>
	<?php
		echo wp_kses_post(
			lh_theme()->nav_menus()->paginate_links(
				array(
					'total'   => $total,
					'current' => $current,
				)
			)
		);
	?>
	<?php if ( $args['query']->query_vars['paged'] < $args['query']->max_num_pages ) : ?>
		<div class="next">
			<a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>" data-page-target="<?php echo esc_attr( $current + 1 ); ?>" class="next-link">
				<span class="screen-reader-text"><?php echo esc_attr( 'Next', 'lhpbpt' ); ?></span>
				<?php
				echo lh_plugin()->svg()->get_svg( //phpcs:ignore
					'chevron--right',
					array(
						'attributes' => array(
							'class' => 'icon icon-chevron',
						),
					)
				);
				?>
			</a>
		</div>
	<?php endif; ?>
</nav>
<?php endif; ?>
