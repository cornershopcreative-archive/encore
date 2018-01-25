<?php
/**
 * The template for displaying Partners Ticker sections.
 */
?>

	<div class="content-section section-partner-ticker"<?php crate_section_id_attr(); ?>>
		
		<?php
		// Set up custom query vars.
		$topics = get_sub_field( 'topic' );
		// Get up to 100 most recently added partners with logos.
		$partner_query_vars = array(
		'post_type' => 'partner',
		'posts_per_page' => 100,
		'meta_query' => array(
			array(
				'key' => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		),
		'tax_query' => array(),
		);
		
		if ( ! empty( $topics ) ) :
			$partner_query_vars['tax_query']['topic'] = array(
				'taxonomy' => 'topic',
				'terms' => $topics,
			);
		endif;
		
		$partner_query = new WP_Query( $partner_query_vars );
		?>

		<?php if ( $partner_query->have_posts() ) : ?>
			<div class="section-ticker">
				<?php while ( $partner_query->have_posts() ) : $partner_query->the_post(); ?>

					<div class="ticker-item">
						<?php echo get_the_post_thumbnail( null, 'news-logo' ); ?>
					</div>

				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php crate_item_link( array( 'class' => 'overlay-link' ) ); ?>
			<span class='screen-reader-text'><?php esc_attr_e( 'View All Partners', 'crate' ); ?></span>
		<?php crate_item_link_close(); ?>

	</div>

