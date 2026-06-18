<?php
/**
 * Title: Hero with Button
 * Slug: accepta/hero-with-button
 * Description: Accepta hero with button block pattern.
 * Categories: banner, call-to-action
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":50,"overlayColor":"secondary","minHeight":500,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-xx-large-font-size">Build Bold. Build Beautiful.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">A modern WordPress theme for business and portfolio.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="#">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
