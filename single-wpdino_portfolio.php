<?php
/**
 * Single portfolio item template for DinoFolio.
 *
 * Full-width hero with overlay header, project description, meta, and related projects.
 *
 * @package Accepta
 */

get_header();
?>

<div class="content-sidebar-wrap content-sidebar-wrap--no-sidebar accepta-portfolio-single-wrap">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/portfolio', 'hero' );
		?>
		<main id="primary" class="site-main accepta-portfolio-single-main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'accepta-portfolio-single-article' ); ?>>
				<div class="container">
					<div class="entry-content accepta-portfolio-single-content">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'accepta' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
				</div>
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</main>
		<?php
	endwhile;
	?>
</div>

<?php
get_footer();
