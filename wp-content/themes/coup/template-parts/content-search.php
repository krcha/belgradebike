<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('container container-small'); ?>>

	<div class="search-post-type">
		<?php echo get_post_type() == 'jetpack-portfolio' ? esc_html__( 'portfolio', 'coup-shop' ) : get_post_type(); ?>
	</div>

	<?php coup_featured_image(); ?>
	<header class="entry-header">
		<?php the_title( sprintf( '<h5 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h5>' ); ?>

		<?php if ( 'post' === get_post_type() || 'jetpack-portfolio' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php coup_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
