Accepta
=======

Accepta is a beautiful and flexible WordPress theme that works perfectly with Elementor page builder for creating stunning websites. This theme provides a clean, professional design with customizable features and seamless Elementor integration.

## Features

* Modern, responsive design
* Perfect Elementor page builder compatibility
* Custom header implementation
* Custom template tags for clean code
* Mobile-friendly navigation
* CSS Grid layouts for flexible content arrangement
* Full WooCommerce integration with product gallery features
* Customizer integration for easy theme customization
* Translation ready
* SEO optimized
* Licensed under GPLv2 or later

## Installation

1. Download the theme files
2. Upload to your WordPress site via Appearance > Themes > Add New > Upload Theme
3. Activate the theme
4. Customize via Appearance > Customize

## Customization

The theme includes a comprehensive customizer with options for:
* Site identity (logo, colors, fonts)
* Header settings
* Layout options
* WooCommerce integration

## Support

For theme support and documentation, visit [WPDINO](https://wpdino.com).

## Changelog

### 1.1.2 (2026-08-07)

* Fixed top-level menu links not working on touch devices by removing a conflicting leftover navigation script.

### 1.1.1 (2026-08-05)

* Added a WooCommerce Shop style option (Default and Shopline) for the catalog and single product pages.
* Added header cart icon choices (cart, bag, basket, tote) in the Customizer.
* Improved Shopline product cards with tighter image-to-meta spacing and a clearer price and Add to cart layout.

### 1.1.0 (2026-06-29)

* Added Scrolled Header Logo option in the Customizer for a separate logo when the sticky header is scrolled.
* Added DinoFolio portfolio integration with archive, single, taxonomy templates, hero layout, and dedicated styles.
* Added sample footer widgets as block widgets on theme activation (About Accepta, About WPDINO, Quick Links, Search).
* Added footer widget preview in the Customizer before theme activation so all four footer columns display sample content.
* Added block editor stylesheet so blockquotes match the frontend card-style design inside Gutenberg.
* Added Customizer Help Guide link on the Accepta dashboard welcome page.
* Updated default social icons to X (fab fa-x-twitter) and refreshed Font Awesome brands webfont assets.
* Footer widget heading underlines and accents now follow the Global Primary Color from the Customizer.
* Fixed entry content button links so `.wp-block-button__link` and `.wp-element-button` keep white text on primary backgrounds.
* Fixed blockquote styling mismatch between the block editor and the live site.
* Fixed container side padding so horizontal spacing appears below the combined container width breakpoint.
* Removed Container Width control from Accepta Lite (available in Accepta PRO).
* Improved Media & Text block content alignment by removing default left padding.
* Fixed mobile header social links display.

### 1.0.8 (2026-06-18)

* Added theme.json with a brand color palette, Outfit typography, spacing scale, layout widths, and block editor styles for buttons, links, headings, quotes, and separators.
* Added 55 block patterns for heroes, CTAs, pricing, services, testimonials, contact, blog layouts, and full page templates (Home, About, Services, Pricing, Contact).
* Registered Accepta and Accepta Pages pattern categories and disabled core block patterns to keep the pattern library focused.
* Fixed block pattern markup so buttons and column layouts use valid Gutenberg block structure (resolves "Attempt Block Recovery" errors).
* Global Primary Color from the Customizer now drives buttons, navigation, forms, footer accents, blog links, pagination, and focus states via CSS custom properties.
* Extended Customizer live preview for primary, link, link hover, and visited link colors.
* Replaced hardcoded green accents across theme styles with Customizer-aware CSS variables.
* Updated button hover states to use a brightness filter instead of fixed darken colors.

### 1.0.7 (2026-05-23)
* Fixed custom logo display on overlay headers by removing the invert filter that broke colored and light logos.
* Added spacing between the custom logo and site title in the header.
* Added a "Hide Tagline" option in the Header Customizer section (off by default).

### 1.0.6 (2026-05-20)

* Fixed hero content alignment so horizontal alignment options in the Customizer now correctly align both content position and text.
* Improved hero alignment output by mapping flex alignment values to logical text alignment values for better LTR/RTL behavior.
* Updated Customizer live preview to apply hero text alignment changes instantly without refresh.
* Removed hard-coded centered hero text CSS that was overriding Customizer alignment settings.

### 1.0.5 (2026-04-30)

* Improved search pages so they look cleaner and easier to read, especially when no results are found.
* Improved keyboard navigation by making focus highlights more visible across buttons, links, and form fields.
* Improved loading speed by using smaller optimized CSS and JavaScript files on live websites.
* Fixed mobile header icon order so the cart button appears after the search icon.
* Fixed a mobile issue where the cart button could stay visible when the search overlay is open.
* Reduced extra code output in page source for better performance in production.
* Updated language template file to include all current translatable text.
* Updated compatibility information to use one clear PHP requirement across theme files.

### 1.0.4 (2026-04-07)

* Fixed social icon rendering conflicts when Elementor is active by improving Font Awesome enqueue strategy.
* Added "Outfit" to Google Fonts list and normalized font list ordering.
* Updated translation template (.pot) and added translators comments for placeholder strings.
* Increased admin plugin API cache duration to two weeks.

## License

This theme is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.