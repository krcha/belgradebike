<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header container container-side">
		<?php the_title( '<h1 class="entry-title big-text">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content container <?php
		if ( coup_is_woocommerce_activated() ) {
			if (is_cart() || is_checkout()) {
				echo 'container-medium';
			} else if (!is_account_page()) {
				echo 'container-small';
			}
		} ?> ">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'coup-shop' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
