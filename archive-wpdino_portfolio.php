<?php
/**
 * Portfolio post type archive (Accepta theme).
 *
 * @package Accepta
 */

defined( 'ABSPATH' ) || exit;

get_header();

$post_type_object = get_post_type_object( 'wpdino_portfolio' );
$archive_title    = $post_type_object && ! empty( $post_type_object->labels->name )
	? $post_type_object->labels->name
	: esc_html__( 'Portfolio', 'dinofolio' );
$archive_description = get_the_archive_description();
$dinofolio_display   = class_exists( 'WPDINO_Portfolio_Display' ) ? \WPDINO_Portfolio_Display::get_instance() : null;
$dinofolio_listing   = '';

if ( $dinofolio_display ) {
	$dinofolio_listing = $dinofolio_display->render_portfolio_listing(
		$dinofolio_display->get_archive_listing_attributes()
	);
}
?>

<div class="content-sidebar-wrap content-sidebar-wrap--no-sidebar accepta-portfolio-taxonomy-wrap">
	<main id="primary" class="site-main accepta-portfolio-taxonomy-main">
		<div class="dinofolio-taxonomy-archive accepta-portfolio-archive">
			<header class="accepta-portfolio-taxonomy-header dinofolio-taxonomy-header">
				<p class="accepta-portfolio-taxonomy-eyebrow"><?php esc_html_e( 'Portfolio Archive', 'accepta' ); ?></p>

				<div class="accepta-portfolio-taxonomy-title-row">
					<h1 class="page-title accepta-portfolio-taxonomy-title dinofolio-taxonomy-title">
						<?php echo esc_html( $archive_title ); ?>
					</h1>
				</div>

				<?php if ( $archive_description ) : ?>
					<div class="archive-description accepta-portfolio-taxonomy-description dinofolio-taxonomy-description">
						<?php echo wp_kses_post( wpautop( $archive_description ) ); ?>
					</div>
				<?php endif; ?>
			</header>

			<?php if ( $dinofolio_listing ) : ?>
				<div class="dinofolio-taxonomy-listing">
					<?php echo $dinofolio_listing; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php else : ?>
				<div class="dinofolio-no-posts">
					<?php esc_html_e( 'Portfolio display module is not available.', 'dinofolio' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</main>
</div>

<?php
get_footer();
