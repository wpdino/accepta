<?php
/**
 * Portfolio single hero (featured / first gallery image).
 *
 * @package Accepta
 */

$post_id    = get_the_ID();
$image_id   = accepta_get_portfolio_hero_image_id( $post_id );
$has_image  = $image_id > 0;
$categories = get_the_terms( $post_id, 'wpdino_portfolio_category' );
$hero_meta  = accepta_get_portfolio_hero_meta( $post_id );
$show_hero_meta = $hero_meta['show_date'] || ! empty( $hero_meta['external_url'] );
?>

<section class="accepta-hero-section accepta-portfolio-hero accepta-hero-fullwidth accepta-hero-min-height<?php echo $has_image ? ' accepta-portfolio-hero--has-image' : ' accepta-portfolio-hero--no-image'; ?>" aria-label="<?php esc_attr_e( 'Project hero', 'accepta' ); ?>">
	<?php if ( $has_image ) : ?>
		<div class="accepta-portfolio-hero-media" aria-hidden="true">
			<?php
			echo wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array(
					'class'         => 'accepta-portfolio-hero-image',
					'alt'           => the_title_attribute( array( 'echo' => false ) ),
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="accepta-hero-overlay accepta-portfolio-hero-overlay"></div>

	<div class="accepta-hero-content-wrapper">
		<div class="container accepta-portfolio-hero-container">
			<div class="accepta-hero-content accepta-portfolio-hero-intro">
				<?php the_title( '<h1 class="accepta-hero-heading accepta-portfolio-hero-title">', '</h1>' ); ?>

				<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
					<div class="accepta-portfolio-hero-categories">
						<?php foreach ( $categories as $category ) : ?>
							<a class="dinofolio-category-pill accepta-portfolio-hero-category-pill" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
								<?php
								if ( class_exists( '\DinoFolio\Portfolio_Category_Icon' ) ) {
									echo \DinoFolio\Portfolio_Category_Icon::render_icon_html( $category ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
								<span class="dinofolio-category-pill-label"><?php echo esc_html( $category->name ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_hero_meta ) : ?>
				<div class="accepta-portfolio-hero-meta">
					<?php if ( $hero_meta['show_date'] && ! empty( $hero_meta['date_value'] ) ) : ?>
						<div class="accepta-portfolio-hero-date">
							<span class="accepta-portfolio-hero-date-label"><?php echo esc_html( $hero_meta['date_label'] ); ?></span>
							<span class="accepta-portfolio-hero-date-value"><?php echo esc_html( $hero_meta['date_value'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $hero_meta['external_url'] ) ) : ?>
						<a class="accepta-portfolio-hero-launch accepta-hero-button accepta-hero-button-outline" href="<?php echo esc_url( $hero_meta['external_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( $hero_meta['button_label'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
