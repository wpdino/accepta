<?php
/**
 * The template for displaying WooCommerce pages (shop, product, cart, checkout, etc.)
 * Uses full-width layout without sidebar.
 *
 * @package Accepta
 */

get_header();
?>

<div class="content-sidebar-wrap content-sidebar-wrap--no-sidebar">
	<?php woocommerce_content(); ?>
</div>

<?php
get_footer();
