<?php
/**
 * The template for displaying Partner Slider sections.
 */
?>

	<div class="content-section section-partner-slider">
		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>
		<?php

		// Set up custom query vars.
		$locations = get_sub_field( 'location' );
		$topics = get_sub_field( 'topic' );
		$partner_query_vars = array(
			'post_type' => 'partners',
			'posts_per_page' => -1,
			'tax_query' => array(),
		);
		if ( ! empty( $locations ) ) :
			$partner_query_vars['tax_query']['location'] = array(
				'taxonomy' => 'location',
				'terms' => $locations,
			);
		endif;
		if ( ! empty( $topics ) ) :
			$partner_query_vars['tax_query']['topic'] = array(
				'taxonomy' => 'topic',
				'terms' => $topics,
			);
		endif;

		$partners = new WP_Query( $partner_query_vars );

		?>
		<div class="content-section-slider container-10">
			<div class="slider-controls">
				<a href="#" class="slider-prev"><span class="icon-slider-arrow-charcoal"></span><span class="screen-reader-text"><?php esc_html_e( 'Previous slide' ); ?></span></a>
				<a href="#" class="slider-next"><span class="icon-slider-arrow-charcoal"></span><span class="screen-reader-text"><?php esc_html_e( 'Next slide' ); ?></span></a>
			</div>
			<div class="slider-items">
				<?php
				$n_results = 0;
				?>
				<div class="slider-item"><div class="content-section-grid">
					<?php while ( $partners->have_posts() ) : $partners->the_post();

						// Break up query results into groups of 6.
						// Count items.
						$n_results += 1;
						// After 6 items...
						if ( $n_results > 6 ) :
							// Reset the item count.
							$n_results = 0;
							// Close the current .slider-item and open a new one.
							?>
				</div></div><!-- /.content-section-grid, /.slider-item -->
				<div class="slider-item"><div class="content-section-grid">
							<?php
						endif; ?>

						<div class="grid-item grid-item-3">
							<?php if ( $partner_logo_id = get_post_thumbnail_id() ) :
								// If a logo image is present, link that to the partner URL.
								?>

								<?php crate_post_item_link( array(
									'target' => '_blank',
									'rel'    => 'noopener noreferrer',
								) ); ?>
									<?php echo wp_get_attachment_image( $partner_logo_id, 'partner-logo', false, array( 'class' => 'grid-item-image', 'alt' => get_the_title() ) ); ?>
								<?php crate_post_item_link_close(); ?>

							<?php else :
								// If no logo image, link the partner's name to its URL.
								?>

								<h3>
									<?php crate_post_item_link( array(
										'target' => '_blank',
										'rel'    => 'noopener noreferrer',
									) ); ?>
										<?php echo esc_html( get_the_title() ); ?>
									<?php crate_post_item_link_close(); ?>
								</h3>

							<?php endif; ?>
						</div>

					<?php endwhile; ?>
				</div></div><!-- /.content-section-grid, /.slider-item -->
			</div>
		</div>

		<?php wp_reset_postdata(); ?>

		<?php if ( crate_item_has_link( 'primary' ) || crate_item_has_link( 'secondary' ) ) : ?>
			<div class="button-group">

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
		<?php endif; ?>

	</div>
