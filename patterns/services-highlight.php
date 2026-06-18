<?php
/**
 * Title: Featured Service
 * Slug: accepta/services-highlight
 * Description: Highlighted service split layout.
 * Categories: services, columns
 * Keywords: accepta, featured
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:columns {"align":"wide","verticalAlignment":"center"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%"><!-- wp:heading -->
<h2 class="wp-block-heading">Featured Service</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Conversion-focused website redesign with clear hierarchy, strong messaging, and measurable business outcomes.</p>
<!-- /wp:paragraph -->
<!-- wp:list -->
<ul class="wp-block-list"><li>UX audit</li><li>Visual redesign</li><li>Performance tuning</li></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->
<!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%"><!-- wp:cover {"url":"<?php echo $accepta_img; ?>","dimRatio":20,"minHeight":300} -->
<div class="wp-block-cover" style="min-height:300px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-20 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $accepta_img; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"></div></div>
<!-- /wp:cover --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
