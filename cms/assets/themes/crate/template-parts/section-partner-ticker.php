<?php
/**
 * The template for displaying Partners Ticker sections.
 */
?>

	<div class="content-section section-partner-ticker"<?php crate_section_id_attr(); ?>>

		<?php
		// Get up to 100 most recently added partners with logos.
		$partner_query = new WP_Query( array(
			'post_type' => 'partner',
			'posts_per_page' => 100,
			'orderby' => 'date',
			'order' => 'DESC',
			'meta_query' => array(
				array(
					'key' => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
		) );
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
