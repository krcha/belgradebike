<?php
/**
 * Template Name: Featured Products Page
 *
 * @package coup
 */

get_header(); ?>

<div id="primary" class="content-area woo-page woocommerce">
	<?php

	coup_call_header_video();

	if ( is_front_page() && !is_paged() ) :

		coup_front_slider();

	endif;
	?>

	<main id="main" class="site-main container" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<header class="entry-header">
				<?php
					the_title( '<h1 class="page-title big-text">', '</h1>' );
					$thecontent = get_the_content();
					if(!empty($thecontent) ) { ?>
						<div class="portfolio-description">
							<?php echo $thecontent; ?>
						</div>
					<?php }

				?>
				</header><!-- .entry-header -->

				<div id="post-load" class="row products">

					<?php
						$args = array(
							'post_type' => 'product',
							'posts_per_page' => -1,
							'nopaging' => true,
							'tax_query' => array(
								array(
									'taxonomy' => 'product_visibility',
									'field'    => 'name',
									'terms'    => 'featured',
									'operator' => 'IN'
								),
							),
						);

						$loop = new WP_Query( $args );

						while ( $loop->have_posts() ) : $loop->the_post();
							global $product; ?>

							<?php wc_get_template_part( 'content', 'product' ); ?>

						<?php endwhile; ?>
				</div>

				<nav class="navigation-to-shop">
					<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php esc_html_e( 'View All', 'coup-shop'); ?></a>
				</nav>

			<?php endwhile; // end of the loop. ?>

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

	<?php 	coup_categories_filter('shop'); ?>

</aside>
<?php
	 get_footer();
?>
