<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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


		<main id="main" class="site-main container" role="main">
			<?php
			if ( is_home() && ! is_front_page() ) : ?>

			<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>

			<?php
			endif;
			?>
			<div id="post-load" class="row">
				<?php
				if ( have_posts() ) :

					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					coup_numbers_pagination();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>
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

		<?php coup_categories_filter('index') ?>


	</aside>

<?php
get_footer();
