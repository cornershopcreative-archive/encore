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
	<header class="entry-header hero hero-float">
		<div class="container-10">

			<div class="hero-image">
				<?php the_post_thumbnail( 'grid-item-lg' ); ?>
			</div>

			<div class="hero-text">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<p class="entry-subtitle"><strong><?php echo wp_kses_post( get_field( 'subtitle' ) ); ?></strong></p>

				<?php echo wp_kses_post( get_field( 'quote' ) ); ?>
			</div>

		</div>
	</header><!-- .entry-header -->

	<footer class="entry-actions container-8">

		<?php // Show a "Back to _____" link.
		$story_bank_post = get_field( 'page_for_stories', 'option' );
		if ( $story_bank_post ) : ?>
			<a href="<?php echo esc_url( get_permalink( $story_bank_post ) ); ?>" class="back-link"><?php echo esc_html( sprintf(
				__( 'Back to %s', 'crate' ),
				get_the_title( $story_bank_post )
			) ); ?></a>
		<?php endif; ?>

		<div class="share-buttons">
			<h2>Share</h2>
			<?php echo do_shortcode( '[addthis_sharing_buttons]' ); ?>
		</div>

	</footer>


	<div class="entry-content container-8 prose">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'crate' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php // stuff in here ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
