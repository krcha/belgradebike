<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<?php

		coup_call_header_video();

		if ( is_front_page() && !is_paged() ) :

			coup_front_slider();

		endif;
		?>

		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

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


		<?php coup_insert_sharedaddy() ?>
	</aside>

<?php
get_footer();
