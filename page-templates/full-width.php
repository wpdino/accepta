<?php
/**
 * Template Name: Full Width (For Elementor Builder)
 * Description: A page template with full width.
 *
 * @package Accepta
 */

get_header();
?>

<div class="content-wrap content-wrap--full-width">
	<main id="primary" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</main>
</div>

<?php
get_footer();
