<?php
/**
 * Template part for displaying gallery posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

?>



<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="archive-background">

		<?php coup_featured_media(); ?>

		<div class="entry-meta">
			<?php coup_posted_on(); ?>
		</div><!-- .entry-meta -->

		<header class="entry-header">
			<?php
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
		</header><!-- .entry-header -->
	</div>

</article><!-- #post-## -->
