<?php
/**
 * The template part to display a single post in the loop.
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

global $post;

$args = wp_parse_args(
	$args,
	array(
		'post'       => $post,
		'classNames' => '',
	)
);

$loop_item_class_names = classNames(
	$args['classNames'],
	'the-loop__item',
	'stack',
);

?>
<article <?php post_class( $loop_item_class_names ); ?> id="post-<?php the_ID(); ?>">
	<div class="entry-header inner-container">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</div>
	<div class="is-root-container">
		<?php the_content(); ?>
	</div>
</article>
