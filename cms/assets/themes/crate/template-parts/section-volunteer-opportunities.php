<?php
/**
 * The template for displaying Volunteer Opportunities from the VolunteerMatch API.
 */

// We can't do anything if VolunteerMatchAPI is missing
if ( ! class_exists('VolunteerMatchAPI') ) return;

$api = new VolunteerMatchAPI();

$query = array(
	'location' => 'United States',
	'sortCriteria' => 'update',
);

$results = $api->searchOrganizations( $query, 'org detail' );

// We can't do anything if VolunteerMatchAPI didn't give us anything useful
if ( ! isset( $results['organizations'] ) ) return;


$organizations = $results['organizations'];

?>

	<div class="content-section section-volunteere">

			<h2 class="section-title">Volunteer Opportunities</h2>

		<div class="content-section-list container-10">
			<?php foreach ( $organizations as $org ): ?>

				<?php

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
				<article class="list-item">

					<?php if ( ! empty( $org['imageUrl'] ) ) : ?>
						<div class="entry-image">
							<img src="<?php echo esc_url( urldecode( $org['imageUrl'] ) ); ?>" alt="Logo for <?php echo esc_attr( $org['name'] ) ; ?>">
						</div>
					<?php endif; ?>

					<div class="entry-summary">

						<h3 class="entry-title"><a href="<?php echo esc_url( urldecode( $org['vmUrl'] ) ); ?>" target="_blank"><?php echo esc_html( $org['name'] ); ?></a></h3>

					</div>

				</article>

			<?php endforeach; ?>
		</div>

	</div>
