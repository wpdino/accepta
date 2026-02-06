<?php
/**
 * Template Name: Full Width No Paddings
 * Description: A full-width page template with no paddings.
 *
 * @package Accepta
 */

get_header();
?>

<div class="content-wrap content-wrap--no-paddings">
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
