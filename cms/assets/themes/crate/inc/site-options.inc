<?php
/**
 * Functions related to the site-options Produce command.
 */

/**
 * Add Site Options page (to be populated via ACF).
 */
function crate_add_options_page() {
	acf_add_options_page( array(
		'page_title' => 'Site Options',
		'menu_slug'  => 'crate-site-options',
	) );
}
add_action( 'after_setup_theme', 'crate_add_options_page' );

/**
 * Return the URL for the Site Options page.
 */
function crate_get_options_page_url() {
	return get_admin_url( 'admin.php?page=crate-site-options' );
}

//-----------------------------------------------------------------------------
// "Display Options" tab
//-----------------------------------------------------------------------------

/**
 * Make the custom error page (if any) accessible in the 404.php template.
 *
 * If a custom error page has been set on the Site Options page, then for
 * requests that result in 404 errors, replace the global $wp_query with a
 * query for the error page, in order to make its content accessible in the
 * 404.php template.
 * Then set $wp_query->is_404 so that the 404.php template still gets used --
 * otherwise we'd end up using page.php or singular.php. Setting $is_404 also
 * ensures that the is_404() conditional tag behaves as expected.
 */
function crate_error_page_template_redirect() {

	// If the current query actually found something, then it's none of our
	// business. Bail.
	if ( ! is_404() ) {
		return;
	}

	// Has an error page been specified?
	if ( $error_page_id = crate_get_error_page_id() ) {

		// Replace the global $wp_query.
		global $wp_query;
		$wp_query = new WP_Query( array(
			'page_id' => $error_page_id,
		) );

		// Our new $wp_query has (presumably) found a page, so it'll have is_404
		// set to false -- but we want WP to use the 404 template, not the single
		// page template.
		// WP_Query::set_404() will set is_404 to true, while also resetting all
		// the other query flags, including `is_singular` and `is_page`. If we just
		// set $wp_query->is_404 to true by hand, then these flags would still be
		// true as well, and I can't in good conscience unleash such an abomination
		// into the world.
		$wp_query->set_404();

		// Now, calls to have_posts() and the_post(), etc. from within the 404.php
		// template will be made to a single-page query for the custom error page,
		// so 404.php can be written using the Loop, just like any other page
		// template.
	}
}
add_action( 'template_redirect', 'crate_error_page_template_redirect' );

/**
 * If a custom error page has been set on the Site Options page, exclude it
 * from any and all queries on the site frontend.
 */
function crate_error_page_pre_get_posts( $query ) {

	// Don't mess with admin queries.
	if ( is_admin() ) {
		return;
	}

	// Don't mess with queries for things other than Pages, since only a Page can
	// be used as the error page.
	// Side note: we *especially* don't want to mess with queries for
	// acf-field-groups, since the call to crate_get_error_page_id() later in
	// this function will run a query if it doesn't find any cached or locally
	// stored (JSON) field groups, and that will cause any pre_get_posts
	// callbacks to be run again, and... yeah.
	$query_post_type = $query->get( 'post_type' );
	if ( is_array( $query_post_type ) ) {
		// If this is a query for multiple post types other than 'page', bail.
		if ( ! in_array( 'page', $query_post_type ) ) {
			return;
		}
	} else {
		// If this isn't either a query for ALL post types, or a query just for
		// Pages, then bail.
		if ( ! in_array( $query_post_type, array( 'page', 'any' ) ) ) {
			return;
		}
	}

	// Has an error page been specified?
	if ( $error_page_id = crate_get_error_page_id() ) {

		$exclude = (array)$query->get( 'post__not_in' );
		$exclude[] = absint( $error_page_id );

		$query->set( 'post__not_in', $exclude );
	}
}
add_action( 'pre_get_posts', 'crate_error_page_pre_get_posts' );

/**
 * Display a message when viewing the custom error page in wp-admin, indicating
 * that it has been set as the error page.
 */
function crate_error_page_admin_notices() {

	// If an error page has been set...
	if ( $error_page_id = crate_get_error_page_id() ) {

		// Get info about the current admin screen.
		$screen = get_current_screen();

		// Is the user editing a page?
		if ( 'page' === $screen->id ) {

			// Is the page being edited the custom error page?
			if ( isset( $_GET['post'] ) && $error_page_id == $_GET['post'] ) {

				// Output a message.
				?>
				<div class="notice notice-info">
					<p><?php esc_html_e( 'This page is set to be used as the error page for this site. It will not be directly accessible; instead, its content will be displayed when a user tries to access a page or file that doesn\'t exist.', 'crate' ); ?></p>
					<p><?php printf(
						esc_html__( 'Visit the %s to change this setting.', 'crate' ),
						'<a href="' . esc_url( crate_get_options_page_url() ) . '">' . esc_html__( 'Site Options page', 'crate' ) . '</a>'
					); ?></p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_notices', 'crate_error_page_admin_notices' );

/**
 * Get the ID for the custom error page, if any.
 */
function crate_get_error_page_id() {
	return get_field( 'page_for_404', 'option' );
}

/**
 * If a default post thumbnail has been specified, use it when there's no post
 * thumbnail available for a given post.
 */
function crate_default_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	// If this post has a thumbnail, just return the real thumbnail HTML.
	if ( $post_thumbnail_id && ! empty( $html ) ) {
		return $html;
	}

	// If a default post thumbnail has been set for this post/post type, use it.
	if ( $default_thumbnail_id = crate_get_post_thumbnail_id( $post_id ) ) {
		$html = wp_get_attachment_image( $default_thumbnail_id, $size, false, $attr );
	}

	return $html;
}
add_filter( 'post_thumbnail_html', 'crate_default_post_thumbnail_html', 10, 5 );

/**
 * Short-circuited version of has_post_thumbnail().
 *
 * Always returns true for post types for which a default thumbnail has been set.
 */
function crate_has_post_thumbnail( $post = null ) {
	return (bool) crate_get_post_thumbnail_id( $post );
}

/**
 * Short-circuited version of get_post_thumbnail_id().
 *
 * Always returns the default thumbnail ID for post types for which a default
 * thumbnail has been set.
 */
function crate_get_post_thumbnail_id( $post = null ) {

	// First, check for a real thumbnail, just in case.
	if ( $real_thumbnail_id = get_post_thumbnail_id( $post ) ) {
		return $real_thumbnail_id;
	}

	// Get the post, or bail if we can't.
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	// These are the post types that should have custom thumbnails.
	$post_types_with_defaults = array(
		// NOTE: If any post types should have their own default thumbnails
		// (different from the one used for Posts), add them here.
		'post',
	);

	// Determine which post type to get the default thumbnail for.
	$post_thumbnail_type = $post->post_type;
	// If this post's actual post type isn't in the $post_types_with_defaults
	// array, then use the thumbnail for Posts.
	if ( ! in_array( $post->post_type, $post_types_with_defaults ) ) {
		$post_thumbnail_type = 'post';
	}

	// NOTE: Use this naming scheme if you're adding additional fields for
	// different post types: default_{POST TYPE}_thumbnail_id
	$default_thumbnail_id = get_field( "default_{$post_thumbnail_type}_thumbnail_id", 'option' );

	return $default_thumbnail_id;
}

/**
 * Set the post excerpt length to the number of words set on the Site Options
 * page (if any).
 */
function crate_excerpt_length( $length ) {
	if ( 'words' === get_field( 'excerpt_length_unit', 'option' ) ) {
		if ( $custom_length = get_field( 'excerpt_length', 'option' ) ) {
			$length = (int)$custom_length;
		}
	}
	return $length;
}
add_filter( 'excerpt_length', 'crate_excerpt_length' );

/**
 * Add the custom Read More link text (if any) to auto-generated excerpts.
 */
function crate_excerpt_more( $more_text ) {
	$more_text = __( ' &hellip; ', 'crate' );
	if ( $more_link_text = get_field( 'excerpt_link_text', 'option' ) ) {
		$more_text .= '<a href="' . esc_attr( get_permalink() ) . '">' . esc_html( $more_link_text ) . '</a>';
	}
	return $more_text;
}
add_filter( 'excerpt_more', 'crate_excerpt_more' );

/**
 * Re-trim post excerpts to a specific number of *characters*, instead of
 * words, if we've been asked to do so.
 */
function crate_trim_excerpt_characters( $trimmed, $raw_excerpt ) {

	// Per wp_trim_excerpt() behavior, if there *is* a raw excerpt, return it.
	if ( '' != $raw_excerpt ) {
		return $raw_excerpt;
	}

	// If we're supposed to be trimming by words and not characters, bail.
	if ( 'chars' !== get_field( 'excerpt_length_unit', 'option' ) ) {
		return $trimmed;
	}

	// Get the number of characters to trim to.
	$excerpt_length = (int)get_field( 'excerpt_length', 'option' );

	// If we haven't been told how many characters to trim to, bail.
	if ( ! $excerpt_length ) {
		return $trimmed;
	}

	// Apply filters/etc from wp_trim_excerpt().
	$text = get_the_content( '' );
	$text = strip_shortcodes( $text );
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );

	// Strip tags and condense whitespace like wp_trim_words().
	$text = wp_strip_all_tags( $text );
	$text = preg_replace( '/[\n\r\t ]+/', ' ', $text );

	// Trim the excerpt to the number of characters given.
	$trimmed_hard = substr( $text, 0, $excerpt_length );

	// Get the "more" text/link to append to the excerpt.
	$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]', 'crate' );

	// NOTE: We're trimming by words in an effort to keep all words whole. If you
	// don't care about your excerpts looking "like thi", you can skip the below
	// and just `return $trimmed_hard . $excerpt_more;`.
	// Get the number of words this corresponds to.
	$word_count = str_word_count( $trimmed_hard );
	// Decrement by one, since the chances are very good that $trimmed_hard ends
	// in the middle of a word.
	$word_count -= 1;
	// TrimSpa, baby!
	$trimmed_soft = wp_trim_words( $text, $word_count, $excerpt_more );

	return $trimmed_soft;
}
add_filter( 'wp_trim_excerpt', 'crate_trim_excerpt_characters' );

//-----------------------------------------------------------------------------
// "Text" tab
//-----------------------------------------------------------------------------

/**
 * Return the copyright text as specified on the Site Options page.
 */
function crate_get_copyright_text() {
	$copyright_text = get_field( 'copyright_text', 'option' );
	// Replace [year] with the year.
	$copyright_text = str_replace( '[year]', date( 'Y' ), $copyright_text );
	return $copyright_text;
}

/**
 * Output the copyright text as specified on the Site Options page.
 */
function crate_copyright_text() {
	// Escape output (allowing basic markup) & prettify dashes, apostrophes, etc.
	echo wp_kses_post( wptexturize( crate_get_copyright_text() ) );
}

/**
 * Return the contact info text as specified on the Site Options page.
 */
function crate_get_contact_info() {
	return get_field( 'contact_info', 'option' );
}

/**
 * Output the contact info as specified on the Site Options page.
 */
function crate_contact_info() {
	// Escape output (allowing basic markup) & prettify dashes, apostrophes, etc.
	echo wp_kses_post( wptexturize( crate_get_contact_info() ) );
}

//-----------------------------------------------------------------------------
// "URLs" tab
//-----------------------------------------------------------------------------

/**
 * Return an array of social links.
 *
 * @arg array $services An array of service names ('facebook', 'twitter', etc.)
 *                      to return links for, if links have been set in the Site
 *                      Options page. Omit or leave empty to return all links
 *                      provided.
 * @return array An array of arrays, each second-dimension array containing the
 *               keys 'service', 'handle', and 'url'. Note that 'service' and
 *               'handle' may be empty.
 */
function crate_get_social_links( $services = false ) {

	// Make sure $services is an array.
	if ( $services ) {
		$services = (array)$services;
	} else {
		$services = array();
	}

	// We'll collect the links in this variable.
	$links = array();

	// Iterate over 'social_media_urls' rows...
	while ( have_rows( 'social_links', 'option' ) ) {
		the_row();

		// Get all sub-fields.
		$link = array(
			'service'   => get_sub_field( 'service' ),
			'link_text' => get_sub_field( 'link_text' ),
			'url'       => get_sub_field( 'url' ),
		);

		if ( $services && ! empty( $services ) ) {
			if ( ! in_array( $link['service'], $services ) ) {
				// If an array of services was passed as an argument and this link's
				// service wasn't one of them, skip it.
				continue;
			}
		}

		// If we got this far, add this link to the return array.
		$links[] = $link;
	}

	return $links;
}

/**
 * Return the Donate URL as specified on the Site Options page.
 */
function crate_get_donate_url() {
	return get_field( 'donate_url', 'option' );
}

/**
 * Output the Donate URL as specified on the Site Options page.
 */
function crate_donate_url() {
	echo esc_url( crate_get_donate_url() );
}

/**
 * Return the Submit Your Story URL as specified on the Site Options page.
 */
function crate_get_story_submit_url() {
	return get_field( 'story_submit_url', 'option' );
}

/**
 * Output the Submit Your Story URL as specified on the Site Options page.
 */
function crate_story_submit_url() {
	echo esc_url( crate_get_story_submit_url() );
}

//-----------------------------------------------------------------------------
// "Integrations" tab
//-----------------------------------------------------------------------------

/**
 * Return the Facebook App ID as specified on the Site Options page.
 */
function crate_get_fb_app_id() {
	return get_field( 'facebook_app_id', 'option' );
}

/**
 * Output the Facebook App ID as specified on the Site Options page.
 */
function crate_fb_app_id() {
	echo esc_attr( crate_get_fb_app_id() );
}

/**
 * Return the Google Maps API key as specified on the Site Options page.
 */
function crate_get_google_maps_api_key() {
	return get_field( 'google_maps_api_key', 'option' );
}

/**
 * Output the Google Maps API key as specified on the Site Options page.
 *
 * Output will be urlencode'd, since it looks like this usually gets passed in
 * via a GET param in a URL somewhere.
 */
function crate_google_maps_api_key() {
	echo rawurlencode( crate_get_google_maps_api_key() );
}

//-----------------------------------------------------------------------------
// "Custom CSS" tab
//-----------------------------------------------------------------------------

/**
 * Style the Custom CSS field (use a monospace font).
 */
function crate_options_page_admin_css() {
	?>
	<!-- Added by Crate for Site Options > Custom CSS field -->
	<style type="text/css">
		.acf-field-monospace textarea {
			/* Match '#template textarea' styles from wp-admin/css/common.css */
			font-family: Consolas, Monaco, monospace;
			font-size: 13px;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'crate_options_page_admin_css' );

/**
 * Add the Custom CSS field's content to the <head> tag on user-facing pages.
 */
function crate_insert_custom_css() {
	?>
	<!-- Custom CSS from Crate -->
	<style type="text/css">
		<?php echo esc_html( get_field( 'custom_css', 'option' ) ); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'crate_insert_custom_css' );
