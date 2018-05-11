<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package coup
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<p class="big-text"><?php esc_html_e( 'Oops!', 'coup-shop' ); ?></p>

			<section class="error-404 not-found container container-small">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'That page can&rsquo;t be found.', 'coup-shop' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p>
						<?php esc_html_e( 'There’s nothing to be found here.', 'coup-shop' ); ?>
						<a href="<?php echo get_home_url(); ?>"><?php esc_html_e( 'Go back home', 'coup-shop' ); ?></a>
						<?php esc_html_e( ' and try your luck there.', 'coup-shop' ); ?>
					</p>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
	<aside class="side-nav">
		<!-- Search form -->
		<div class="search-wrap">
			<?php coup_custom_search_form(); ?>
		</div>
	</aside>

<?php
get_footer();
