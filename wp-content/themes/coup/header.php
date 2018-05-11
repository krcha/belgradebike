<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package coup
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'coup-shop' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php if (has_custom_logo()) {
				the_custom_logo();
					?>
				<div class="site-information">
					<?php
			} ?>
					<?php
					if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
					endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo esc_html($description); /* WPCS: xss ok. */ ?></p>
					<?php
					endif; ?>
				<?php if (has_custom_logo()) { ?>
				</div>
				<?php }; ?>
		</div><!-- .site-branding -->

		<div class="sidebar-nav-holder">

			<?php if ( is_active_sidebar('sidebar-1') ) : ?>
				<div class="sidebar-button">
					<button class="sidebar-toggle">
						<span class="screen-reader-text"><?php esc_html_e( 'toggle open/close sidebar', 'coup-shop' ); ?></span>
						<i class="icon-sidebar"></i>
						<i class="icon-close"></i>
					</button>
				</div>
				<div class="sidebar-hide-scroll">
					<?php get_sidebar(); ?>
				</div>
			<?php endif; ?>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle hide" aria-controls="primary-menu" aria-expanded="false">
					<p class="open-menu"><?php esc_html_e( 'Menu', 'coup-shop' ); ?></p>
					<p class="close-menu"><?php esc_html_e( 'Close', 'coup-shop' ); ?></p>
					<div class="hamburger">
						<span></span>
						<span></span>
					</div>
				</button>
				<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
