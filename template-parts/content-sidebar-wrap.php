<?php
/**
 * Template part for wrapping content and sidebar
 *
 * @package Accepta
 */

$has_sidebar = accepta_has_sidebar();
?>
<div class="content-sidebar-wrap<?php echo ! $has_sidebar ? ' content-sidebar-wrap--no-sidebar' : ''; ?>">
    <main id="primary" class="site-main">
        <?php
        if ( have_posts() ) :

            if ( is_home() && ! is_front_page() ) :
                ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
                <?php
            endif;

            /* Start the Loop */
            while ( have_posts() ) :
                the_post();

                /*
                 * Include the Post-Type-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                 */
                get_template_part( 'template-parts/content', get_post_type() );

            endwhile;

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