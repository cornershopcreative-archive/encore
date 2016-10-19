<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Crate
 */

?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container container-10">
			<nav class="nav-footer">
				<?php

				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'menu_id' => 'footer-menu-1',
					'menu_class' => 'menu menu-footer menu-footer-1'
				) );

				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'menu_id' => 'footer-menu-2',
					'menu_class' => 'menu menu-footer menu-footer-2'
				) );

				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'menu_id' => 'footer-menu-3',
					'menu_class' => 'menu menu-footer menu-footer-3'
				) );

				?>
				<div class="footer-logos">
					<ul class="button-group">
						<li><a class="button button-badge button-facebook" href="#" target="_blank"><span class="screen-reader-text"><?php esc_html_e( 'Like Generation to Generation on Facebook', 'crate' ); ?></span></a></li>
						<li><a class="button button-badge button-twitter" href="#" target="_blank"><span class="screen-reader-text"><?php esc_html_e( 'Like Generation to Generation on Facebook', 'crate' ); ?></span></a></li>
					</ul>
					<p class="powered-by"><a class="icon-powered-by" href="http://encore.org/" target="_blank"><span class="screen-reader-text"><?php esc_html_e( 'Like Generation to Generation on Facebook', 'crate' ); ?></span></a></p>
				</div>
			</nav>
			<div class="site-info">
				<div class="contact">
					<p><?php crate_copyright_text(); ?></p>
					<p><?php crate_contact_info(); ?></p>
				</div>
				<div class="credits">
					<p>Designed by Ronik</p>
					<p>Crafted by Cornershop Creative</p>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
