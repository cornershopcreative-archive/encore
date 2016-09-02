<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage crate
 * @since crate 1.0
 */

get_header(); ?>
<div id="main">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('prose'); ?>>
				<?php if ( is_front_page() ) { ?>
					<h2 class="entry-title"><?php the_title(); ?></h2>
				<?php } else { ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php } ?>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'crate' ), 'after' => '' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'crate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
				<?php comments_template( '', true ); ?>
<?php endwhile; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>