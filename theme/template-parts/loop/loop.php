<?php
/**
 * The template part to display the loop.
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

global $wp_query, $post;

$args = wp_parse_args(
	$args,
	array(
		'query'      => $wp_query,
		'classNames' => '',
	)
);

$loop_class_names = classNames(
	$args['classNames'],
	'the-loop',
);

$pagination_args = array(
	'query'      => $args['query'],
);

lh_theme()->styles()->print( 'lhpbpt-loop' );
?>

<div class="<?php echo esc_attr( $loop_class_names ); ?>">
	<?php
	if ( $args['query']->have_posts() ) :
		?>
		<div class="the-loop__posts stack">
			<?php
			while ( $args['query']->have_posts() ) :
				$args['query']->the_post();
				get_template_part( 'template-parts/loop/item', get_post_type() );
			endwhile;
			?>
		</div>
		<?php
	else :
		?>
		<div class="the-loop__no-posts wp-site-blocks">
			<p>
				<?php esc_html_e( 'Sorry, no posts matched your criteria.', 'lhpbpt' ); ?>
			</p>
		</div>
		<?php
	endif;
	?>
	<div class="the-loop__pagination wp-site-blocks">
		<?php
		get_template_part( 'template-parts/loop/pagination', null, $pagination_args );
		?>
	</div>
</div>
