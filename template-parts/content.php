<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Accepta
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			$show_meta = is_singular() || (
				function_exists( 'accepta_blog_archive_show_date' )
				&& ( accepta_blog_archive_show_date() || accepta_blog_archive_show_author() )
			);
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
	</header><!-- .entry-header -->

	<?php accepta_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		if ( is_singular() ) :
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'accepta' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'accepta' ),
					'after'  => '</div>',
				)
			);
		else :
			the_excerpt();
			?>
			<p class="read-more-link">
				<a href="<?php the_permalink(); ?>" class="more-link">
					<?php
					echo esc_html__( 'Continue reading', 'accepta' );
					?>
					<span class="screen-reader-text"><?php echo esc_html( get_the_title() ); ?></span>
				</a>
			</p>
			<?php
		endif;
		?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() || ( function_exists( 'accepta_blog_archive_show_categories' ) && accepta_blog_archive_show_categories() ) ) : ?>
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
