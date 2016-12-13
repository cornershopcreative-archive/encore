<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage crate
 * @since crate 1.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
<?php if ( have_posts() ) : ?>
<header class="entry-header hero">
	<div class="hero-text prose prose-compact container-10 container-flex">
				<h1><?php printf( __( 'Search Results for: %s', 'crate' ), '' . get_search_query() . '' ); ?></h1>
	</div>
</header>
		<div class="container-8 search-listings">

				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
					<h2><?php _e( 'Nothing Found', 'crate' ); ?></h2>
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'crate' ); ?></p>
					<?php get_search_form(); ?>
<?php endif; ?>

</div>
</main>
<?php get_footer(); ?>
