<?php
/**
 * The template for the site homepage.
 *
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage crate
 * @since crate 2.1.3
 */

get_header(); ?>
<div id="main">
<strong>This is the front page template</strong>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('prose'); ?>>
		<h2 class="entry-title"><?php the_title(); ?></h2>
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