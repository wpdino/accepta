<?php
/**
 * Title: Featured Pricing Plan
 * Slug: accepta/pricing-featured
 * Description: Accepta featured pricing plan block pattern.
 * Categories: pricing, featured
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","backgroundColor":"light-gray","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"480px"}} -->
<div class="wp-block-group alignfull has-light-gray-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","right":"40px","bottom":"40px","left":"40px"}},"border":{"width":"2px"}},"borderColor":"primary","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-primary-border-color" style="border-width:2px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:paragraph {"align":"center","textColor":"primary"} -->
<p class="has-text-align-center has-primary-color has-text-color">Most Popular</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Professional</h3>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","level":2,"textColor":"primary"} -->
<h2 class="wp-block-heading has-text-align-center has-primary-color has-text-color">$49/mo</h2>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="#">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
