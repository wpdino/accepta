<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Accepta
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php
		if ( 'post' === get_post_type() ) :
			$show_meta = function_exists( 'accepta_blog_archive_show_date' )
				&& ( accepta_blog_archive_show_date() || accepta_blog_archive_show_author() );
			if ( $show_meta ) :
				?>
				<div class="entry-meta">
					<?php
					if ( function_exists( 'accepta_blog_archive_entry_meta' ) ) {
						accepta_blog_archive_entry_meta();
					} else {
						accepta_posted_on();
						accepta_posted_by();
					}
					?>
				</div><!-- .entry-meta -->
				<?php
			endif;
		endif;
		?>
	</header><!-- .entry-header -->

	<?php accepta_post_thumbnail(); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<?php if ( ! function_exists( 'accepta_blog_archive_show_categories' ) || accepta_blog_archive_show_categories() ) : ?>
	<footer class="entry-footer">
		<?php
		if ( function_exists( 'accepta_blog_archive_entry_footer' ) ) {
			accepta_blog_archive_entry_footer();
		} else {
			accepta_entry_footer();
		}
		?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
