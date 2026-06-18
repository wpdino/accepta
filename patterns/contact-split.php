<?php
/**
 * Title: Contact Split Layout
 * Slug: accepta/contact-split
 * Description: Accepta contact split layout block pattern.
 * Categories: contact, columns
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading -->
<h2 class="wp-block-heading">Let's talk</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Whether you need a new site or a refresh, we're ready to help you get started.</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>Free consultation</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Custom design guidance</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Ongoing support</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}},"color":{"background":"#f5f5f5"}}} -->
<p class="has-background" style="background-color:#f5f5f5;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><strong>Email:</strong> hello@example.com<br><strong>Phone:</strong> +1 (555) 123-4567</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
