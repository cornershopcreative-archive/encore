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
	<header class="entry-header hero hero-float hero-float-right hero-with-text-bubbles">
		<div class="container-10">

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="hero-image">

					<?php
					// Get the URL for the featured video, if there is one.
					$video_url = crate_get_oembed_autoplay_url( get_field( 'video' ) );
					?>

					<?php if ( $video_url ) : ?>
						<a href="<?php echo esc_url( $video_url ); ?>" class="hero-video-link play-button-link">
					<?php endif; ?>

							<?php the_post_thumbnail( 'square-md' ); ?>

							<?php if ( $hero_callout_text = get_field( 'hero_callout_text' ) ) : ?>
								<div class="hero-callout-text">
									<p>

									<?php if ( $video_url ) : ?>
										<a href="<?php echo esc_url( $video_url ); ?>" class="hero-video-link">
									<?php endif; ?>

										<?php echo wp_kses_post( wptexturize( $hero_callout_text ) ); ?></p>
									<?php if ( $video_url ) : ?>
										</a>
									<?php endif; ?>

									</div>
								<?php endif; ?>

					<?php if ( $video_url ) : ?>
						</a><!-- /.hero-video-link -->
					<?php endif; ?>

				</div>
			<?php endif; ?>

			<div class="hero-text-bubble">
				<div class="hero-text">
					<?php echo get_field( 'subtitle' ); ?>
				</div>
			</div>

		</div>
	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/sections' ); ?>

	<footer class="entry-footer">
		<?php // stuff in here ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
