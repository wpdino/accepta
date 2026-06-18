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
<!-- wp:group {"align":"full","backgroundColor":"light-gray","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-gray-background-color has-background"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"width":"35%"} -->
<div class="wp-block-column" style="flex-basis:35%"><!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":30,"minHeight":320} -->
<div class="wp-block-cover" style="min-height:320px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"></div></div>
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
