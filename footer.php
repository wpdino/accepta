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
		// Get footer columns setting
		$footer_columns = get_theme_mod( 'accepta_footer_columns', '4' );
		
		// Only show footer widgets if columns is not 0
		if ( $footer_columns !== '0' ) {
			// Check if any footer widget areas are active (check all 4, not just up to selected count)
			$has_active_widgets = false;
			for ( $i = 1; $i <= 4; $i++ ) {
				if ( is_active_sidebar( 'footer-' . $i ) ) {
					$has_active_widgets = true;
					break;
				}
			}
			
			if ( $has_active_widgets ) : ?>
				<div class="footer-widgets-container">
					<div class="container">
						<div class="footer-widgets">
							<?php
							// Always render all widget areas (up to 4) for live preview support
							// CSS will control visibility based on column setting
							for ( $i = 1; $i <= 4; $i++ ) {
								if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
									<div class="footer-widget-area footer-widget-<?php echo esc_attr( $i ); ?>">
										<?php dynamic_sidebar( 'footer-' . $i ); ?>
									</div>
								<?php endif;
							}
							?>
						</div>
					</div>
				</div>
			<?php endif;
		}
		?>
		
		<div class="site-info">
			<div class="container">
				<?php if ( function_exists( 'accepta_should_display_social_icons' ) && accepta_should_display_social_icons() ) : ?>
					<div class="footer-social-icons">
						<?php accepta_social_icons(); ?>
					</div>
				<?php endif; ?>
				
				<div class="footer-copyright">
					<?php 
					$copyright_text = get_theme_mod( 'accepta_footer_copyright', __( '{copyright} {current-year} {site-title}. Powered by {wordpress}.', 'accepta' ) );
					$processed_copyright = accepta_process_copyright_tags( $copyright_text );
					
					// Always display the container, even if copyright text is empty
					if ( ! empty( trim( $processed_copyright ) ) ) {
						echo wp_kses_post( $processed_copyright );
					} else {
						// Add a non-breaking space to ensure container has content and remains visible
						echo '&nbsp;';
					}
					?>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html> 