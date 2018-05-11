<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main container" role="main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header container">
				<?php
					coup_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div id="post-load" class="row">

			<?php
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

			?> </div> <?php

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

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
