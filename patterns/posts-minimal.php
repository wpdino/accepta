<?php
/**
 * Title: Minimal Posts List
 * Slug: accepta/posts-minimal
 * Description: Accepta minimal posts list block pattern.
 * Categories: posts, text
 * Keywords: accepta, gutenberg, blocks
 * Viewport Width: 1280
 * Inserter: true
 */
$accepta_img = esc_url( get_template_directory_uri() . '/assets/images/accepta-hero-bg.jpg' );
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull " style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">News & Updates</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:query {"query":{"perPage":6,"postType":"post","order":"desc","orderBy":"date"}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"20px"}},"border":{"bottom":{"color":"#e0e0e0","width":"1px"}}}} -->
<div class="wp-block-group" style="border-bottom-color:#e0e0e0;border-bottom-width:1px;padding-bottom:20px"><!-- wp:post-title {"isLink":true,"level":3} /-->

<!-- wp:post-date {"fontSize":"small"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group -->
