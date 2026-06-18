<?php
/**
 * Title: FAQ Section
 * Slug: accepta/faq-section
 * Description: Accepta faq section block pattern.
 * Categories: accepta, text
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Frequently Asked Questions</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Is Accepta Elementor compatible?</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Yes. Accepta works with Elementor and the block editor.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Can I customize colors and fonts?</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Use the Customizer for global colors, typography, and layout.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Does it support WooCommerce?</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Accepta includes WooCommerce styles for shop pages.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Is the theme translation ready?</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Yes. Accepta includes a complete translation template.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
