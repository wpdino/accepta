<?php
/**
 * Title: Four Icon Columns
 * Slug: accepta/columns-four-icons
 * Description: Accepta four icon columns block pattern.
 * Categories: columns, featured
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Four Highlights</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Speed</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Optimized assets for fast loading.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Security</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Built following WordPress best practices.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Support</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Documentation and updates from WPDINO.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Scale</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Grow from blog to shop with WooCommerce.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
