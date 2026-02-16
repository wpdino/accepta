<?php
/**
 * Template part for wrapping single content and sidebar
 *
 * @package Accepta
 */

$has_sidebar = accepta_has_sidebar();
?>
<div class="content-sidebar-wrap<?php echo ! $has_sidebar ? ' content-sidebar-wrap--no-sidebar' : ''; ?>">
    <main id="primary" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );

            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'accepta' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'accepta' ) . '</span> <span class="nav-title">%title</span>',
                )
            );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </main><!-- #primary -->

    <?php if ( $has_sidebar ) : ?>
        <?php get_sidebar(); ?>
    <?php endif; ?>
</div><!-- .content-sidebar-wrap --> 