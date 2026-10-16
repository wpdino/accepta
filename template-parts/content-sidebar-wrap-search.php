<?php
/**
 * Template part for wrapping search content and sidebar
 *
 * @package Accepta
 */

$has_sidebar = accepta_has_sidebar();
?>
<div class="content-sidebar-wrap<?php echo ! $has_sidebar ? ' content-sidebar-wrap--no-sidebar' : ''; ?>">
	<main id="primary" class="site-main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'accepta' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<div class="<?php echo esc_attr( accepta_get_posts_loop_classes() ); ?>">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'search' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( '&larr; Previous', 'accepta' ),
					'next_text' => __( 'Next &rarr;', 'accepta' ),
				)
			);

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
	</main><!-- #primary -->

	<?php if ( $has_sidebar ) : ?>
		<?php get_sidebar(); ?>
	<?php endif; ?>
</div><!-- .content-sidebar-wrap -->
