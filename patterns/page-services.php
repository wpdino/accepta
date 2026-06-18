<?php
/**
 * Title: Services Page
 * Slug: accepta/page-services
 * Description: Full services page layout for Accepta.
 * Categories: accepta-pages, pages
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":60,"overlayColor":"secondary","minHeight":400,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim-60 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white","level":1} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Our Services</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Explore what we offer and how we can help.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>Add your content here. Replace placeholder text with your own copy.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":20,"minHeight":250} -->
<div class="wp-block-cover" style="min-height:250px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-20 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
