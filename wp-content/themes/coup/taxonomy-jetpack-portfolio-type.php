<?php
/**
 * Displays gallery archives
 *
 * @package coup
 */
get_header(); ?>

		<div id="primary" class="content-area gallery">
			<main id="main" class="site-main container" role="main">

				<?php
					if ( isset( get_queried_object()->name ) ) {
						$term_name = get_queried_object()->name;
					} else {
						$term_name = 0;
					}
				?>
				<header class="entry-header">
					<?php printf( '<h2 class="page-title big-text">%s</h2>', esc_html( $term_name ) ); ?>

					<?php
						$type_description = get_queried_object()->description;

						if ($type_description) {
					?>
						<div class="portfolio-description">
							<?php
								echo esc_html($type_description);
							?>
						</div>
					<?php }; ?>
				</header>

				<?php if ( have_posts() ) : ?>

					<div class="container">
						<div id="post-load" class="masonry row">

							<?php
								while ( have_posts() ) : the_post();

									get_template_part( 'template-parts/content', 'portfolio' );

								endwhile;
							?>

						</div><!-- .masonry -->

						<?php
							coup_numbers_pagination();
							wp_reset_postdata();
						?>

					</div><!-- .container -->

					<?php else : ?>

						<section class="no-results not-found">

							<header class="page-header">
								<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'coup-shop' ); ?></h1>
							</header>
							<div class="page-content">
								<?php if ( current_user_can( 'publish_posts' ) ) : ?>

									<p><?php printf( wp_kses( __( 'Ready to publish your first project? <a href="%1$s">Get started here</a>.', 'coup-shop' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php?post_type=jetpack-portfolio' ) ) ); ?></p>

								<?php else : ?>

									<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'coup-shop' ); ?></p>
									<?php get_search_form(); ?>
									<div class="search-instructions"><?php esc_html_e( 'Press Enter / Return to begin your search.', 'coup-shop' ); ?></div>

								<?php endif; ?>
							</div>

						</section>

					<?php endif; ?>

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

			<!-- list all portfolio types -->

			<?php coup_categories_filter('portfolio') ?>

		</aside>

<?php get_footer(); ?>
