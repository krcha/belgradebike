<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package coup
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="archive-background">

	<?php coup_featured_media();

	if ( 'post' === get_post_type() || 'jetpack-portfolio' == get_post_type() ) : ?>
	<div class="entry-meta">
		<?php coup_posted_on(); ?>
	</div><!-- .entry-meta -->
	<?php
	endif; ?>

	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		if ('jetpack-portfolio' == get_post_type()) {

		} else if ( 'link' == get_post_format() || 'quote' == get_post_format() ) {
			the_content();
		} else {
			the_excerpt();
		};

			if ( 'link' == get_post_format() ) {
				?>
					<a class="post-format-type" href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"><i class="icon-link-p"></i></a>
				<?php
			} else if ('quote' == get_post_format() ) {
				?>
					<a class="post-format-type" href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"><i class="icon-quote-p"></i></a>
				<?php
			};
		?>
	</div><!-- .entry-content -->

	<?php
	if ( !has_post_thumbnail() && 'link' != get_post_format() && 'quote' != get_post_format() && 'jetpack-portfolio' != get_post_type()) {
        echo '<a class="more-link" href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More','coup-shop') . '</a>';
    } ?>
</div>
</article><!-- #post-## -->
