<?php
/**
 * Template Name: Portfolio
 *
 * @package coup
 */

get_header(); ?>

		<div id="primary" class="content-area gallery">

			<?php

			coup_call_header_video();

			if ( is_front_page() && !is_paged() ) :

				coup_front_slider();

			endif;
			?>

			<main id="main" class="site-main container" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<header class="entry-header">
						<?php the_title( '<h1 class="page-title big-text">', '</h1>' );

						$thecontent = get_the_content();
						if(!empty($thecontent)) { ?>
						<div class="portfolio-description">
							<?php echo $thecontent; ?>
						</div>

						<?php } ?>
					</header><!-- .entry-header -->
				<?php endwhile; // End of the loop.
				wp_reset_postdata();
				?>

				<?php
					global $paged, $wp_query, $wp;
					$args = wp_parse_args($wp->matched_query);
					if ( !empty ( $args['paged'] ) && 0 == $paged ) {
						$wp_query->set('paged', $args['paged']);
						$paged = $args['paged'];
					}
					$paged          = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$posts_per_page = get_option( 'jetpack_portfolio_posts_per_page', '8' );

					$args = array(
						'post_type'      => 'jetpack-portfolio',
						'posts_per_page' => $posts_per_page,
						'paged'			 => $paged
					);

					$wp_query = new WP_Query ( $args );

					?>


					<?php

					if ( post_type_exists( 'jetpack-portfolio' ) && $wp_query->have_posts() ) :
					?>

						<div id="post-load" class="masonry row">

							<?php

								while ( $wp_query->have_posts() ) : $wp_query->the_post();

									get_template_part( 'template-parts/content', 'portfolio' );

								endwhile;
							?>

						</div><!-- .masonry -->

						<?php
							coup_numbers_pagination();
							wp_reset_postdata();
						?>

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

			<?php coup_insert_sharedaddy() ?>

			<!-- List all portfolio types -->

			<?php coup_categories_filter('portfolio') ?>


		</aside>

<?php get_footer(); ?>
