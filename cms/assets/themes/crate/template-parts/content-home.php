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
	<header class="entry-header hero hero-float hero-float-left">
		<div class="container-10">

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="hero-image">

					<svg class="logo logo-surround" viewBox="0 0 50 50">
						<use xlink:href="#icon-logo"></use>
					</svg>

					<?php
					// Get the URL for the featured video, if there is one.
					$video_url = crate_get_oembed_autoplay_url( get_field( 'video' ) );
					?>

					<?php if ( $video_url ) : ?>
						<a href="<?php echo esc_url( $video_url ); ?>" class="lightbox-embed-link play-button-link">
					<?php endif; ?>

							<?php the_post_thumbnail( 'square-md' ); ?>

					<?php if ( $video_url ) : ?>
						</a><!-- /.lightbox-embed-link -->
					<?php endif; ?>

				</div>
			<?php endif; ?>

			<div class="hero-text prose prose-compact">
				<?php echo get_field( 'subtitle' ); ?>
			</div>

		</div>
	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/sections' ); ?>

	<footer class="entry-footer">
		<?php // stuff in here ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
