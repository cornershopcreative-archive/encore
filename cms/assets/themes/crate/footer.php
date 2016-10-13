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

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<nav class="nav-footer">
				<?php

				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'menu_id' => 'footer-menu-1',
					'menu_class' => 'menu menu-footer'
				) );

				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'menu_id' => 'footer-menu-2',
					'menu_class' => 'menu menu-footer'
				) );

				wp_nav_menu( array(
					'theme_location' => 'footer-1',
					'menu_id' => 'footer-menu-3',
					'menu_class' => 'menu menu-footer'
				) );

				?>
			</nav>
			<div class="site-info">
				<div class="contact">
					<p>&copy; Generation to Generation 2016. All rights reserved.<br/>
						P.O. Box 29542 | San Fransisco, CA 94129</p>
				</div>
				<div class="credits">
					<p>Designed by Ronik<br/>
						Crafted by Cornershop Creative</p>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
