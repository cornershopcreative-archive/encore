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

			<div class="hero-image">
				<?php the_post_thumbnail( 'square-md' ); ?>
			</div>

			<div class="hero-text">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<p class="entry-subtitle"><strong><?php echo wp_kses_post( get_field( 'subtitle' ) ); ?></strong></p>

				<?php echo wp_kses_post( get_field( 'quote' ) ); ?>
			</div>

		</div>
	</header><!-- .entry-header -->

	<footer class="entry-actions entry-actions-before container-8">

		<a href="/blog" class="back-link">Back to Posts</a>


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

	<footer class="entry-actions entry-actions-after container-8">

		<div class="share-buttons">
			<h2>Share</h2>
			<?php echo do_shortcode( '[addthis_sharing_buttons]' ); ?>
		</div>

		<div class="button-group">
			<?php crate_back_link( array(
				'class' => 'button button-gold button-solid'
			) ); ?>
		</div>

	</footer>

</article><!-- #post-## -->
