<?php
/**
 * coup functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package coup
 */

if ( ! function_exists( 'coup_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function coup_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on coup, use a find and replace
	 * to change 'coup-shop' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'coup-shop', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Add Support for WooCommerce
	 */
	add_theme_support( 'woocommerce' );

	// Add support for Woocommerce Gallery features: Image zoom / Magnification, Lightbox and Slider

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	add_theme_support( 'custom-header', array(
		'wp-head-callback' => 'coup_header_style',
		'video' => true,
		'flex-width'    => true,
		'width'         => 1920,
		'flex-height'   => true,
		'height'        => 1080,
	) );

	/**
	 * Add excerpt functionality to pages
	 */
	add_post_type_support( 'page', 'excerpt' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'coup-single-post', 900 );
	add_image_size( 'coup-archive-sticky', 1000 );
	add_image_size( 'coup-archive', 450 );
	add_image_size( 'coup-shuffle-sticky', 840 );
	add_image_size( 'coup-shuffle', 540 );


	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 740,
		'single_image_width' => 900,
	) );


	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'coup-shop' ),
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'video',
		'gallery',
		'quote',
		'link'
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 400,
		'height'      => 120,
		'flex-width'  => true,
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'coup_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'coup_setup' );


// add css for hideing header text
function coup_header_style() {
	/*
	 * If header text is set to display, let's bail.
	 */
	if ( display_header_text() ) {
		return;
	}
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	</style>
	<?php
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function coup_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'coup_content_width', 680 );
}
add_action( 'after_setup_theme', 'coup_content_width', 0 );

/**
 * Customize read more link.
 *
 * @link https://codex.wordpress.org/Customizing_the_Read_More
 */

function coup_modify_read_more_link() {
    return '';
}
add_filter( 'the_content_more_link', 'coup_modify_read_more_link' );


/**
 * disable sharedaddy and like buttons from standard place
 * they are added in single.php
 *
 */

function coup_jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
}

add_action( 'loop_start', 'coup_jptweak_remove_share' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function coup_widgets_init() {
	// Define sidebars
		$sidebars = array(
			'sidebar-1' => esc_html__( 'Sidebar', 'coup-shop' ),
			'sidebar-2' => esc_html__( 'Footer Widgets 1', 'coup-shop' ),
			'sidebar-3' => esc_html__( 'Footer Widgets 2', 'coup-shop' )
		);

		// Loop through each sidebar and register
		foreach ( $sidebars as $sidebar_id => $sidebar_name ) {
			register_sidebar( array(
				'name'          => $sidebar_name,
				'id'            => $sidebar_id,
				'description'   => sprintf( esc_html__( 'Widget area for %s', 'coup-shop' ), $sidebar_name ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h6 class="widget-title">',
				'after_title'   => '</h6>',
			) );
		}
}
add_action( 'widgets_init', 'coup_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function coup_scripts() {

	// Google Fonts
	wp_enqueue_style( 'coup-font-enqueue', coup_font_url(), array(), null );

	// Style
	wp_enqueue_style( 'coup-style', get_stylesheet_uri() );

	// Woocommerce styling
	if ( coup_is_woocommerce_activated() ) {
		wp_enqueue_style( 'coup-woocommerce-style', get_template_directory_uri() . '/woo-style.css' );
	}

	// Change Fonts Style
	$change_fonts_style = wp_strip_all_tags( coup_change_fonts() );
	wp_add_inline_style( 'coup-style', $change_fonts_style );

	// Front Slider Style

	$front_slider = get_theme_mod( 'front_page_slider_enable', 'hide-slider' );

	if ( $front_slider !== 'hide-slider' ) :

		$slider_style = esc_html(coup_front_slider_style());

		if ( coup_is_woocommerce_activated() ) {
			wp_add_inline_style( 'coup-woocommerce-style', $slider_style );
		}
		else {
			wp_add_inline_style( 'coup-style', $slider_style );
		}

	endif;

	// Change Colors Style

	$change_colors_style = wp_strip_all_tags( coup_change_colors() );

	if ( coup_is_woocommerce_activated() ) {
		wp_add_inline_style( 'coup-woocommerce-style', $change_colors_style );
	}
	else {
		wp_add_inline_style( 'coup-style', $change_colors_style );
	}

	// Scripts

	wp_enqueue_script( 'coup-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'coup-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'coup-slick', get_template_directory_uri() . '/js/slick.js', array(), '20151215', true );

	wp_enqueue_script( 'coup-mcustom-scrollbar', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// Main JS file
	wp_enqueue_script( 'coup-call-scripts', get_template_directory_uri() . '/js/common.js', array( 'jquery', 'masonry', 'thickbox' ), false, true );


	// WooCommerce JS file
	if ( coup_is_woocommerce_activated() ) {
		wp_enqueue_script( 'coup-call-woo-scripts', get_template_directory_uri() . '/js/woo-common.js', array( 'jquery', 'masonry' ), false, true );
	}


	// Change google fonts

	// Get all customizer font settings
	$headings_font_family   = get_theme_mod( 'headings_font_family', 'default' );
	$paragraphs_font_family  = get_theme_mod( 'paragraphs_font_family', 'default' );
	$navigation_font_family = get_theme_mod( 'navigation_font_family', 'default' );

	if ( 'default' != $headings_font_family ) {
		wp_enqueue_style( 'coup-headings-font', coup_generate_headings_google_font_url(), array(), '1.0.0' );
	}
	if ( 'default' != $paragraphs_font_family ) {
		wp_enqueue_style( 'coup-paragraph-font', coup_generate_paragraphs_google_font_url(), array(), '1.0.0' );
	}
	if ( 'default' != $navigation_font_family ) {
		wp_enqueue_style( 'coup-navigation-font', coup_generate_navigation_google_font_url(), array(), '1.0.0' );
	}

}
add_action( 'wp_enqueue_scripts', 'coup_scripts' );


/**
 * Enqueue admin scripts
 */
function coup_add_admin_scripts() {
	// Admin styles
	wp_enqueue_style( 'coup-admin-css', get_template_directory_uri() . '/inc/admin/admin.css' );
	wp_enqueue_style( 'wp-color-picker' );

	// Admin scripts
	wp_enqueue_media();
	wp_enqueue_script( 'my-upload' );
	wp_enqueue_script( 'jquery-ui' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script( 'coup-admin-js', get_template_directory_uri() . '/inc/admin/admin.js' );

	// Customizer settings
	wp_enqueue_script( 'coup-admin-scripts', get_template_directory_uri() . '/inc/customizer/js/customizer-settings.js', array(), false, false );

	$js_vars = array(
		'url'                     => get_template_directory_uri(),
		'admin_url'               => esc_url( admin_url( 'admin-ajax.php' ) ),
		'nonce'                   => wp_create_nonce( 'ajax-nonce' ),
		'default_text'            => esc_html__( 'Theme default', 'coup-shop' ),
		'headings_font_variant'   => get_theme_mod( 'headings_font_weight', 'default' ),
		'paragraphs_font_variant' => get_theme_mod( 'paragraphs_font_weight', 'default' ),
		'navigation_font_variant' => get_theme_mod( 'navigation_font_weight', 'default' )
	);
	wp_localize_script( 'coup-admin-scripts', 'js_vars', $js_vars );
}
add_action( 'admin_enqueue_scripts', 'coup_add_admin_scripts' );



/**
 * Adds theme default Google Fonts
 */
function coup_font_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by HK Grotesk, translate this to 'off'. Do not translate
    * into your own language.
    */
    $hk_grotesk    = esc_html_x( 'on', 'HK Grotesk font: on or off', 'coup-shop' );

    if ( 'off' === $hk_grotesk ) {

		return;

	} else {

        return get_template_directory_uri() . '/assets/fonts/hk-grotesk/stylesheet.css';

    }
}

/**
 * Generate headings google font url
 */
function coup_generate_headings_google_font_url() {
	$headings_font_family = get_theme_mod( 'headings_font_family', 'default' );
	$fonts_url = '';
	$headings_font_weight = get_theme_mod( 'headings_font_weight', 'normal' );

	if ( 'regular' == $headings_font_weight ) {
		$headings_font_weight = '';
	}

	$fonts_url = esc_url('https://fonts.googleapis.com/css?family='.str_replace( ' ', '+', $headings_font_family ).':'. $headings_font_weight.'');
	return $fonts_url;
}

/**
 * Generate paragraph google font url
 */
function coup_generate_paragraphs_google_font_url() {
	$paragraphs_font_family = get_theme_mod( 'paragraphs_font_family', 'default' );
	$fonts_url = '';
	$paragraphs_font_weight = get_theme_mod( 'paragraphs_font_weight', 'normal' );

	if ( 'regular' == $paragraphs_font_weight ) {
		$paragraphs_font_weight = '';
	}

	$fonts_url = esc_url('https://fonts.googleapis.com/css?family='.str_replace( ' ', '+', $paragraphs_font_family ).':'. $paragraphs_font_weight.'');
	return $fonts_url;
}

/**
 * Generate navigation google font url
 */
function coup_generate_navigation_google_font_url() {
	$navigation_font_family = get_theme_mod( 'navigation_font_family', 'default' );
	$fonts_url = '';
	$navigation_font_weight = get_theme_mod( 'navigation_font_weight', 'normal' );

	if ( 'regular' == $navigation_font_weight ) {
		$navigation_font_weight = '';
	}

	$fonts_url = esc_url('https://fonts.googleapis.com/css?family='.str_replace( ' ', '+', $navigation_font_family ).':'. $navigation_font_weight.'');
	return $fonts_url;
}

/**
 * One click demo import settings
 */
function coup_import_files() {
  return array(
    array(
      'import_file_name'           => 'coup Shop Demo 1',
      'import_file_url'            => get_template_directory_uri().'/import/demo1/content.xml',
      'import_widget_file_url'     => get_template_directory_uri().'/import/demo1/widgets.json',
      'import_customizer_file_url' => get_template_directory_uri().'/import/demo1/customizer.dat',
      'import_preview_image_url'   => get_template_directory_uri().'/import/demo1/screenshot.png',
      'import_notice'              => __( 'You can speed up development of your site by importing our sample site content like posts and images. The imported images are copyrighted and are for demo use only. Please replace them with your own images after importing.', 'coup-shop' ),
    ),
    array(
      'import_file_name'           => 'coup Shop Demo 2',
      'import_file_url'            => get_template_directory_uri().'/import/demo2/content.xml',
      'import_widget_file_url'     => get_template_directory_uri().'/import/demo2/widgets.json',
      'import_customizer_file_url' => get_template_directory_uri().'/import/demo2/customizer.dat',
      'import_preview_image_url'   => get_template_directory_uri().'/import/demo2/screenshot.png',
      'import_notice'              => __( 'You can speed up development of your site by importing our sample site content like posts and images. The imported images are copyrighted and are for demo use only. Please replace them with your own images after importing.', 'coup-shop' ),
    ),
  );
}
add_filter( 'pt-ocdi/import_files', 'coup_import_files' );


/**
 * Customize colors.
 */
require get_template_directory() . '/inc/change-colors.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load WooCommerce theme functions.
 */
if ( coup_is_woocommerce_activated() ) {
	require get_template_directory() . '/inc/woo/woo-functions.php';
	require get_template_directory() . '/inc/woo/woo-new-badge.php';
}

/**
 * Load plugin activation script file.
 */
require get_template_directory() . '/inc/plugin-activation.php';

/**
 * Load Meta Boxes Config
 */
require get_template_directory() . '/inc/metadata/meta-boxes.php';
