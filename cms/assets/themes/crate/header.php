<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Crate
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

<?php get_template_part( 'template-parts/header-svg' ); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'crate' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="site-branding">
				<?php
				if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a class="icon-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a></h1>
				<?php else : ?>
					<p class="site-title"><a class="icon-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a></p>
				<?php
				endif;
				?>
				<p class="powered-by">
					<a class="icon-powered-by" href="http://encore.org/" target="_blank"><span class="screen-reader-text"><?php esc_html_e( 'Powered by Encore.org', 'crate' ); ?></span></a>
				</p>
			</div><!-- .site-branding -->

			<div class="nav-toggle">
				<a class="icon-menu" href="#site-navigation"><span class="screen-reader-text"><?php esc_html_e( 'Show navigation', 'crate' ); ?></span></a>
			</div>

			<nav id="site-navigation" class="nav-primary" role="navigation">
				<?php wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id' => 'primary-menu',
					'menu_class' => 'menu menu-primary',
					'container' => false,
				) ); ?>
			</nav><!-- #site-navigation -->

			<div class="button-group button-group-expand nav-nag">
				<a class="button button-solid button-bright-blue" href="#" data-featherlight="#signup-modal" data-featherlight-variant="modalform"><?php echo esc_html_e( 'Count Me In', 'crate' ); ?></a>
			</div>
		</div>
	</header><!-- #masthead -->
