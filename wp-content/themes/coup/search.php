<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package coup
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header container container-side">
				<h1 class="page-title"><?php
					echo '<span class="big-text">';
					echo esc_html__( 'Search', 'coup-shop' );
					echo '</span>';
					echo get_search_query(); ?>
				</h1>

				<div class="results-count">
					<?php echo coup_search_results_count() ?>
				</div>
			</header><!-- .page-header -->

			<div id="post-load" class="page-content container container-medium">

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'search' );

				endwhile;

				coup_numbers_pagination(); ?>
			</div>
		<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->
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

	</aside>

<?php
get_footer();
