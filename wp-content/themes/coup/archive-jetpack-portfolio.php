<?php
/**
 * Displays portfolio archives
 *
 * @package coup
 */
get_header(); ?>

		<div id="primary" class="content-area gallery">
			<main id="main" class="site-main container" role="main">

				<header class="entry-header container">
					<?php
						coup_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<?php if ( have_posts() ) : ?>

					<div id="post-load" class="masonry row">

						<?php
							while ( have_posts() ) : the_post();

								get_template_part( 'template-parts/content', 'portfolio' );

							endwhile;
						?>

					</div>
				<?php
					coup_numbers_pagination();
				?>

			<?php else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>

			</main>
		</div>
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

			<?php coup_categories_filter('portfolio') ?>

		</aside>

<?php get_footer(); ?>
