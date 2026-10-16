<?php
/**
 * Blog features: archive layout and related posts.
 *
 * Defaults preserve the previous list layout and hide related posts
 * so existing sites are unchanged until the user opts in via Customizer.
 *
 * @package Accepta
 */

/**
 * Whether the current view should use the blog archive layout option.
 *
 * @return bool
 */
function accepta_is_blog_archive_layout_context() {
	if ( is_singular() ) {
		return false;
	}

	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return false;
	}

	if ( is_post_type_archive( 'wpdino_portfolio' ) || is_tax( 'portfolio-category' ) ) {
		return false;
	}

	return is_home() || is_category() || is_tag() || is_author() || is_date() || is_search();
}

/**
 * Get the blog archive layout setting.
 *
 * @return string list|grid
 */
function accepta_get_blog_archive_layout() {
	$layout = get_theme_mod( 'accepta_blog_archive_layout', 'list' );
	return in_array( $layout, array( 'list', 'grid' ), true ) ? $layout : 'list';
}

/**
 * Get the blog archive grid column count.
 *
 * @return int
 */
function accepta_get_blog_archive_columns() {
	$columns = absint( get_theme_mod( 'accepta_blog_archive_columns', 3 ) );
	return in_array( $columns, array( 2, 3, 4 ), true ) ? $columns : 3;
}

/**
 * Whether the active blog archive should render as a grid.
 *
 * @return bool
 */
function accepta_blog_archive_is_grid() {
	return accepta_is_blog_archive_layout_context() && 'grid' === accepta_get_blog_archive_layout();
}

/**
 * CSS classes for the posts loop wrapper.
 *
 * @return string
 */
function accepta_get_posts_loop_classes() {
	$classes = array( 'accepta-posts-loop' );

	if ( accepta_blog_archive_is_grid() ) {
		$classes[] = 'accepta-posts-loop--grid';
		$classes[] = 'accepta-posts-loop--cols-' . accepta_get_blog_archive_columns();
	} else {
		$classes[] = 'accepta-posts-loop--list';
	}

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}

/**
 * Add body classes for blog archive layout.
 *
 * @param array $classes Body classes.
 * @return array
 */
function accepta_blog_archive_body_classes( $classes ) {
	if ( ! accepta_is_blog_archive_layout_context() ) {
		return $classes;
	}

	$layout    = accepta_get_blog_archive_layout();
	$classes[] = 'accepta-blog-layout-' . sanitize_html_class( $layout );

	if ( 'grid' === $layout ) {
		$classes[] = 'accepta-blog-cols-' . accepta_get_blog_archive_columns();
	}

	return $classes;
}
add_filter( 'body_class', 'accepta_blog_archive_body_classes' );

/**
 * Whether related posts should render on the current single post.
 *
 * @return bool
 */
function accepta_should_show_related_posts() {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'accepta_related_posts', false ) ) {
		return false;
	}

	return true;
}

/**
 * Get related posts for the current post (by category, fallback by tag).
 *
 * @param int $count Number of posts.
 * @return WP_Post[]
 */
function accepta_get_related_posts( $count = 3 ) {
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return array();
	}

	$count = max( 1, min( 6, absint( $count ) ) );
	$args  = array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'orderby'             => 'rand',
	);

	$categories = wp_get_post_categories( $post_id );
	if ( ! empty( $categories ) ) {
		$args['category__in'] = $categories;
	} else {
		$tags = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
		if ( empty( $tags ) ) {
			return array();
		}
		$args['tag__in'] = $tags;
	}

	$query = new WP_Query( $args );
	$posts = $query->posts;
	wp_reset_postdata();

	return is_array( $posts ) ? $posts : array();
}

/**
 * Render related posts below single post content.
 *
 * @return void
 */
function accepta_related_posts() {
	if ( ! accepta_should_show_related_posts() ) {
		return;
	}

	$count = absint( get_theme_mod( 'accepta_related_posts_count', 3 ) );
	$posts = accepta_get_related_posts( $count );
	if ( empty( $posts ) ) {
		return;
	}

	$title = get_theme_mod( 'accepta_related_posts_title', __( 'Related posts', 'accepta' ) );
	?>
	<section class="accepta-related-posts" aria-label="<?php echo esc_attr( $title ); ?>">
		<h2 class="accepta-related-posts__title"><?php echo esc_html( $title ); ?></h2>
		<div class="accepta-related-posts__grid">
			<?php foreach ( $posts as $related_post ) : ?>
				<article <?php post_class( 'accepta-related-posts__item', $related_post->ID ); ?>>
					<?php if ( has_post_thumbnail( $related_post ) ) : ?>
						<a class="accepta-related-posts__thumb" href="<?php echo esc_url( get_permalink( $related_post ) ); ?>">
							<?php
							echo get_the_post_thumbnail(
								$related_post,
								'medium',
								array(
									'alt' => the_title_attribute(
										array(
											'echo' => false,
											'post' => $related_post,
										)
									),
								)
							);
							?>
						</a>
					<?php endif; ?>
					<h3 class="accepta-related-posts__item-title">
						<a href="<?php echo esc_url( get_permalink( $related_post ) ); ?>">
							<?php echo esc_html( get_the_title( $related_post ) ); ?>
						</a>
					</h3>
					<p class="accepta-related-posts__meta">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $related_post ) ); ?>">
							<?php echo esc_html( get_the_date( '', $related_post ) ); ?>
						</time>
					</p>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}
