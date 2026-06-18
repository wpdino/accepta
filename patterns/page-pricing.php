<?php
/**
 * Title: Pricing Page
 * Slug: accepta/page-pricing
 * Description: Full pricing page layout for Accepta.
 * Categories: accepta-pages, pages
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":60,"overlayColor":"secondary","minHeight":400,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white","level":1} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Pricing</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}}} -->
<div class="wp-block-column" style="border-width:1px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color">Starter</h3>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","level":4} -->
<h4 class="wp-block-heading has-text-align-center">$19/mo</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>✓ 1 site</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>✓ Email support</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="#">Choose Plan</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}}} -->
<div class="wp-block-column" style="border-width:1px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color">Pro</h3>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","level":4} -->
<h4 class="wp-block-heading has-text-align-center">$49/mo</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>✓ 5 sites</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>✓ Priority support</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="#">Choose Plan</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

</div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
