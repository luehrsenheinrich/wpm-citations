<?php
/**
 * The block pattern for the example pattern.
 *
 * @package lhpbpt
 */

?>
<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">
	<!-- wp:paragraph -->
	<p>A simple example pattern. If you can read this at prod call an admin.</p>
	<!-- /wp:paragraph -->

	<!-- wp:image {"id":,"sizeSlug":"large","linkDestination":"none", "align":"wide"} -->
	<figure class="wp-block-image alignwide"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/img/block-patterns/placeholder_000.png" alt="" class=""/></figure>
	<!-- /wp:image -->
</div>
<!-- /wp:group -->
