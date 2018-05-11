<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'container container-medium'); ?>>

	<?php coup_featured_media(); ?>


	<header class="entry-header container container-small">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h5 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' );
		endif;
		?>

		<div class="entry-meta">
			<?php coup_posted_on() ?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content container container-small">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'coup-shop' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'coup-shop' ),
				'after'  => '</div>',
			) );
		?>
		<div class="entry-footer container container-small">
			<?php coup_entry_footer(); ?>
		</div>
	</div><!-- .entry-content -->
	<?php
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		echo '<div class="related-holder container container-small">';
	    echo coup_do_shortcode_function('jetpack-related-posts');
	    echo '</div>';
	}
	?>
</article>
