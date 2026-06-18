<?php
/**
 * Title: Featured Post
 * Slug: accepta/posts-featured
 * Description: Accepta featured post block pattern.
 * Categories: posts, featured
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Featured Article</h2>
<!-- /wp:heading -->

<!-- wp:query {"query":{"perPage":1,"postType":"post","order":"desc","orderBy":"date"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"align":"wide"} /-->

<!-- wp:post-title {"textAlign":"center","isLink":true,"fontSize":"x-large"} /-->

<!-- wp:post-excerpt {"textAlign":"center","moreText":"Continue reading"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
