<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

?>

<section class="no-results not-found container container-small">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'coup-shop' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'coup-shop' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p>
				<?php esc_html_e( 'There’s nothing to be found here.', 'coup-shop' ); ?>
				<a href="<?php echo get_home_url(); ?>"><?php esc_html_e( 'Go back home', 'coup-shop' ); ?></a>
				<?php esc_html_e( ' and try your luck there.', 'coup-shop' ); ?>
			</p>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
