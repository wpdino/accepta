<?php
/**
 * Portfolio category/tag taxonomy archive (Accepta theme override).
 *
 * @package Accepta
 */

defined( 'ABSPATH' ) || exit;

get_header();

$accepta_term         = get_queried_object();
$accepta_description  = term_description();
$accepta_is_category  = ( $accepta_term instanceof WP_Term && 'wpdino_portfolio_category' === $accepta_term->taxonomy );
$accepta_eyebrow      = $accepta_is_category
	? esc_html__( 'Portfolio Category', 'accepta' )
	: esc_html__( 'Portfolio Tag', 'accepta' );
$dinofolio_display    = class_exists( 'WPDINO_Portfolio_Display' ) ? \WPDINO_Portfolio_Display::get_instance() : null;
$dinofolio_listing    = '';

if ( $dinofolio_display ) {
	$dinofolio_listing = $dinofolio_display->render_portfolio_listing(
		$dinofolio_display->get_taxonomy_listing_attributes()
	);
}
?>

<div class="content-sidebar-wrap content-sidebar-wrap--no-sidebar accepta-portfolio-taxonomy-wrap">
	<main id="primary" class="site-main accepta-portfolio-taxonomy-main">
		<div class="dinofolio-taxonomy-archive">
			<?php if ( $accepta_term instanceof WP_Term ) : ?>
				<header class="accepta-portfolio-taxonomy-header dinofolio-taxonomy-header">
					<p class="accepta-portfolio-taxonomy-eyebrow"><?php echo esc_html( $accepta_eyebrow ); ?></p>

					<div class="accepta-portfolio-taxonomy-title-row">
						<?php
						if ( $accepta_is_category && class_exists( '\DinoFolio\Portfolio_Category_Icon' ) ) {
							echo \DinoFolio\Portfolio_Category_Icon::render_icon_html( $accepta_term, 'accepta-portfolio-taxonomy-term-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
						<h1 class="page-title accepta-portfolio-taxonomy-title dinofolio-taxonomy-title">
							<?php echo esc_html( $accepta_term->name ); ?>
						</h1>
					</div>

					<?php if ( $accepta_description ) : ?>
						<div class="archive-description accepta-portfolio-taxonomy-description dinofolio-taxonomy-description">
							<?php echo wp_kses_post( wpautop( $accepta_description ) ); ?>
						</div>
					<?php endif; ?>
				</header>
			<?php endif; ?>

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
