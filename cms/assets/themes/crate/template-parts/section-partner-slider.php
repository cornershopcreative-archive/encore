<?php
/**
 * The template for displaying Partner Slider sections.
 */
?>

<?php

	$slider_color = get_sub_field('background_color');

?>


	<?php echo '<div class="content-section section-partner-slider '.$slider_color.' "' . crate_get_section_id_attr() . '>' ;?>
	
		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>
		<?php

		// Set up custom query.
		$partner_query = crate_section_query( array(
			'post_type' => 'partner',
			'orderby' => 'post_title',
			'order' => 'ASC',
		) );

		?>
		<div class="content-section-slider container-10">
			<div class="slider-controls">
				<a href="#" class="slider-prev">
					<svg class="icon" viewBox="0 0 100 100">
						<use xlink:href="#icon-slider-arrow"></use>
					</svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Previous slide' ); ?></span>
				</a>
				<a href="#" class="slider-next">
					<svg class="icon" viewBox="0 0 100 100">
						<use xlink:href="#icon-slider-arrow"></use>
					</svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Next slide' ); ?></span>
				</a>
			</div>
			<div class="slider-items">
				<?php
				$n_results = 0;
				?>
				<div class="slider-item"><div class="content-section-grid">
					<?php while ( $partner_query->have_posts() ) : $partner_query->the_post();

						// Break up query results into groups of 6.
						// Count items.
						$n_results += 1;
						// After 6 items...
						if ( $n_results > 6 ) :
							// Reset the item count.
							$n_results = 1;
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

		<?php crate_section_links(); ?>

	</div>
