<?php
/**
 * Title: Featured Testimonial
 * Slug: accepta/testimonials-featured
 * Description: Accepta featured testimonial block pattern.
 * Categories: testimonials, columns
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"metadata":{"categories":["testimonials"],"patternName":"accepta/testimonials-featured","name":"Featured Testimonial"},"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"width":"35%"} -->
<div class="wp-block-column" style="flex-basis:35%"><!-- wp:cover {"url":"http://localhost/accepta-lite/wp-content/themes/accepta/assets/images/accepta-hero-bg.jpg","dimRatio":30,"minHeight":320} -->
<div class="wp-block-cover" style="min-height:320px"><img class="wp-block-cover__image-background" alt="" src="http://localhost/accepta-lite/wp-content/themes/accepta/assets/images/accepta-hero-bg.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"placeholder":"Write title…","style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"65%"} -->
<div class="wp-block-column" style="flex-basis:65%"><!-- wp:heading -->
<h2 class="wp-block-heading">Featured Testimonial</h2>
<!-- /wp:heading -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>“Accepta gave us a clear system for creating pages quickly, and our conversion rate improved within weeks.”</p>
<!-- /wp:paragraph --><cite>Natalie Brooks · Head of Marketing</cite></blockquote>
<!-- /wp:quote --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->