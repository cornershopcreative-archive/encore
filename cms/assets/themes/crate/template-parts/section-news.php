<?php
/**
 * The template for displaying News sections.
 */
?>

<?php

	$newssection_color = get_sub_field('background_color');

?>


	<?php echo '<div class="content-section section-news '.$newssection_color.' "' . crate_get_section_id_attr() . '>' ;?>

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
		$news_query_vars = array(
			'facetwp' => $show_pager, // Allow filtering/pagination via FWP.
			'post_type' => 'news',
			'posts_per_page' => $posts_per_page,
			'orderby' => array(
				'sticky' => 'DESC', // Meta query will be added by crate_pre_get_posts() (see inc/query.php).
				'post_date' => 'DESC',
			),
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

		$news_query = new WP_Query( $news_query_vars );

		?>

		<div class="content-section-list container-10<?php echo ( $show_pager ? ' facetwp-template' : '' ); ?>">
			<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>

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

		<?php if ( $show_pager ) :
			echo facetwp_display( 'pager' );
		endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php crate_section_links(); ?>

	</div>
