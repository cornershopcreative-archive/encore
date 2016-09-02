<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage crate
 * @since crate 1.0
 */

get_header(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
      <div id="main">
				<nav id="nav-above" class="navigation">
					<?php previous_post_link( '%link', '' . _x( '&larr;', 'Previous post link', 'crate' ) . ' %title' ); ?>
					<?php next_post_link( '%link', '%title ' . _x( '&rarr;', 'Next post link', 'crate' ) . '' ); ?>
				</nav><!-- #nav-above -->
				<article id="post-<?php the_ID(); ?>" <?php post_class('prose'); ?>>
					<h1><?php the_title(); ?></h1>
					<div class="entry-meta">
						<?php crate_posted_on(); ?>
					</div><!-- .entry-meta -->
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'crate' ), 'after' => '' ) ); ?>
					</div><!-- .entry-content -->
<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<footer id="entry-author-info">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'crate_author_bio_avatar_size', 60 ) ); ?>
						<h2><?php printf( esc_attr__( 'About %s', 'crate' ), get_the_author() ); ?></h2>
						<?php the_author_meta( 'description' ); ?>
						<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
							<?php printf( __( 'View all posts by %s &rarr;', 'crate' ), get_the_author() ); ?>
						</a>
					</footer><!-- #entry-author-info -->
<?php endif; ?>
					<footer class="entry-utility">
						<?php crate_posted_in(); ?>
						<?php edit_post_link( __( 'Edit', 'crate' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-utility -->
				</article><!-- #post-## -->
				<nav id="nav-below" class="navigation">
					<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'crate' ) . '</span> %title' ); ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'crate' ) . '</span>' ); ?></div>
				</nav><!-- #nav-below -->
				<?php comments_template( '', true ); ?>
<?php endwhile; // end of the loop. ?>
      </div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
