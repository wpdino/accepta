<?php
/**
 * Title: Homepage
 * Slug: accepta/page-home
 * Description: Full homepage layout for Accepta.
 * Categories: accepta-pages, pages
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":60,"overlayColor":"secondary","minHeight":400,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white","level":1} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Welcome to Accepta</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Why Choose Accepta</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Flexible</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Customizer options for every part of your site.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Fast</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Lightweight and performance optimized.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-primary-color has-text-color">Modern</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Gutenberg patterns and Elementor ready.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","backgroundColor":"primary","textColor":"white","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-color has-primary-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Ready to get started?</h2>
<!-- /wp:heading -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="#">Contact Us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
