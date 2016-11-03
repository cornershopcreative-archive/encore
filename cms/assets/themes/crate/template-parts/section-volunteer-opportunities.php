<?php
/**
 * The template for displaying Volunteer Opportunities from the VolunteerMatch API.
 */


// Try to get cached results
if ( get_transient( 'vmatch_basic' ) ) {

	$organizations = get_transient( 'vmatch_basic' );

// Use the API to get results
} else {

	// We can't do anything if VolunteerMatchAPI is missing
	if ( ! class_exists('VolunteerMatchAPI') ) return;

	$api = new VolunteerMatchAPI();

	$query = array(
		'location' => 'United States',
		'sortCriteria' => 'update',
		'numberOfResults' => 18,
	);

	$results = $api->searchOrganizations( $query, 'org detail' );

	// We can't do anything if VolunteerMatchAPI didn't give us anything useful
	if ( ! isset( $results['organizations'] ) ) return;

	$organizations = $results['organizations'];

	// Cache them
	set_transient( 'vmatch_basic', $organizations, 5 * MINUTE_IN_SECONDS );
}



	/* each $org is an array with the following keys (see API call):
		avgRating => float
		categoryIds => array
		created => string like 2016-10-28T07:58:32-0700
		description => string
		id => int
		imageUrl => string
		location => array( city, country, postalCode, region )
		mission => string
		name => string
		numReviews => int
		plaintextDescription => sting
		type => string ("public")
		url => string - often null!
		vmUrl => string
	*/

?>

<div class="content-section section-volunteer-opportunities" data-page="1">

	<h2 class="section-title">Volunteer Opportunities</h2>

	<div class="content-section-grid container">
		<?php foreach ( $organizations as $org ): ?>
			<article class="grid-item grid-item-3">

				<div class="entry-image">
					<?php if ( ! empty( $org['imageUrl'] ) ) : ?>
						<a href="<?php echo esc_url( urldecode( $org['vmUrl'] ) ); ?>" target="_blank"><img src="<?php echo esc_url( urldecode( $org['imageUrl'] ) ); ?>" alt="Logo for <?php echo esc_attr( $org['name'] ) ; ?>"></a>
					<?php else : ?>
						<div class="no-image"></div>
					<?php endif; ?>
				</div>

				<h3 class="grid-item-heading">
					<a href="<?php echo esc_url( urldecode( $org['vmUrl'] ) ); ?>" target="_blank"><?php echo esc_html( $org['name'] ); ?></a>
				</h3>

				<div class="grid-item-blurb">
					<p><?php echo wp_trim_words( wp_kses_post( $org['plaintextDescription'] ), 30 ); ?>&nbsp;<a href="<?php echo esc_url( urldecode( $org['vmUrl'] ) ); ?>" class="more" target="_blank">More</a></p>
				</div>

				<div class="grid-item-meta">
					<?php echo $org['location']['city'] ?>, <?php echo $org['location']['region'] ?>
				</div>
			</article>

		<?php endforeach; ?>
	</div>

	<div class="section-title">
		<a class="button more" href="#">Show More</a>
	</div>
</div>

<script id="volunteer-opportunity" type="x-tmpl-mustache">
	<article class="grid-item grid-item-3">
		<div class="entry-image">
			{{{imagehtml}}}
		</div>
		<h3 class="grid-item-heading">
			<a href="{{{url}}}" target="_blank">{{name}}</a>
		</h3>
		<div class="grid-item-blurb">
			<p>{{{summary}}}&nbsp;<a href="{{url}}" class="more" target="_blank">More</a></p>
		</div>
		<div class="grid-item-meta">
			{{city}}, {{region}}
		</div>
	</article>
</script>