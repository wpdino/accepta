<?php
/**
 * Title: Contact Info Columns
 * Slug: accepta/contact-info-columns
 * Description: Accepta contact info columns block pattern.
 * Categories: contact, columns
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Get in Touch</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Address</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>123 Main Street<br>City, State 12345</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Email</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>hello@example.com</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":4,"textColor":"primary"} -->
<h4 class="wp-block-heading has-primary-color has-text-color">Phone</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>+1 (555) 123-4567</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
