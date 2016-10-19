<?php
/**
 * Template part for displaying posts, generally
 * This is a fallback for when other content-*.php parts are missing (e.g. loop, page, single, category, search, etc)
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header hero">
		<?php if ( has_post_thumbnail() ) : ?>
			<picture class="hero-image">
				<?php
				list( $src ) = wp_get_attachment_image_src( get_post_thumbnail_id(), 'hero-lg' );
				if ( $src ) : ?>
					<source media="(min-width: 640px)" srcset="<?php echo esc_url( $src ); ?>">
				<?php endif; ?>
				<?php the_post_thumbnail( 'hero-sm' ); ?>
			</picture>
		<?php endif; ?>
		<div class="hero-text prose prose-compact container-10">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<p>This is an example of subtitle text.</p>
			<p>This is some longer subtitle text. Lorem ipsum dolor sit amet etc. We shall see how this looks. Does it make the .hero-text thing way too wide, or does it still look OK?</p>
		</div>
	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/sections' ); ?>

	<footer class="entry-footer">
		<?php // stuff in here ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
