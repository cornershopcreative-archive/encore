<?php
/**
 * Template tags.
 *
 * Utility functions, odds & ends, mainly for use in content section templates.
 */

/**
 * Output an opening <a> tag for the current section item, if appropriate.
 *
 * This function relies on a naming convention for ACF sub-fields:
 */
function crate_item_link( $attrs = array(), $prefix = '', $use_sub_fields = true ) {

	// Determine whether to use get_sub_field() or get_field().
	$get_field_function = ( $use_sub_fields ? 'get_sub_field' : 'get_field' );

	// Add an underscore to $prefix if it's non-empty, so we can prefix all field
	// names.
	$prefix = ( $prefix ? $prefix . '_' : '' );

	// Get the Link URL field.
	$link_url = $get_field_function( $prefix . 'link_url' );

	// If there's nothing to link to, bail.
	if ( ! $link_url ) {
		return;
	}

	// Get link options, and translate them to HTML attributes.
	$link_options = $get_field_function( $prefix . 'link_options' );
	if ( is_array( $link_options ) && in_array( 'target_blank', $link_options ) ) {
		$attrs['target'] = '_blank';
		$attrs['rel'] = 'noopener noreferrer';
	}

	$html = '<a href="' . esc_url( $link_url ) . '"';
	foreach ( $attrs as $attr_name => $attr_value ) {
		$html .= " $attr_name=" . '"' . esc_attr( $attr_value ) . '"';
	}
	$html .= '>';

	echo $html;
}

/**
 * Output a closing </a> tag for the current section item, if appropriate.
 */
function crate_item_link_close( $prefix = '', $use_sub_fields = true ) {

	// Determine whether to use get_sub_field() or get_field().
	$get_field_function = ( $use_sub_fields ? 'get_sub_field' : 'get_field' );

	// Determine the field name to use.
	$field_name = ( $prefix ? "{$prefix}_link_url" : 'link_url' );

	// If the current section item has a URL to link to, just spit out a closing
	// tag. It doesn't matter what the link options, etc. are.
	if ( $link_url = $get_field_function( $field_name) ) {
		echo '</a>';
	}
}

/**
 * Return true or false depending on whether the current section item has a
 * link.
 */
function crate_item_has_link( $prefix = '', $use_sub_fields = true ) {

	// Determine whether to use get_sub_field() or get_field().
	$get_field_function = ( $use_sub_fields ? 'get_sub_field' : 'get_field' );

	// Determine the field name to use.
	$field_name = ( $prefix ? "{$prefix}_link_url" : 'link_url' );

	// Return true if the link_url field is present, or false if not.
	return !! $get_field_function( $field_name );
}

/**
 * Output an opening <a> tag for the current post (don't use sub-fields).
 */
function crate_post_item_link( $attrs = array(), $prefix = '' ) {
	return crate_item_link( $attrs, $prefix, false );
}

/**
 * Output a closing </a> tag for the current post (don't use sub-fields).
 */
function crate_post_item_link_close( $prefix = '' ) {
	return crate_item_link_close( $prefix, false );
}

/**
 * Return true or false depending on whether the current post has a link (don't
 * use sub-fields).
 */
function crate_post_item_has_link( $prefix = '' ) {
	return crate_item_has_link( $prefix, false );
}

/**
 * Output the publication date of a post.
 */
function crate_posted_on() {
	?>

	<time class="post-date entry-date updated" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
		<span class="month"><?php echo get_the_date( 'F' ); ?></span>
		<span class="day"><?php echo esc_html( get_the_date( 'j' ) ); ?></span>,
		<span class="year"><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
	</time>

	<?php
}

/**
 * Query posts for a content section.
 *
 * @return WP_Query $query A new WP Query object.
 */
function crate_section_query( $query_vars ) {

	// Get fields for standard query vars.
	$show_pager = get_sub_field( 'show_pager' );
	$show_facets = ( 'yes' === get_sub_field( 'filtering' ) );
	$posts_per_page = get_sub_field( 'items_per_page' );
	if ( $posts_per_page < 1 ) {
		$posts_per_page = -1;
	}

	$defaults = array(
		'facetwp' => ( $show_pager || $show_facets ), // Allow filtering/pagination via FWP.
		'posts_per_page' => $posts_per_page,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'tax_query' => array(),
	);

	$query_vars = array_merge( $defaults, $query_vars );

	// Add Location filter parameters, if any.
	$locations = get_sub_field( 'location' );
	if ( ! empty( $locations ) ) {
		$query_vars['tax_query']['location'] = array(
			'taxonomy' => 'location',
			'terms' => $locations,
		);
	}

	// Add Topic filter parameters, if any.
	$topics = get_sub_field( 'topic' );
	if ( ! empty( $topics ) ) {
		$query_vars['tax_query']['topic'] = array(
			'taxonomy' => 'topic',
			'terms' => $topics,
		);
	}

	return new WP_Query( $query_vars );
}

/**
 * Output HTML for a 'Back to [post type archive]' link.
 */
function crate_back_link( $args = array() ) {

	// Set default arguments.
	$args = wp_parse_args( $args, array(
		'text' => '', // If this is blank/empty, "Back to [archive name]" will be used.
		'class' => 'back-link',
	) );

	global $post;

	// Get the pseudo-archive ID, if any.
	$pseudo_archive_id = crate_get_pseudo_archive_id( $post );

	// If no link text was set, we'll use "Back to [archive name]". So first
	// we'll need to figure out the archive name.
	if ( empty( $args['text'] ) ) {

		// If a pseudo-archive exists, use its title.
		if ( $pseudo_archive_id ) {
			$archive_name = get_the_title( $pseudo_archive_id );
		}
		// If no pseudo-archive exists, use the real post type archive link and the
		// post type's plural name.
		else {
			$post_type = get_post_type_object( $post->post_type );
			$archive_name = $post_type->labels->name;
		}

		// Prefix the archive name with "Back to".
		$args['text'] = sprintf(
			__( 'Back to %s', 'crate' ),
			$archive_name
		);
	}

	?>
	<a href="<?php echo esc_url( crate_get_post_type_archive_link( $post->post_type ) ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>"><?php
		echo esc_html( $args['text'] );
	?></a>
	<?php
}

/**
 * Get the post type archive link for a post type, OR the pseudo-archive link
 * if there is one.
 */
function crate_get_post_type_archive_link( $post_type ) {

	// Get the post type object.
	$post_type = get_post_type_object( $post_type );

	// If no post type object was found, bail.
	if ( is_wp_error( $post_type ) ) {
		return;
	}

	// If the post type doesn't have a real archive, try looking for a
	// pseudo-archive link.
	// Even though we've got a filter on post_type_archive_link, that filter
	// won't get called if the post type in question has has_archive set to
	// false.
	if ( ! $post_type->has_archive ) {
		return crate_pseudo_archive_link( '', $post_type->name );
	}

	// Fall back on the normal WP post type archive link, or maybe the filtered
	// version if has_archive is set to true but there's also a pseudo-archive.
	return get_post_type_archive_link( $post_type->name );
}

/**
 * Output 'primary' and 'secodary' links for a section.
 */
function crate_section_links() {

	// If there's neither a primary nor a secondary link for this section, bail.
	if ( ! crate_item_has_link( 'primary' ) && ! crate_item_has_link( 'secondary' ) ) {
		return;
	}

	?>

	<div class="button-group container">

		<?php crate_item_link( array(
			'class' => 'button button-gold button-solid',
		), 'primary' ); ?>
			<?php echo esc_html( get_sub_field( 'primary_link_text' ) ); ?>
		<?php crate_item_link_close( 'primary' ); ?>

		<?php crate_item_link( array(
			'class' => 'button button-gold',
		), 'secondary' ); ?>
			<?php echo esc_html( get_sub_field( 'secondary_link_text' ) ); ?>
		<?php crate_item_link_close( 'secondary' ); ?>

	</div>

	<?php
}

/**
 * Return a string suitable for use as the ID for a content section, if a
 * 'section name' was provided.
 */
function crate_get_section_id() {
	return sanitize_title( get_sub_field( 'section_name' ) );
}

/**
 * Return an ID attribute for a content section, if a 'section name' was
 * provided.
 */
function crate_get_section_id_attr() {

	if ( $section_name = crate_get_section_id() ) {
		return ' id="' . esc_attr( $section_name ) . '"';
	}

	return '';
}

/**
 * Output an ID attribute for a content section.
 */
function crate_section_id_attr() {
	echo crate_get_section_id_attr();
}

/**
 * Given oembed <iframe> markup, return a URL with autoplay enabled.
 *
 * Note that autoplay will *probably* only work for YouTube videos.
 */
function crate_get_oembed_autoplay_url( $html ) {

	// Pull iframe src url.
	if ( preg_match( '/src="([^"]*)"/', $html, $matches ) ) {
		// Add ?autoplay=1 to src url.
		return add_query_arg( 'autoplay', 1, $matches[1] );
	}

	return false;
}
