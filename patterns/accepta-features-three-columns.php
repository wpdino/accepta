<?php
/**
 * Title: Three Feature Highlights
 * Slug: accepta/features-three-columns
 * Description: Accepta three feature highlights block pattern.
 * Categories: accepta, columns, featured
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Why Choose Accepta</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Flexible</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Multiple header layouts, hero options, and full Customizer control.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Fast</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Lightweight code and optimized assets keep your site responsive.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Modern</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Clean design with Gutenberg patterns and WooCommerce ready styles.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
