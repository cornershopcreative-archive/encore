<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage crate
 * @since crate 1.0
 */
 header('X-UA-Compatible: IE=edge,chrome=1'); //kill compatibility mode
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
			wp_head();
		?>
		<?php /* If adding in Google Fonts or somesuch, also put an @import into editor-style.sass so they're available to TinyMCE */ ?>

		<?php
		/* We add some JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	?>
	</head>
	<body <?php body_class(); ?>>
  	<div id="fb-root"></div>
  	<div id="wrapper">
		<header role="banner">
			<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<p><?php bloginfo( 'description' ); ?></p>
			<?php
  			$custom_header = get_custom_header();
  			if ($custom_header->url) :
      		?>
    			<img src="<?php header_image(); ?>" height="<?php echo $custom_header->height; ?>" width="<?php echo $custom_header->width; ?>" alt="" />
    			<?php
  			endif;
			?>
			<div class="visible-print">
				<img src="<?php header_image(); ?>" height="<?php echo $custom_header->height / 2; ?>" width="<?php echo $custom_header->width / 2; ?>" alt="" id="print-logo">
				<div id="qrcode">
					<img src="https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=<?php echo current_page_url(); ?>" width="70" height="70">
					<div><?php echo current_page_url(); ?></div>
				</div>
			</div>
		</header>
		<nav id="access" role="navigation">
		  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
			<a id="skip" class="visuallyhidden focusable" href="#content" title="<?php esc_attr_e( 'Skip to content', 'crate' ); ?>"><?php _e( 'Skip to content', 'crate' ); ?></a>
			<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
			<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #access -->
		<section id="content" role="main">
