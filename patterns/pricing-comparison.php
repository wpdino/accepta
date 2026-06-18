<?php
/**
 * Title: Pricing Comparison
 * Slug: accepta/pricing-comparison
 * Description: Accepta pricing comparison block pattern.
 * Categories: pricing, columns
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Compare Plans</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:table -->
<figure class="wp-block-table"><table><thead><tr><th>Feature</th><th>Starter</th><th>Pro</th></tr></thead><tbody><tr><td>Patterns</td><td>Basic</td><td>All</td></tr><tr><td>Support</td><td>Email</td><td>Priority</td></tr><tr><td>Sites</td><td>1</td><td>5</td></tr></tbody></table></figure>
<!-- /wp:table --></div>
<!-- /wp:group -->
