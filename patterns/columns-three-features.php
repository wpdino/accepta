<?php
/**
 * Title: Three Column Features
 * Slug: accepta/columns-three-features
 * Description: Accepta three column features block pattern.
 * Categories: columns, services
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Three Column Features</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Strategy</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Plan your site structure, content, and conversion goals.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Design</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Apply Accepta patterns and customize colors and typography.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Launch</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Publish with confidence using responsive, accessible layouts.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
