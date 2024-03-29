<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Crate
 */

get_header(); ?>

	<main id="main" class="site-main" role="main">
	
		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

		<center><a class="button button-solid button-gold" style="margin-bottom: 100px;" href="/">Go to Home</a></center>

	</main><!-- #main -->

<?php
get_footer();
