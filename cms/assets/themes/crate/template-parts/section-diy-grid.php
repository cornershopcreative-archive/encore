<?php
/**
 * The template for displaying DIY Opportunities Grid sections.
 */
?>

	<div class="content-section section-tile-grid section-diy-grid"<?php crate_section_id_attr(); ?>>

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>

		<?php if ( $subtitle = get_sub_field( 'subtitle' ) ): ?>
			<p class="section-subtitle"><?php echo wp_kses_post( wptexturize( $subtitle ) ); ?></p>
		<?php endif; ?>

		<?php

		$show_pager = get_sub_field( 'show_pager' );

		// Set up custom query.
		$opp_query = crate_section_query( array(
			'post_type' => 'opportunity',
		) );

		?>

		<div class="content-section-grid container<?php echo ( $show_pager ? ' facetwp-template' : '' ); ?>">
			<?php while ( $opp_query->have_posts() ) : $opp_query->the_post(); ?>

				<?php
				$opp_type = get_field( 'type' );
				// Determine what should happen when the user clicks this opportunity.
				$opp_link = '';
				$opp_link_target = '';
				if ( 'embed' === $opp_type ) :
					$opp_link = crate_get_oembed_autoplay_url( get_field( 'embed' ) );
				elseif ( 'download' === $opp_type ) :
					$opp_link = get_field( 'download' );
					$opp_link_target = ' target="blank" rel="noopener noreferrer"';
				elseif ( 'link' === $opp_type ) :
					$opp_link = get_field( 'link_url' );
					$opp_link_target = ' target="blank" rel="noopener noreferrer"';
				endif;
				?>

				<article class="grid-item grid-item-3<?php if ( 'text' === $opp_type ) echo ' text-only'; ?>">

					<?php if ( 'text' !== $opp_type ) : ?>

						<div class="entry-thumbnail">

							<?php if ( has_post_thumbnail() ) : ?>
								<?php echo get_the_post_thumbnail( null, 'square-md' ); ?>
							<?php else : // No image? Display the G2G logo. ?>
								<svg class="logo" viewBox="0 0 50 50">
									<use xlink:href="#icon-logo"></use>
								</svg>
							<?php endif; ?>

							<?php if ( 'embed' === $opp_type ) : ?>
								<div class="icon-container">
									<span class="icon-video-play"></span>
								</div>
							<?php endif; ?>

							<div class="entry-preview">
								<?php the_field( 'description' ); ?>
								<?php if ( $button_text = get_field( 'button_text' ) ) : ?>
									<div class="button-group"><a href="<?php echo esc_url( $opp_link ); ?>"<?php echo esc_html( $opp_link_target ); ?> class="button button-solid button-white<?php if ( 'embed' === $opp_type ) echo ' lightbox-embed-link'; ?>"><?php echo esc_html( $button_text ); ?></a></div>
								<?php endif; ?>
							</div>

						</div>

					<?php endif; ?>

					<div class="entry-summary">
						<?php if ( 'text' === $opp_type ) : ?>

							<div class="entry-text">
								<?php the_field( 'description' ); ?>
							</div>

						<?php else : ?>

							<h3 class="entry-title">
								<a href="<?php echo esc_attr( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
							</h3>

						<?php endif; ?>
					</div>

					<?php if ( ( 'text' !== $opp_type ) && $opp_link ) : ?>
						<a href="<?php echo esc_url( $opp_link ); ?>"<?php echo esc_html( $opp_link_target ); ?> class="overlay-link<?php if ( 'embed' === $opp_type ) echo ' lightbox-embed-link'; ?>"></a>
					<?php endif; ?>

				</article>

			<?php endwhile; ?>
		</div>

		<?php if ( $show_pager ) :
			echo facetwp_display( 'pager' );
		endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php crate_section_links(); ?>

	</div>
