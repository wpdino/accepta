<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Accepta
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'accepta' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container">
			<?php
			$header_layout = get_theme_mod( 'accepta_header_layout', 'layout-3' );
			// Remove 'layout-' prefix if present to avoid double prefix
			$layout_suffix = str_replace( 'layout-', '', $header_layout );
			$header_layout_class = 'header-layout-' . esc_attr( $layout_suffix );
			?>
			<div class="header-content <?php echo esc_attr( $header_layout_class ); ?>">
				<div class="site-branding">
					<?php the_custom_logo(); ?>
					<div class="branding-text">
						<?php
						if ( is_front_page() && is_home() ) :
							?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php
						else :
							?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						endif;
						$accepta_description = get_bloginfo( 'description', 'display' );
						if ( $accepta_description || is_customize_preview() ) :
							?>
							<p class="site-description"><?php echo $accepta_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php endif; ?>
					</div><!-- .branding-text -->
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'accepta' ); ?></span>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'menu_class'     => 'nav-menu',
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<?php if ( function_exists( 'accepta_should_display_header_social_icons' ) && accepta_should_display_header_social_icons() ) : ?>
					<div class="header-social-icons">
						<?php accepta_social_icons( 'header' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( get_theme_mod( 'accepta_display_header_search', true ) ) : ?>
					<button class="header-search-toggle" aria-label="<?php esc_attr_e( 'Open search', 'accepta' ); ?>" aria-expanded="false">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
							<path d="m20 20-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
						<span class="screen-reader-text"><?php esc_html_e( 'Search', 'accepta' ); ?></span>
					</button>
				<?php endif; ?>
			</div><!-- .header-content -->

			<?php if ( get_theme_mod( 'accepta_display_header_search', true ) ) : ?>
				<div class="header-search-overlay" aria-hidden="true">
					<button class="header-search-close" aria-label="<?php esc_attr_e( 'Close search', 'accepta' ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
						<span class="screen-reader-text"><?php esc_html_e( 'Close', 'accepta' ); ?></span>
					</button>
					<div class="header-search-overlay-content">
						<div class="header-search-form-wrapper">
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div><!-- .container -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
