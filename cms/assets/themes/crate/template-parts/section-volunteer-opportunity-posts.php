<?php
/**
 * The template for displaying Volunteer Opportunities from the VolunteerMatch API.
 */

// Build meta query for local/virtual status.
$local_virtual = get_sub_field( 'vm_local_virtual' );
if ( 'local' === $local_virtual ) {
	$local_virtual_query = array(
		'key' => '_vm_location_region',
		'value' => 'virtual',
		'compare' => '!=',
	);
} elseif ( 'virtual' === $local_virtual ) {
	$local_virtual_query = array(
		'key' => '_vm_virtual',
		'value' => 1,
	);
} else {
	$local_virtual_query = false;
}

// Build meta query for location.
$locations = get_sub_field( 'vm_locations' );
$locations = explode( "\n", $locations );
$locations = array_map( 'trim', $locations );
$location_query = array();
foreach ( $locations as $location ) {

	// Skip blank lines.
	if ( empty( $location) ) continue;

	if ( preg_match( '/^\d{5}$/', $location ) ) {

		// If $location is numeric, treat it as a zip code.
		$location_query[] = array(
			'key' => '_vm_location_postalCode',
			'value' => $location,
		);

	} elseif ( preg_match( '/^(.*),([^,]*)$/', $location, $matches ) ) {

		// If $location contains a comma, treat it as City, State.
		$location_query[] = array(
			'relation' => 'AND',
			array(
				'key' => '_vm_location_city',
				'value' => trim( $matches[1] ),
			),
			array(
				'key' => '_vm_location_region',
				'value' => trim( $matches[2] ),
			),
		);

	} else {

		// Otherise, assume it's just the state.
		$location_query[] = array(
			'key' => '_vm_location_region',
			'value' => $location,
		);

	}
}
if ( ! empty( $location_query ) ) {
	$location_query['relation'] = 'OR';
}

// Build meta query for organization.
$org_ids = get_sub_field( 'vm_org_ids' );
$org_ids = explode( ',', $org_ids );
$org_ids = array_map( 'trim', $org_ids );
$org_query = array();
foreach ( $org_ids as $org_id ) {

	// Skip blank items.
	if ( ! $org_id ) continue;

	$org_query[] = array(
		'key' => '_vm_parentOrg_id',
		'value' => $org_id,
	);
}
if ( ! empty( $org_query ) ) {
	$org_query['relation'] = 'OR';
}

// General query vars.
$query_args = array(
	'post_type' => 'vm-opportunity',
	'orderby' => 'post_date',
	'order' => 'DESC',
);

// Build and add overall meta query.
$meta_query = array();
if ( ! empty( $local_virtual_query ) ) {
	$meta_query[] = $local_virtual_query;
}
if ( ! empty( $location_query ) ) {
	$meta_query[] = $location_query;
}
if ( ! empty( $org_query ) ) {
	$meta_query[] = $org_query;
}
if ( ! empty( $meta_query ) ) {
	$meta_query['relation'] = 'AND';
	$query_args['meta_query'] = $meta_query;
}

// Build query.
$section_query = crate_section_query( $query_args );

?>

<div class="content-section section-volunteer-opportunities" <?php crate_section_id_attr(); ?>>

	<?php if ( get_sub_field( 'filtering' ) == 'yes' ) : ?>
		<div class="section-facets container">
			<div class="section-facet">
				<h3 class="section-facet-label">Organization</h3>
				<?php echo facetwp_display( 'facet', 'vm-organization' ); ?>
			</div>
			<div class="section-facet">
				<h3 class="section-facet-label">State</h3>
				<?php echo facetwp_display( 'facet', 'vm-state' ); ?>
			</div>
			<div class="section-facet">
				<h3 class="section-facet-label">City</h3>
				<?php echo facetwp_display( 'facet', 'vm-city' ); ?>
			</div>
			<span class="flex-space"></span>
			<div class="section-facet">
				<h3 class="section-facet-label">Search</h3>
				<?php echo facetwp_display( 'facet', 'vm-search' ); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="content-section-grid container<?php echo ( get_sub_field( 'show_pager' ) ? ' facetwp-template' : '' ); ?>">
		<?php while ( $section_query->have_posts() ) : $section_query->the_post(); ?>
			<article class="grid-item grid-item-4">

				<div class="entry-image">
					<?php
					$image_url = get_post_meta( $post->ID, '_vm_imageUrl', true );
					if ( ! $image_url ) {
						$image_url = get_post_meta( $post->ID, '_vm_orgImageUrl', true );
					}
					if ( $image_url ) : ?>
						<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" target="_blank"><img src="<?php echo esc_url( urldecode( $image_url ) ); ?>" alt="Logo for <?php the_title_attribute(); ?>"></a>
					<?php else : ?>
						<div class="no-image"></div>
					<?php endif; ?>
				</div>

				<h3 class="grid-item-heading">
					<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" target="_blank"><?php the_title(); ?></a>
				</h3>

				<div class="grid-item-blurb">
					<?php the_excerpt(); ?>
				</div>

				<div class="grid-item-meta">
					<?php
					if ( get_post_meta( $post->ID, '_vm_virtual', true ) ) :
						esc_html_e( 'Anywhere', 'crate' );
					else :
						echo esc_html( sprintf( '%1$s, %2$s',
							get_post_meta( $post->ID, '_vm_location_city', true ),
							get_post_meta( $post->ID, '_vm_location_region', true )
						) );
					endif;
					?>
				</div>
			</article>

		<?php endwhile; ?>
	</div>

	<?php if ( get_sub_field( 'show_pager' ) ) :
		echo facetwp_display( 'pager' );
	endif; ?>

</div>
