<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Accepta
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php
		$footer_columns = get_theme_mod( 'accepta_footer_columns', '4' );
		$show_footer_widgets_preview = function_exists( 'accepta_should_show_footer_widgets_preview' ) && accepta_should_show_footer_widgets_preview();

		// Check if any footer widget areas are active (check all 4, not just up to selected count).
		$has_active_widgets = false;
		for ( $i = 1; $i <= 4; $i++ ) {
			if ( is_active_sidebar( 'footer-' . $i ) ) {
				$has_active_widgets = true;
				break;
			}
		}

		if ( 0 != $footer_columns && ( $show_footer_widgets_preview || $has_active_widgets ) ) : ?>
			<div class="footer-widgets-container">
				<div class="container">
					<div class="footer-widgets">
						<?php
						// Always render all widget areas (up to 4) for live preview support.
						// CSS controls visibility based on the column setting.
						for ( $i = 1; $i <= 4; $i++ ) {
							if ( $show_footer_widgets_preview || is_active_sidebar( 'footer-' . $i ) ) : ?>
								<div class="footer-widget-area footer-widget-<?php echo esc_attr( $i ); ?>">
									<?php
									if ( $show_footer_widgets_preview ) {
										accepta_render_footer_widget_preview( $i );
									} else {
										dynamic_sidebar( 'footer-' . $i );
									}
									?>
								</div>
							<?php endif;
						}
						?>
					</div>
				</div>
			</div>
		<?php endif;

		$show_social_icons = function_exists( 'accepta_should_display_social_icons' ) && accepta_should_display_social_icons();
		$copyright_text    = get_theme_mod( 'accepta_footer_copyright', __( '{copyright} {current-year} {site-title}. Powered by {wordpress}.', 'accepta' ) );
		$has_copyright     = ! empty( trim( (string) $copyright_text ) );

		if ( $show_social_icons || $has_copyright ) : ?>
			<div class="site-info">
				<div class="container">
					<?php if ( $show_social_icons ) : ?>
						<div class="footer-social-icons">
							<?php accepta_social_icons(); ?>
						</div>
					<?php endif; ?>

					<?php if ( $has_copyright ) : ?>
						<div class="footer-copyright">
							<?php echo wp_kses_post( accepta_process_copyright_tags( $copyright_text ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
