<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Crate
 * @since Crate 1.0
 */

get_header(); ?>
<div id="main">
	<article id="post-0" class="post error404 not-found">
		<h1><?php _e( 'Not Found', 'crate' ); ?></h1>
		<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'boilerplate' ); ?></p>
		<?php get_search_form(); ?>
		<script>
			// focus on search field after it has loaded
			document.getElementById('s') && document.getElementById('s').focus();
		</script>
	</article>
</div>
<?php get_footer(); ?>
