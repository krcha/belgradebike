<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package coup
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
		if ( is_active_sidebar( 'sidebar-2' ) or is_active_sidebar( 'sidebar-3' ) ) { ?>

			<div class="footer-widget-holder container">
				<?php coup_footer_widgets(); ?>
			</div>

		<?php

		} ?>


		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'coup-shop' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'coup-shop' ), 'WordPress' ); ?></a>
			<span class="sep"> - </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'coup-shop' ), 'Coup Shop', '<a href="https://themeskingdom.com/" rel="designer">Themes Kingdom</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php if ( coup_is_woocommerce_activated() ) : ?>

	<?php if ( !is_cart() && !is_checkout() ) { ?>

		<!-- Mini Cart -->

		<div class="mini-cart woo-side-area">
			<?php coup_woo_header_cart(); ?>
		</div>

	<?php }
endif; ?>
<span class="overlay"></span>
<button class="back-to-top hide"><i class="icon-top"></i></button>
<?php wp_footer(); ?>

</body>
</html>
