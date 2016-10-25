<?php
/**
 * The template for displaying News sections.
 */
?>

	<div class="content-section section-news">
		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>
		<?php

		// Set up custom query vars.
		$locations = get_sub_field( 'location' );
		$topics = get_sub_field( 'topic' );
		$news_query_vars = array(
			'post_type' => 'news',
			'posts_per_page' => -1,
			'tax_query' => array(),
		);
		if ( ! empty( $locations ) ) :
			$news_query_vars['tax_query']['location'] = array(
				'taxonomy' => 'location',
				'terms' => $locations,
			);
		endif;
		if ( ! empty( $topics ) ) :
			$news_query_vars['tax_query']['topic'] = array(
				'taxonomy' => 'topic',
				'terms' => $topics,
			);
		endif;

		$news = new WP_Query( $news_query_vars );

		?>
		<div class="content-section-slider container-10">
			<div class="slider-controls">
				<a href="#" class="slider-prev"><span class="icon-slider-arrow-charcoal"></span><span class="screen-reader-text"><?php esc_html_e( 'Previous slide' ); ?></span></a>
				<a href="#" class="slider-next"><span class="icon-slider-arrow-charcoal"></span><span class="screen-reader-text"><?php esc_html_e( 'Next slide' ); ?></span></a>
			</div>
			<div class="content-section-list">
				<?php while ( $news->have_posts() ) : $news->the_post(); ?>

					<article class="list-item">

						<?php if ( $image_id = get_post_thumbnail_id() ) : ?>
							<div class="entry-image">
								<?php crate_post_item_link( array(
									'target' => '_blank',
									'rel'    => 'noopener noreferrer',
								) ); ?>
									<?php echo wp_get_attachment_image( $image_id, 'news-logo', false ); ?>
								<?php crate_post_item_link_close(); ?>
							</div>
						<?php endif; ?>

						<div class="entry-summary">

							<?php crate_posted_on(); ?>

							<h3 class="entry-title">
								<?php crate_post_item_link( array(
									'target' => '_blank',
									'rel'    => 'noopener noreferrer',
								) ); ?>
									<?php echo esc_html( get_the_title() ); ?>
								<?php crate_post_item_link_close(); ?>
							</h3>

						</div>

					</article>

				<?php endwhile; ?>
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
