<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package coup
 */

if ( ! function_exists( 'coup_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function coup_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

	$byline = sprintf(
		esc_html_x( 'By : %s', 'post author', 'coup-shop' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline">' . $byline . '</span><span class="posted-on"> ' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'coup_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function coup_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() or 'jetpack-portfolio' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		if ( 'jetpack-portfolio' == get_post_type() ) {
			$categories_list = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', ', ', '' );
		} else {
			$categories_list = get_the_category_list( ', ' );
		}

		if ( $categories_list && coup_categorized_blog() ) {
			printf( '<span class="cat-links"><span class="meta-text">' . esc_html__( 'Posted in %1$s', 'coup-shop' ) . '</span>', '</span>' . $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		if ( 'jetpack-portfolio' == get_post_type() ) {
			$tags_list = get_the_term_list( get_the_ID(), 'jetpack-portfolio-tag', '', ', ', '');
		} else {
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'coup-shop' ) );
		}

		if ($categories_list && $tags_list ) {
			printf('<span class="cat-tags-connector meta-text">' . esc_html__(' and ','coup-shop') . '</span>');
		}

		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="meta-text">' . esc_html__( 'tagged %1$s', 'coup-shop' ) . '</span>', '</span>' . $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'coup-shop' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}



	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'coup-shop' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Custom post navigation
 *
 * @since coup 1.0
 */
function coup_post_navigation() {
	$post_navigation      = '';
	$prev_post_navigation = '';
	$next_post_navigation = '';
	$previous_post        = get_previous_post();
	$next_post            = get_next_post();

	// Previous post
	if ( !empty( $previous_post ) ) {

		$prev_text 		= esc_html__( 'Previous Post', 'coup-shop' );
		$prev_post_text = '<a href="' . esc_url( get_permalink( $previous_post->ID ) ) . '"><span class="prev-trig">' . $prev_text . '<i class="icon-left"></i></span>';

		$prev_post_navigation = '<div class="nav-previous">';
		$prev_post_navigation .= $prev_post_text;
		$prev_post_navigation .= '<div class="prev-title">';
			$prev_post_navigation .= '<span class="post-title">' . get_the_title( $previous_post->ID ) . '</span>';
		$prev_post_navigation .= '</div></div></a>';
	}

	// Next post
	if ( !empty( $next_post ) ) {

		$next_text 		= esc_html__( 'Next Post', 'coup-shop' );
		$next_post_text = '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '"><span class="next-trig">' . $next_text . '<i class="icon-right"></i></span>';

		$next_post_navigation = '<div class="nav-next">';
		$next_post_navigation .= $next_post_text;
		$next_post_navigation .= '<div class="next-title">';
			$next_post_navigation .= '<span class="post-title">' . get_the_title( $next_post->ID ) . '</span>';
		$next_post_navigation .= '</div></div></a>';
	}

	// Post navigation
	$post_navigation = $next_post_navigation . $prev_post_navigation;

	echo _navigation_markup( $post_navigation );
}



/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function coup_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'coup_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'coup_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so coup_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so coup_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in coup_categorized_blog.
 */
function coup_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'coup_categories' );
}
add_action( 'edit_category', 'coup_category_transient_flusher' );
add_action( 'save_post',     'coup_category_transient_flusher' );


/**
 * Generate and display Footer widgets
 *
 * @since coup 1.0
 */
function coup_footer_widgets() {

	$footer_sidebars = array(
		'sidebar-2',
		'sidebar-3'
	);

	foreach ( $footer_sidebars as $footer_sidebar ) {

		if ( is_active_sidebar( $footer_sidebar ) ) { ?>

			<div class="widget-area">
				<?php dynamic_sidebar( $footer_sidebar ); ?>
			</div>

		<?php

		}

	}

}

/**
 * Displays post featured image
 *
 * @since  coup 1.0
 */
function coup_featured_image() {

	if ( has_post_thumbnail() ) :

		if ( is_single() ) { ?>

			<div class="featured-content featured-image <?php echo esc_attr( coup_get_featured_image_class() ); ?>">
				<?php

				$url = wp_get_attachment_url( get_post_thumbnail_id( ) );
				$filetype = wp_check_filetype($url);
				if ($filetype['ext'] == 'gif') {
					$thumb_size = '';
				} else {
					$thumb_size = 'coup-single-post';
				}

				the_post_thumbnail( $thumb_size ); ?>
			</div>

		<?php } else { ?>

			<div class="featured-content featured-image <?php echo esc_attr( coup_get_featured_image_class() ); ?>">

				<?php

					if (!is_search() & 'jetpack-portfolio' == get_post_type()) {
						$lazy_load_class = 'skip-lazy';
					} else {
						$lazy_load_class = '';
					}

					$url = wp_get_attachment_url( get_post_thumbnail_id( ) );
					$filetype = wp_check_filetype($url);
					if ($filetype['ext'] == 'gif') {
						$thumb_size = '';
					} else {

						$blog_layout        = get_theme_mod( 'blog_layout_setting', 'standard-layout' );
						if ($blog_layout == 'standard-layout') {
							if (is_sticky()) {
								$thumb_size = 'coup-archive-sticky';
							} else {
								$thumb_size = 'coup-archive';
							}
						} else {
							if (is_sticky()) {
								$thumb_size = 'coup-shuffle-sticky';
							} else {
								$thumb_size = 'coup-shuffle';
							}
						}
					}
				?>

				<?php if ( 'image' == get_post_format() ) {

					$thumb_id        = get_post_thumbnail_id();
					$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'full', true );
					$thumb_url       = $thumb_url_array[0];

				?>
					<a href="<?php echo esc_url( $thumb_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="thickbox">
						<?php the_post_thumbnail($thumb_size, array( 'class' => $lazy_load_class ) ); ?>
					</a>

				<?php } else { ?>

					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($thumb_size, array( 'class' => $lazy_load_class ) ); ?></a>

				<?php } ?>

				<?php if ('link' != get_post_format() && 'quote' != get_post_format() && 'jetpack-portfolio' != get_post_type() ) {
					echo '<a class="more-link" href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More','coup-shop') . '</a>';
				}
				?>

			</div>

		<?php }

	else :

		return;

	endif;
}

/**
 * Displays post featured image for hero positioned: slider or last post
 *
 * @since  coup 1.0
 */
function coup_hero_featured_image() {

	if ( has_post_thumbnail() ) :

		?>

		<div class="featured-content featured-image">

			<?php the_post_thumbnail( 'full', array( 'class' => 'skip-lazy' )); ?>

		</div>

		<?php

	else :

		return;

	endif;

}

/**
 * Displays post featured image
 *
 * @since  coup 1.0
 */
function coup_featured_media() {

	if ( 'gallery' == get_post_format() ) :

		if ( get_post_gallery() && ! post_password_required() ) { ?>

			<div class="featured-content entry-gallery">
				<?php echo get_post_gallery(); ?>
				<?php if (!is_single() && !'link' == get_post_format() && !'quote' == get_post_format() && !'jetpack-portfolio' == get_post_type() ) {
					echo '<a class="more-link" href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More','coup-shop') . '</a>';
				} ?>
			</div><!-- .entry-gallery -->

		<?php } else {

			coup_featured_image();

		}

	elseif ( 'video' == get_post_format() ) :

		if ( coup_get_embeded_media() ) { ?>

			<div class="featured-content entry-video">
				<div class="video-sizer">
					<?php echo coup_get_embeded_media(); ?>
				</div>
				<?php if (!is_single() && !'link' == get_post_format() && !'quote' == get_post_format() && !'jetpack-portfolio' == get_post_type()) {
					echo '<a class="more-link" href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More','coup-shop') . '</a>';
				} ?>
			</div><!-- .entry-video -->

		<?php } else {

			coup_featured_image();

		}

	else :

		coup_featured_image();

	endif;

}


/**
 * Display the archive title based on the queried object.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function coup_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( '<span class="big-text">%s</span><span class="archive-name">%s</span>',
						 esc_html__( 'Category', 'coup-shop'),
						 single_cat_title( '', false )
				 );
	} elseif ( is_tag() ) {
		$title = sprintf( '<span class="big-text">%s</span>%s',
						 esc_html__( 'Tag' , 'coup-shop'),
						 single_tag_title( '', false )
				 );
	} elseif ( is_author() ) {
		$title = sprintf( '<span class="big-text">%s</span>%s',
						 esc_html__( 'Author' , 'coup-shop'),
						 get_the_author()
				 );
	} elseif ( is_year() ) {
		$title = sprintf( '<span class="big-text">%s</span>%s',
						 esc_html__( 'Year' , 'coup-shop'),
						 get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'coup-shop' ) )  );
	} elseif ( is_month() ) {
		$title = sprintf( '<span class="big-text">%s</span>%s',
						 esc_html__( 'Month' , 'coup-shop'),
						 get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'coup-shop' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( '<span class="big-text">%s</span>%s',
						 esc_html__( 'Day' , 'coup-shop'),
						 get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'coup-shop' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Asides', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Galleries', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Images', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Videos', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Quotes', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Links', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Statuses', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Audio', 'post format archive title', 'coup-shop' ) . '</span>';
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = '<span class="big-text">' . esc_html_x( 'Chats', 'post format archive title', 'coup-shop' ) . '</span>';
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( '<span class="big-text">%s</span>',
						  post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf('<span class="big-text">%1$s</span>%2$s',
						 $tax->labels->singular_name,
						 single_term_title( '', false )
				 );
	} else {
		$title = sprintf('<span class="big-text">%s</span>%s',
					esc_html__( 'Archives', 'coup-shop' )
				 );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}

/**
 * coup custom paging function
 *
 * Creates and displays custom page numbering pagination in bottom of archives
 *
 * @since coup 1.0
 */
function coup_numbers_pagination() {

	global $wp_query, $wp_rewrite, $project_query;

	$paging_query = $wp_query;

	/** Stop execution if there's only 1 page */
	if( $paging_query->max_num_pages <= 1 )
		return;


	$paging_query->query_vars['paged'] > 1 ? $current = $paging_query->query_vars['paged'] : $current = 1;

	if ( is_page_template( 'templates/gallery-page.php' ) ) {
		$paging_query = $project_query;
		$paging_query->query['paged'] > 1 ? $current = $paging_query->query['paged'] : $current = 1;
	}

	$pagination = array(
		'base'      => @add_query_arg( 'paged', '%#%' ),
		'format'    => '',
		'total'     => $paging_query->max_num_pages,
		'current'   => $current,
		'end_size'           => 1,
		'mid_size'           => 2,
		'type'      => 'list',
		'prev_next' => false
	);

	if ( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

	if ( ! empty( $paging_query->query_vars['s'] ) ) {
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
	}

	// add prev and next post links
	$prev_link_text = esc_html__('Prev','coup-shop');
	$next_link_text = esc_html__('Next','coup-shop');
	$prev_link = get_previous_posts_link( $prev_link_text );
	$next_link = get_next_posts_link( $next_link_text );

	if ( !$prev_link ) {
		$prev_link = '<span class="disabled">' . $prev_link_text . '</span>';
	}

	if ( !$next_link ) {
		$next_link = '<span class="disabled">' . $next_link_text . '</span>';
	}

	// Display pagination
	printf( '<nav class="navigation paging-navigation"><h4 class="screen-reader-text">%1$s</h4>%2$s %3$s %4$s</nav>',
		esc_html__( 'Page navigation', 'coup-shop' ),
		$prev_link,
		paginate_links( $pagination ),
		$next_link
	);

}


/**
 * List all categories
 *
 * @since coup 1.0
 */
function coup_categories_filter( $type ) {

	if ($type == 'portfolio') {


		$categories_list = get_terms( 'jetpack-portfolio-type' );

		if ( ! empty( $categories_list ) && ! is_wp_error( $categories_list ) ) {

			if ( ! is_home() ) {
				if ( isset( get_queried_object()->term_id ) ) {
					$term_id = get_queried_object()->term_id;
				} else {
					$term_id = 0;
				}
			}

			$categories_list_display = '<ul class="category-filter">';
			$count_all_posts = wp_count_posts('jetpack-portfolio','readable');

			if ( is_tax( 'jetpack-portfolio-type' ) ) {

				if ( coup_return_portfolio_pages_number() > 1  ) {

					foreach ($categories_list as $category) {
						$count = $category->count;
						$categories_list_display .= '<li><a href="' . esc_url( get_permalink( $category->term_id ) ) . '">' . $category->name . '<span>' . $count . '</span></a></li>';
					}
				  } else {
					$categories_list_display .= '<li><a href="' . esc_url( get_permalink( coup_return_portfolio_page( 'id' ) ) ) . '">' . esc_html__( 'All Projects', 'coup-shop' ) . '<span>' . $count_all_posts->publish . '</span></a></li>';
				}

			}
			else {

				if ( coup_return_portfolio_pages_number() > 1  ) {
					foreach ($categories_list as $category) {
						if ( get_the_ID() == $category->term_id ) {
							$active_class = 'cat-active';
						} else {
							$active_class = '';
						}

						$count = $category->count;
						$categories_list_display .= '<li class="' . esc_attr( $active_class ) . '"><a href="' . esc_url( get_permalink( $category->term_id ) ) . '">' . $category->name . '<span>' . $count . '</span></a></li>';
					}
				  } else {
					$categories_list_display .= '<li class="cat-active"><a href="' . esc_url(get_permalink()) . '">' . esc_html__( 'All Projects', 'coup-shop' ) . '<span>' . $count_all_posts->publish . '</span></a></li>';
				}

			}

			foreach ( $categories_list as $category ) {

				if ( $category->term_id == $term_id ) {
					$active_class = 'cat-active';
				} else {
					$active_class = '';
				}

				$count = $category->count;
				$categories_list_display .= '<li class="' . esc_attr( $active_class ) . '"><a href="' . esc_url( get_term_link( $category ) ) . '">' . $category->name . '<span>' . $count . '</span></a></li>';

			}

			$categories_list_display .= '</ul>';

			echo $categories_list_display;

		}

	} else if ($type == 'index') {

		$categories_list = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC'
		) );

		if ( ! empty( $categories_list ) && ! is_wp_error( $categories_list ) ) {

			$categories_list_display = '<ul class="category-filter">';

			if (! is_archive()) {

				$count_all_posts = wp_count_posts('post','readable');
				$categories_list_display .= '<li class="cat-active"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'All Stories', 'coup-shop' ) . '<span>' . $count_all_posts->publish . '</span></a></li>';

				foreach( $categories_list as $category ) {
					$count = $category->category_count;
					$category_link = get_category_link( $category );
					$categories_list_display .= '<li><a href="' . esc_url( $category_link ) . '">' . $category->name . '<span>' . $count . '</span></a></li>';

				}

			} else {
				if ( isset( get_queried_object()->term_id ) ) {
					$category_id = get_queried_object()->term_id;
				} else {
					$category_id = 0;
				}

				$count_all_posts = wp_count_posts('post','readable');
				$categories_list_display .= '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'All Stories', 'coup-shop' ) . '<span>' . $count_all_posts->publish . '</span></a></li>';

				foreach( $categories_list as $category ) {
					if ( $category->term_id == $category_id ) {
						$active_class = 'cat-active';
					} else {
						$active_class = '';
					}

					$count = $category->category_count;
					$category_link = get_category_link( $category );
					$categories_list_display .= '<li class="' . $active_class . '"><a href="' . esc_url( $category_link ) . '">' . $category->name . '<span>' . $count . '</span></a></li>';

				}
			}

			$categories_list_display .= '</ul>';

			echo $categories_list_display;
		}
	} else if ($type == 'shop') {

		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'show_count'   => 1,
			'pad_counts'   => 1,
			'hierarchical' => 1,
			'parent'       => 0,
			'title_li'     => '',
			'hide_empty'   => 1
		);

		$top_level_categories = get_categories( $args );

		if ( ! empty( $top_level_categories ) && ! is_wp_error( $top_level_categories ) ) {

			$categories_list_display = '<ul class="category-filter">';

			if ( isset( get_queried_object()->term_id ) ) {
				$term_id = get_queried_object()->term_id;
			} else {
				$term_id = 0;
			}

			if ( ! is_shop() ) {
				$count_posts = wp_count_posts('product');
				$count_all_products = $count_posts->publish;
				$categories_list_display .= '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'All Products', 'coup-shop' ) . '<span>' . $count_all_products . '</span></a></li>';

			} else {
				$count_posts = wp_count_posts('product');
				$count_all_products = $count_posts->publish;
				$categories_list_display .= '<li class="cat-active"><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'All Products', 'coup-shop' ) . '<span>' . $count_all_products . '</span></a></li>';
			}

			foreach ($top_level_categories as $cat) {

				if ( $term_id == $cat->term_id ) {
					$active_class = 'cat-active';
				} else {
					$active_class = '';
				}

				// check if there is first child categories
				$print_child_cat = coup_hierarchical_term_tree($cat->term_id);

				$categories_list_display .= '<li class="' . esc_attr( $active_class ) . '"><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name . '<span class="count">' . $cat->count . '</span></a>' . $print_child_cat . '</li>';
			}

			$categories_list_display .= '</ul>';

			echo $categories_list_display;

		}
	}
}

function coup_hierarchical_term_tree($parent_id = 0) {
	$child_cat = '';

	$args = array(
		'parent' => $parent_id,
	);
	$next = get_terms('product_cat', $args);

	if ($next) {
		$child_cat .= '<button class="category-dropdown"><span class="screen-reader-text">toggle child menu</span><i class="icon-down"></i></button>';

		$child_cat .= '<ul>';

		foreach ($next as $cat) {
			$child_cat .= '<li><a href="' . get_term_link($cat->slug, $cat->taxonomy) . '" title="' . sprintf(__("View all products in %s", 'coup-shop'), $cat->name) . '" ' . '>' . $cat->name . ' <span class="count">' . $cat->count . '</span></a>';
			$child_cat .= $cat->term_id !== 0 ? coup_hierarchical_term_tree($cat->term_id) : null;
			$child_cat .= '</li>';
		}

		$child_cat .= '</ul>';
	}

	return $child_cat;
}

/**
 * Insert sharedaddy
 *
 * @since coup 1.0
 */
function coup_insert_sharedaddy() {

	// Disabled for this post?

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'sharedaddy' ) ) {

		$share_content = sharing_display( '', false );

		if ( function_exists( 'sharing_display' ) && !empty($share_content) ) {
			echo '<div class="sharedaddy-holder"><i class="icon-share"></i>';
			echo $share_content;
			echo '</div>';
		}
	};
}


// Change Fonts

function coup_change_fonts() {

	// Get all customizer font settings
	$headings_font_family   = get_theme_mod( 'headings_font_family', 'default' );
	$paragraphs_font_family  = get_theme_mod( 'paragraphs_font_family', 'default' );
	$navigation_font_family = get_theme_mod( 'navigation_font_family', 'default' );

	$change_fonts_style = '';

	// Headings
	if ( 'default' != $headings_font_family ) {

		$headings_font_weight = get_theme_mod( 'headings_font_weight', 'normal' );
		$headings_font_italic = false;

		if ( strpos( $headings_font_weight, 'italic' ) !== false ) {
			$headings_font_italic = true;
			$headings_font_weight = str_replace( 'italic', '', $headings_font_weight );
		}

		if ( 'regular' == $headings_font_weight ) {
			$headings_font_weight = '';
		}

		if ( $headings_font_italic ) {
			$headings_font_italic_css = 'font-style: italic;';
		} else {
			$headings_font_italic_css = 'font-style: normal;';
		}

		$change_fonts_style .= '

			h1, h1>a, h2, h2>a, h3, h3>a, h4, h4>a, h5, h5>a, h6, h6>a,
			.blog article.format-quote .entry-content blockquote,
			.archive article.format-quote .entry-content blockquote,
			.blog article.format-quote .entry-content q,
			.archive article.format-quote .entry-content q,
			.products .product .woocommerce-loop-product__title,
			.woocommerce .related>h2, .woocommerce.single .up-sells>h2, .woocommerce.single .cross-sells>h2,
			.mini-cart .widget_shopping_cart ul.cart_list li a:not(.remove) {
				font-family: '. esc_html( $headings_font_family ) .', Verdana, Geneva, sans-serif;
				font-weight: '. esc_html( $headings_font_weight == '' ? 'normal' : $headings_font_weight ).';
				'. $headings_font_italic_css .'
			}
		';
	}

	// Paragraph
	if ( 'default' != $paragraphs_font_family ) {

		$paragraphs_font_weight = get_theme_mod( 'paragraphs_font_weight', 'normal' );
		$paragraphs_font_italic = false;

		if ( strpos( $paragraphs_font_weight, 'italic' ) !== false ) {
			$paragraphs_font_italic = true;
			$paragraphs_font_weight = str_replace( 'italic', '', $paragraphs_font_weight );
		}

		if ( 'regular' == $paragraphs_font_weight ) {
			$paragraphs_font_weight = '';
		}

		if ( $paragraphs_font_italic ) {
			$paragraphs_font_italic_css = 'font-style: italic;';
		} else {
			$paragraphs_font_italic_css = 'font-style: normal;';
		}

		$change_fonts_style .= '

			body,
			.blog article.format-quote .entry-content blockquote cite,
			.archive article.format-quote .entry-content blockquote cite {
				font-family: '.esc_html( $paragraphs_font_family ).', Verdana, Geneva, sans-serif;
				font-weight: '.esc_html( $paragraphs_font_weight == '' ? 'normal' : $paragraphs_font_weight ).';
				'. $paragraphs_font_italic_css .'
			}

		';
	}

	// Header Navigation
	if ( 'default' != $navigation_font_family ) {

		$navigation_font_weight = get_theme_mod( 'navigation_font_weight', 'normal' );
		$navigation_font_italic = false;

		if ( strpos( $navigation_font_weight, 'italic' ) !== false ) {
			$navigation_font_italic = true;
			$navigation_font_weight = str_replace( 'italic', '', $navigation_font_weight );
		}

		if ( 'regular' == $navigation_font_weight ) {
			$navigation_font_weight = '';
		}

		if ( $navigation_font_italic ) {
			$navigation_font_italic_css = 'font-style: italic;';
		} else {
			$navigation_font_italic_css = 'font-style: normal;';
		}

		$change_fonts_style .= '

			.main-navigation,
			.site-title,
			.site-title a,
			.side-nav,
			.search-wrap .search-field,
			.category-filter,
			.posts-navigation .next-trig, .posts-navigation .prev-trig,
			.cart-touch a span,
			body .sd-sharing-enabled div h3.sd-title {
				font-family: '.esc_html( $navigation_font_family ).', Verdana, Geneva, sans-serif;
				font-weight: '.esc_html( $navigation_font_weight == '' ? '500' : $navigation_font_weight ).';
				'. $navigation_font_italic_css .'
			}

		';

	}

	if ( 'default' != $headings_font_family || 'default' != $paragraphs_font_family || 'default' != $navigation_font_family ) {

		return $change_fonts_style;

	}

}


/**
 * Add Cart Items Count
 *
 * @since  coup 1.0
 */
if ( ! function_exists( 'coup_cart_link' ) ) {

	function coup_cart_link() { ?>
		<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_html_e( 'View your order summary', 'coup-shop' ); ?>">
			<span><?php esc_html_e( 'Cart', 'coup-shop' ); ?><sup class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></sup></span>
			<i class="icon-cart"></i>
		</a>

<?php

	}
}

/**
 * Display Header Mini Cart
 *
 * @since  coup 1.0
 * @uses  coup_is_woocommerce_activated() check if WooCommerce is activated
 */
if ( ! function_exists( 'coup_woo_header_cart' ) ) {

	function coup_woo_header_cart() {

		if ( coup_is_woocommerce_activated() ) {

			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
		?>
		<div class="cart-header">
			<h4><?php esc_html_e( 'Cart', 'coup-shop' ); ?></h4>
		</div> <?php
		the_widget( 'WC_Widget_Cart', 'title=' );

		}
	}

}

/**
 * Refreshes mini cart after ajax add to cart
 */
function coup_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<sup class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></sup>

	<?php

	$fragments['a.cart-contents sup'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'coup_header_add_to_cart_fragment' );



// display next prev product on single product page

function coup_product_navigation() {
	global $post;
	// get categories
	$terms = wp_get_post_terms( $post->ID, 'product_cat' );
	foreach ( $terms as $term ) $cats_array[] = $term->term_id;
	// get all posts in current categories
	$query_args = array(
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $cats_array
	)));

	$r = new WP_Query($query_args);
	// show next and prev only if we have 3 or more
	if ($r->post_count > 2) {
		$prev_product_id = -1;
		$next_product_id = -1;
		$found_product = false;
		$i = 0;
		$first_product_index = $i;
		$current_product_index = $i;
		$current_product_id = get_the_ID();
		if ($r->have_posts()) {
			while ($r->have_posts()) {
				$r->the_post();
				$current_id = get_the_ID();
				if ($current_id == $current_product_id) {
					$found_product = true;
					$current_product_index = $i;
				}
				$is_first = ($current_product_index == $first_product_index);
				if ($is_first) {
					$prev_product_id = get_the_ID(); // if product is first then 'prev' = last product
				} else {
					if (!$found_product && $current_id != $current_product_id) {
						$prev_product_id = get_the_ID();
					}
				}
				if ($i == 0) { // if product is last then 'next' = first product
					$next_product_id = get_the_ID();
				}
				if ($found_product && $i == $current_product_index + 1) {
					$next_product_id = get_the_ID();
				}
				$i++;
			}

			if ($prev_product_id != -1) {

				$prev_text 		= esc_html__( 'Previous Product', 'coup-shop' );
				$prev_post_text = '<a href="' . esc_url( get_permalink( $prev_product_id ) ) . '"><span class="prev-trig">' . $prev_text . '<i class="icon-left"></i></span>';

				$prev_post_navigation = '<div class="nav-previous">';
				$prev_post_navigation .= $prev_post_text;
				$prev_post_navigation .= '<div class="prev-title">';
				$prev_post_navigation .= '<span class="post-title">' . get_the_title( $prev_product_id ) . '</span>';
				$prev_post_navigation .= '</div></div></a>';

			}


			if ($next_product_id != -1) {

				$next_text 		= esc_html__( 'Next Product', 'coup-shop' );
				$next_post_text = '<a href="' . esc_url( get_permalink( $next_product_id ) ) . '"><span class="prev-trig">' . $next_text . '<i class="icon-right"></i></span>';

				$next_post_navigation = '<div class="nav-previous">';
				$next_post_navigation .= $next_post_text;
				$next_post_navigation .= '<div class="prev-title">';
				$next_post_navigation .= '<span class="post-title">' . get_the_title( $next_product_id ) . '</span>';
				$next_post_navigation .= '</div></div></a>';
			}

			// Post navigation
			$post_navigation = $next_post_navigation . $prev_post_navigation;

			echo _navigation_markup( $post_navigation );
		}
		wp_reset_query();
	}
}

/**
 * Front Page Slider
 */
function coup_front_slider() {

	$front_slider    = get_theme_mod( 'front_page_slider_enable', 'hide-slider' );

	if ( $front_slider !== 'hide-slider' ) :

		$args = array(
			'post_type'      => 'slide',
			'posts_per_page' => 10,
		);


		$front_slides = new WP_Query( $args );

		if ( $front_slides->have_posts() ) : ?>

			<div class="featured-slider-wrapper">
				<div class="featured-slider">

					<?php

						while ( $front_slides->have_posts() ) : $front_slides->the_post();

							$slide_desc      = get_post_meta( get_the_ID(), 'coup_slide_desc', true );
							$slide_link      = get_post_meta( get_the_ID(), 'coup_slide_link', true );
							$slide_txt_align = get_post_meta( get_the_ID(), 'coup_slider_text_alignment', true );

					?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if ( $front_slider == 'medium-slider' ) { ?>


							<?php coup_hero_featured_image(); ?>
							<div class="entry-text <?php echo esc_attr( $slide_txt_align ); ?> ">

								<?php if ( '' != $slide_link ) { ?>

									<a href="<?php echo esc_url( $slide_link ); ?>">
										<div class="slider-headline-wrapper fp-slider-headline-wrapper-<?php echo get_the_ID() ?>">
											<?php the_title( '<h2 class="slider-headline fp-slider-headline-'. get_the_ID() .'">', '</h2>' ); ?>
										</div>
									</a>

								<?php } else { ?>
									<div class="slider-headline-wrapper fp-slider-headline-wrapper-<?php echo get_the_ID() ?>">
									<?php the_title( '<h2 class="slider-headline fp-slider-headline-'. get_the_ID() .'">', '</h2>' ); ?>
									</div>
								<?php } ?>

								<?php

									printf( '<div class="slider-text fp-slider-text-'. get_the_ID() .'">%s</div>', wpautop( $slide_desc ) );

								?>

							</div>

						<?php } else { ?>

							<div class="entry-text <?php echo esc_attr( $slide_txt_align ); ?> ">

								<header class="entry-header">

								<?php if ( '' != $slide_link ) { ?>

									<a href="<?php echo esc_url( $slide_link ); ?>">
										<div class="slider-headline-wrapper fp-slider-headline-wrapper-<?php echo get_the_ID() ?>">
											<?php the_title( '<h2 class="slider-headline fp-slider-headline-'. get_the_ID() .'">', '</h2>' ); ?>
										</div>
									</a>

								<?php } else { ?>
									<div class="slider-headline-wrapper fp-slider-headline-wrapper-<?php echo get_the_ID() ?>">
										<?php the_title( '<h2 class="slider-headline fp-slider-headline-'. get_the_ID() .'">', '</h2>' ); ?>
									</div>
								<?php } ?>

								</header>
								<div class="entry-content">

								<?php

									printf( '<div class="slider-text fp-slider-text-'. get_the_ID() .'">%s</div>', wpautop( $slide_desc ) );

								?>
								</div>
							</div>
							<?php coup_hero_featured_image(); ?>

						<?php }; ?>
						</article>

					<?php endwhile; ?>

				</div>
			</div>

		<?php

		else :

			return;

		endif;

		wp_reset_postdata();

	endif; // if slider is enabled

}

function coup_front_slider_style() {

	$front_slider    = get_theme_mod( 'front_page_slider_enable', 'hide-slider' );

	if ( $front_slider !== 'hide-slider' ) :

		$args = array(
			'post_type'      => 'slide',
			'posts_per_page' => 10,
		);


		$front_slides = new WP_Query( $args );

		if ( $front_slides->have_posts() ) :

			$slider_style = ''; ?>

			<?php

				while ( $front_slides->have_posts() ) : $front_slides->the_post();

					// Title & Text
					$slide_h_font      = get_post_meta( get_the_ID(), 'coup_slide_headline_size', 50 );
					if (!is_numeric($slide_h_font) ) {
						$slide_h_font = 50;
					}
					$slide_h_font_size = $slide_h_font / 18;
					$slide_h_color     = get_post_meta( get_the_ID(), 'coup_headline_color', '#000' );
					$slide_txt_color   = get_post_meta( get_the_ID(), 'coup_text_color', '#000' );

					// Slider Colors
					$slide_h_color       = get_post_meta( get_the_ID(), 'coup_headline_color', '#000' );
					$slide_h_hover_color = get_post_meta( get_the_ID(), 'coup_headline_hover_color', '#ccc' );

				$slider_style .=  ' size:' . $slide_h_font . '<

					/* FRONT PAGE TEMPLATE */
					.fp-slider-headline-'.get_the_ID().' {
						color: '.esc_attr( $slide_h_color ).';
						-webkit-transition: color .3s;
						-moz-transition: color .3s;
						-ms-transition: color .3s;
						-o-transition: color .3s;
						transition: color .3s;
					}

					.fp-slider-headline-wrapper-'.get_the_ID().' {
						font-size: '.esc_attr( $slide_h_font_size ).'rem !important;
					}

					 a:hover .fp-slider-headline-'.get_the_ID().' {
						color: '.esc_attr( $slide_h_hover_color ).';
					}

					.fp-slider-text-'.get_the_ID().' {
						color: '.esc_attr( $slide_txt_color ).';
					}

				'

			;  ?>

			<?php

				endwhile;

				wp_reset_postdata();

				return $slider_style;

			endif; ?>

		<?php

	endif; // if slider is enabled

}


function coup_call_header_video() {

	if ( is_front_page() && !is_paged() && has_custom_header() ) {?>
		<div class="header-video-wrapper">
			<?php
			the_custom_header_markup();

			$header_section_text = get_theme_mod( 'header_section_text', '');
			if ($header_section_text != '') {
				echo '<div class="header-text">' . $header_section_text . '</div>';
			}
			?>
		</div>
		<?php
	}
}
