<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package coup
 */

get_header();

?>

<div id="primary" class="content-area woo-page">
	<?php
	if ( is_front_page() ) :

		coup_front_slider();

	endif;
	?>

	<main id="main" class="site-main container" role="main">

		<?php if ( is_shop() || is_archive() ) : ?>

			<?php woocommerce_content(); ?>

		<?php else : ?>
			<article>
				<?php woocommerce_content(); ?>
			</article>

		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->
<aside class="side-nav">
	<!-- Search form -->
	<div class="search-wrap">
		<?php coup_custom_search_form(); ?>
	</div>

	<!-- woo filter sidebar -->
	<?php if ( coup_is_woocommerce_activated() ) : ?>

		<?php if ( !is_cart() && !is_checkout() ) { ?>

			<!-- Cart icon -->
			<div class="cart-touch">
				<?php coup_cart_link(); ?>
			</div>

		<?php }
	endif; ?>

	<!-- insert sharedaddy if enabled here -->
		<?php coup_insert_sharedaddy() ?>

	<!-- List all categories -->

	<?php if ( (is_shop() || is_product_category() || is_product_tag() ) && !is_search()) {
		coup_categories_filter('shop');
	}; ?>

	<!-- Add product navigation -->

	<?php if ( is_singular('product') ) {
		coup_product_navigation();
	} ?>


</aside>
<?php
	 get_footer();
?>
