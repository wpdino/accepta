<?php
/**
 * Title: About Introduction
 * Slug: accepta/about-intro
 * Description: Intro section with media and text.
 * Categories: about, text
 * Keywords: accepta, about
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>

<!-- wp:media-text {"align":"wide","mediaPosition":"right","mediaType":"image","verticalAlignment":"center","metadata":{"categories":["about"],"patternName":"accepta/about-intro","name":"About Introduction"}} -->
<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center"><div class="wp-block-media-text__content"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"1px"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="letter-spacing:1px;text-transform:uppercase">About</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Built for growth-focused websites</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Accepta helps teams launch fast websites with clear structure, clean typography, and flexible components.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button" href="#">Our Story</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url($accepta_img); ?>" alt=""/></figure></div>
<!-- /wp:media-text -->