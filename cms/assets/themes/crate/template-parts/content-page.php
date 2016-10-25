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

			<?php if ( $video_embed = get_field( 'video' ) ) : ?>
				<a href="#<?php echo esc_attr( $post->post_name . '-video' ); ?>" class="hero-video-link">
			<?php endif; ?>

			<picture class="hero-image">
				<?php
				list( $src ) = wp_get_attachment_image_src( get_post_thumbnail_id(), 'hero-lg' );
				if ( $src ) : ?>
					<source media="(min-width: 640px)" srcset="<?php echo esc_url( $src ); ?>">
				<?php endif; ?>
				<?php the_post_thumbnail( 'hero-sm' ); ?>
			</picture>

			<?php if ( $video_embed ) : ?>
				<?php error_log( $video_embed ); ?>
				<!-- <svg class="icon" viewBox="0 0 100 100">
					<use xlink:href="#icon-video-play"></use>
				</svg> -->
				</a><!-- /.hero-video-link -->
				<div class="hero-video" id="<?php echo esc_attr( $post->post_name . '-video' ); ?>">
					<?php echo $video_embed; ?>
				</div>
			<?php endif; ?>

		<?php endif; ?>
		<div class="hero-text prose prose-compact container-10 container-flex">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php echo wp_kses_post( get_field( 'subtitle' ) ); ?>
		</div>
	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/sections' ); ?>

	<footer class="entry-footer">
		<?php // stuff in here ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
