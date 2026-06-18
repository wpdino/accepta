<?php
/**
 * Title: Posts by Category
 * Slug: accepta/posts-category
 * Description: Accepta posts by category block pattern.
 * Categories: posts, query
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">From the Blog</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:query {"query":{"perPage":4,"postType":"post","order":"desc","orderBy":"date"}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
<!-- wp:post-featured-image {"isLink":true} /-->

<!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->

<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
