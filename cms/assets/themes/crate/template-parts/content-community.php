<?php
/**
 * Template part for displaying single Learning Lab Communities
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header hero hero-float hero-float-right">
		<div class="container-10">

			<div class="hero-image">
				<?php the_post_thumbnail( 'square-md' ); ?>
			</div>

			<div class="hero-logo">
				<?php echo wp_get_attachment_image( get_field( 'logo_light' ) ); ?>
				<?php the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' ); ?>
			</div>

		</div>
	</header><!-- .entry-header -->

	<footer class="entry-actions entry-actions-before container-8">

		<?php crate_back_link(); ?>

		<div class="share-buttons">
			<h2>Share</h2>
			<?php echo do_shortcode( '[addthis_sharing_buttons]' ); ?>
		</div>

	</footer>

	<?php get_template_part( 'template-parts/sections' ); ?>

</article><!-- #post-## -->
