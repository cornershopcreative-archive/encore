<?php
/**
 * The template for displaying Learning Lab Partners sections.
 */
?>

	<div class="content-section section-learning-lab-partners">

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>
		<?php

		// Set up custom query vars.
		$show_pager = get_sub_field( 'show_pager' );
		$locations = get_sub_field( 'location' );
		$topics = get_sub_field( 'topic' );
		$posts_per_page = get_sub_field( 'items_per_page' );
		if ( $posts_per_page < 1 ) {
			$posts_per_page = -1;
		}
		$partner_query_vars = array(
			'facetwp' => $show_pager, // Allow filtering/pagination via FWP.
			'post_type' => 'partners',
			'posts_per_page' => $posts_per_page,
			'meta_query' => array(
				'learning-lab' => array(
					'key' => 'options',
					'value' => 's:12:"learning-lab"',
					'compare' => 'LIKE',
				)
			),
			'orderby' => 'post_title',
			'order' => 'ASC',
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

		$partner_query = new WP_Query( $partner_query_vars );

		?>

		<div class="content-section-list container<?php echo ( $show_pager ? ' facetwp-template' : '' ); ?>">
			<?php while ( $partner_query->have_posts() ) : $partner_query->the_post(); ?>

				<article class="list-item">

					<div class="entry-image">
						<?php echo get_the_post_thumbnail( null, 'news-logo' ); ?>
					</div>

					<div class="entry-summary">

						<h3 class="entry-title">
							<?php echo esc_html( get_the_title() ); ?>
						</h3>

						<?php the_content(); ?>

						<?php crate_post_item_link( array(
							'class' => 'button button-gold button-solid',
						) ); ?>
							<?php esc_html_e( 'Get Involved', 'crate' ); ?>
						<?php crate_post_item_link_close(); ?>

					</div>

				</article>

			<?php endwhile; ?>
		</div>

		<?php if ( $show_pager ) : ?>
			<div class="button-group list-pager container">
				<a class="button button-gold fwp-load-more" data-text-more="<?php esc_attr_e( 'Load More', 'crate' ); ?>" data-text-loading="<?php esc_attr_e( 'Loading...', 'crate' ); ?>"><?php esc_html_e( 'Load More', 'crate' ); ?></a>
			</div>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
