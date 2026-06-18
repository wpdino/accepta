<?php
/**
 * Title: Posts List
 * Slug: accepta/posts-list
 * Description: Accepta posts list block pattern.
 * Categories: posts, query
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Recent Articles</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:query {"query":{"perPage":5,"postType":"post","order":"desc","orderBy":"date"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:post-title {"isLink":true} /-->

<!-- wp:post-date /-->

<!-- wp:post-excerpt {"moreText":"Read more"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
