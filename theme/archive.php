<?php
/**
 * The archive template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

get_header();
lh_theme()->styles()->print( 'lhpbpt-archive' );
?>

<div id="content" class="content-area">
	<?php get_template_part( 'template-parts/loop/loop', 'home' ); ?>
</div><!-- #content -->

<?php
get_footer();
