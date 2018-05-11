<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package coup
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function coup_body_classes( $classes ) {

	// Adds a class of tk-theme-frontend when viewing frontend.
	if ( !is_admin() ) {
		$classes[] = 'tk-theme-frontend';
	}

	// Get blog layout setting
	$blog_layout        = get_theme_mod( 'blog_layout_setting', 'standard-layout' );
	$shop_layout        = get_theme_mod( 'shop_layout_setting', 'layout-4' );
	$featured_slider    = get_theme_mod( 'front_page_slider_enable', 'hide-slider' );

	if ( ( is_archive() && !is_tax( 'jetpack-portfolio-type' ) && !is_post_type_archive( 'portfolio' ) ) || is_home() ) {
	    $classes[] = esc_attr( $blog_layout );
	}

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if (coup_is_woocommerce_activated()) {
		if ( is_woocommerce() || is_page_template( 'templates/woocommerce-featured-products-page.php' ) ) {
			$classes[] = 'woo-' . esc_attr( $shop_layout );
		}
	}

	if ( $featured_slider !== 'hide-slider' && is_front_page() && !is_paged() ) {

		$args = array(
			'post_type'      => 'slide',
			'posts_per_page' => 10,
		);

		$front_slides = new WP_Query( $args );

		if ( $featured_slider !== 'hide-slider' && is_front_page() && $front_slides->have_posts() ) {
			$classes[] = 'show-slider';
			$classes[] = esc_attr( $featured_slider );
		}
	}

	if ( is_front_page() && !is_paged() && has_custom_header() ) {
		$classes[] = 'header-image-section';
	}

	return $classes;
}
add_filter( 'body_class', 'coup_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function coup_post_classes( $classes ) {

    if ( !has_post_thumbnail() ) {
        $classes[] = 'no-featured-content';
    }

    return $classes;
}
add_filter( 'post_class', 'coup_post_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function coup_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'coup_pingback_header' );

/**
 * Check for embed content in post and extract
 *
 * @since coup 1.0
 */
function coup_get_embeded_media() {
	$content   = get_the_content();
	$embeds    = get_media_embedded_in_content( $content );
	$video_url = wp_extract_urls( $content );

	if ( !empty( $embeds ) ) {

		// Check what is the first embed containg video tag, youtube or vimeo
		foreach( $embeds as $embed ) {
			if ( strpos( $embed, 'video' ) || strpos( $embed, 'youtube' ) || strpos( $embed, 'vimeo' ) ) {

				$id   = 'coup-shop' . rand();
				$href = "#TB_inline?height=640&width=1000&inlineId=" . $id;

				if ( !is_single() && has_post_thumbnail() ) {

					$video_url = '<div id="' . $id . '" style="display:none;">' . $embed . '</div>';
					$video_url .= '<div class="featured-content featured-image"><a class="thickbox" title="' . get_the_title() . '" href="' . $href . '">' . get_the_post_thumbnail() . '</a></div>';

					return $video_url;

				} else {

					return $embed;

				}

			}
		}

	} else {

		if ( $video_url ) {

			if ( strpos( $video_url[0], 'youtube' ) || strpos( $video_url[0], 'vimeo' ) ) {

				$id   = 'coup-shop' . rand();
				$href = "#TB_inline?height=640&width=1000&inlineId=" . $id;

				if ( !is_single() && has_post_thumbnail() ) {

					$video_url = '<div id="' . $id . '" style="display:none;">' . wp_oembed_get( $video_url[0] ) . '</div>';
					$video_url .= '<div class="featured-content featured-image"><a class="thickbox" title="' . get_the_title() . '" href="' . $href . '">' . get_the_post_thumbnail() . '</a></div>';

					return $video_url;

				} else {

					return wp_oembed_get( $video_url[0] );

				}

			}

		} else {
			// No video embedded found
			return $content;
		}
	}
}


/**
 * Filter content for gallery post format
 *
 * @since  coup 1.0
 */
function coup_filter_post_content( $content ) {

    if ( 'video' == get_post_format() && 'post' == get_post_type() ) {
        $video_content = get_media_embedded_in_content( $content );
        $video_url     = wp_extract_urls( $content );

        if ( $video_content ) {
            $content = str_replace( $video_content, '', $content );
        }

        if ( $video_url ) {
            if ( strpos( $video_url[0], 'youtube' ) || strpos( $video_url[0], 'vimeo' ) ) {
                $content = str_replace( $video_url[0], '', $content );
            }
        }

    }

    if ( 'gallery' == get_post_format() && 'post' == get_post_type() ) {
        $regex   = '/\[gallery.*]/';
        $content = preg_replace( $regex, '', $content, 1 );
    }

    return $content;
}
add_filter( 'the_content', 'coup_filter_post_content' );

/**
 * Get Thumbnail Image Size Class
 *
 * @since coup 1.0
 */
function coup_get_featured_image_class() {

    $thumb_class            = '';
    $url                    = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

    if ( $url == false ) {
    	return;
    }

    list( $width, $height ) = getimagesize( $url );

    if ( $width > $height || $width == $height ) {
        $thumb_class = 'horizontal-img';
    } else {
        $thumb_class = 'vertical-img';
    }

    return $thumb_class;

}

/**
 * Change tag cloud font size
 *
 * @since  coup 1.0
 */
function coup_widget_tag_cloud_args( $args ) {
    $args['largest']  = 14;
    $args['smallest'] = 14;
    $args['unit']     = 'px';
    return $args;
}
add_filter( 'widget_tag_cloud_args', 'coup_widget_tag_cloud_args' );

/**
 * Remove parenthesses from excerpt
 *
 * @since coup 1.0
 */
function coup_excerpt_more( $more ) {
    return '';
}
add_filter( 'excerpt_more', 'coup_excerpt_more' );

/**
 * Parenthesses remove
 *
 * Removes parenthesses from category and archives widget
 *
 * @since coup 1.0
 */
function coup_categories_postcount_filter( $variable ) {
	$variable = str_replace( '(', '<span class="post_count"> ', $variable );
	$variable = str_replace( ')', '</span>', $variable );
	return $variable;
}
add_filter( 'wp_list_categories','coup_categories_postcount_filter' );

function coup_archives_postcount_filter( $variable ) {
	$variable = str_replace( '(', '<span class="post_count"> ', $variable );
	$variable = str_replace( ')', '</span>', $variable );
	return $variable;
}
add_filter( 'get_archives_link','coup_archives_postcount_filter' );




/**
 * Get title of page that uses portfolio template
 *
 * @return  String [Page title]
 */
function coup_return_portfolio_page( $type ) {
	$pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'templates/gallery-page.php'
	) );

	if ( !empty( $pages ) ) {
		if ( 'id' == $type ) {
			return $pages[0]->ID;
		} else {
			return $pages[0]->post_title;
		}
	}
}

/**
 * Get the number of porfolio pages
 *
 * @return  String [Pages count]
 */
function coup_return_portfolio_pages_number() {
    $pages = get_pages( array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'templates/portfolio-page.php'
    ) );

    if ( !empty( $pages ) ) {
        return count($pages);
    } else {
        return 0;
    }
}

/**
* A title for the search.php template that displays the total number of search results and search terms
*
* @return  String [Search results count]
*/
function coup_search_results_count() {
	if( is_search() ) {

		global $wp_query;

		$result_count = esc_html__( 'Results', 'coup-shop' );
		$result_count .= ' ';

		$result_count .= $wp_query->found_posts;

		return $result_count;

	}
}

/**
 * Template for displaying search forms in coup
 * code taken from Twenty Seventeen
 *
 * @package WordPress
 * @subpackage coup
 * @since 1.0
 * @version 1.0
 */

function coup_custom_search_form() {

	$unique_id = esc_attr( uniqid( 'search-form-' ) );

	$search_form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<label for="' . $unique_id . '">
			<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'coup-shop' ) . '</span>
		</label>
		<input type="search" id="' . $unique_id . '" class="search-field" placeholder="' . esc_attr_x( 'Search', 'placeholder', 'coup-shop' ) . '" value="' . get_search_query() . '" name="s" />
		<button type="submit" class="search-submit">
			<i class="icon-search"></i>
			<span class="screen-reader-text">
				' . _x( 'Search', 'submit button', 'coup-shop' ) .'
				</span>
			</button>
	</form>';

	echo $search_form;

}

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'coup_is_woocommerce_activated' ) ) {
    function coup_is_woocommerce_activated() {
        return class_exists( 'woocommerce' ) ? true : false;
    }
}

/* Convert hexdec color string to rgb(a) string */

function coup_hex2rgba( $color, $opacity = false ) {

    $default = 'rgb(0,0,0)';

    //Return default if no color provided
    if ( empty( $color ) ) {
        return $default;
    }

    //Sanitize $color if "#" is provided
    if ( $color[0] == '#' ) {
        $color = substr( $color, 1 );
    }

    //Check if color has 6 or 3 characters and get values
    if ( strlen( $color ) == 6) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

    //Convert hexadec to rgb
    $rgb =  array_map( 'hexdec', $hex );

    //Check if opacity is set(rgba or rgb)
    if ( $opacity ) {
        if ( abs( $opacity ) > 1 ) {
            $opacity = 1.0;
        }
        $output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode( ",",$rgb ) . ')';
    }

    // Return rgb(a) color string
    return $output;
}

/**
 * Do shortcode function instead calling do_shortcode
 *
 */
function coup_do_shortcode_function( $tag, array $atts = array(), $content = null ) {

	 global $shortcode_tags;

	 if ( ! isset( $shortcode_tags[ $tag ] ) )
			 return false;

	 return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}
