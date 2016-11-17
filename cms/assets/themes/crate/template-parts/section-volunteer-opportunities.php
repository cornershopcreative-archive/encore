<?php
/**
 * The template for displaying Volunteer Opportunities from the VolunteerMatch API.
 */

$location = get_sub_field( 'location' );

// Get first set of API results.
$api_results = get_vmatch_results( array(
	'location' => $location,
) );

$opportunities = $api_results['opportunities'];

// If no results were found, bail.
if ( ! $opportunities ) :
	return;
endif;

?>

<div class="content-section section-volunteer-opportunities" data-page="1" data-location="<?php echo esc_attr( $location ); ?>"<?php crate_section_id_attr(); ?>>

	<div class="section-filters container">
		<form class="filter-form" action="#">

			<h5>Search within</h5>

			<select name="opportunities_radius" class="filter-radius">
				<option value="5">5 miles</option>
				<option value="10">10 miles</option>
				<option value="25">25 miles</option>
				<option value="50">50 miles</option>
				<option value="100">100 miles</option>
			</select>

			<h5>of</h5>

			<input type="search" name="opportunities_location" class="filter filter-location" value="" placeholder="<?php esc_attr_e( 'Location', 'crate' ); ?>" />
			<span class="flex-space"></span>
			<input type="search" name="opportunities_terms" class="filter filter-search" value="" placeholder="<?php esc_attr_e( 'Keywords', 'crate' ); ?>" />
		</form>
	</div>

	<div class="content-section-grid container">
		<?php foreach ( $opportunities as $opp ): ?>
			<article class="grid-item grid-item-3">

				<div class="entry-image">
					<?php if ( ! empty( $opp['imageUrl'] ) ) : ?>
						<a href="<?php echo esc_url( urldecode( $opp['vmUrl'] ) ); ?>" target="_blank"><img src="<?php echo esc_url( urldecode( $opp['imageUrl'] ) ); ?>" alt="Logo for <?php echo esc_attr( $opp['title'] ) ; ?>"></a>
					<?php else : ?>
						<div class="no-image"></div>
					<?php endif; ?>
				</div>

				<h3 class="grid-item-heading">
					<a href="<?php echo esc_url( urldecode( $opp['vmUrl'] ) ); ?>" target="_blank"><?php echo wp_kses_post( $opp['title'] ); ?></a>
				</h3>

				<div class="grid-item-blurb">
					<p><?php echo wp_trim_words( wp_kses_post( $opp['plaintextDescription'] ), 30 ); ?>&nbsp;<a href="<?php echo esc_url( urldecode( $opp['vmUrl'] ) ); ?>" class="more" target="_blank">More</a></p>
				</div>

				<div class="grid-item-meta">
					<?php echo $opp['location']['city'] ?>, <?php echo $opp['location']['region'] ?>
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
