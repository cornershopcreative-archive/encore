<?php
/**
 * The template for displaying Volunteer Opportunities from the VolunteerMatch API.
 */

$location = get_sub_field( 'location' );

// Get first set of API results.
$api_results = get_vmatch_results( array(
	'location' => $location,
) );

$organizations = $api_results['organizations'];

// If no results were found, bail.
if ( ! $organizations ) :
	return;
endif;

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

<div class="content-section section-volunteer-opportunities" data-page="1" data-location="<?php echo esc_attr( $location ); ?>"<?php crate_section_id_attr(); ?>>

	<div class="section-filters container">
		<form class="filter-form" action="#">
			<span class="flex-space"></span>
			<input type="search" name="search_opportunities" class="filter filter-search" value="" placeholder="<?php esc_attr_e( 'Search', 'crate' ); ?>" />
		</form>
	</div>

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
					<a href="<?php echo esc_url( urldecode( $org['vmUrl'] ) ); ?>" target="_blank"><?php echo wp_kses( $org['name'] ); ?></a>
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
