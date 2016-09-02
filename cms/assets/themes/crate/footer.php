<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage crate
 * @since crate 1.0
 */
?>
		</section><!-- #main -->
		<footer role="contentinfo">
		  <?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>
<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>
			<a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			<div class="credit">
				<a href="http://cornershopcreative.com"><span class="text">Crafted by Cornershop Creative</span><i></i></a>
			</div>
		</footer><!-- footer -->
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */
	wp_footer();
?>
  </div>
	</body>
</html>