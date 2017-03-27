<?php
/**
 * The template for displaying blog sections.
 */
?>

<?php

	$blogsection_color = get_sub_field('background_color');

?>


	<?php echo '<div class="content-section section-news '.$blogsection_color.' "' . crate_get_section_id_attr() . '>' ;?>

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
		$blog_query_vars = array(
			'facetwp' => $show_pager, // Allow filtering/pagination via FWP.
			'post_type' => 'post',
			'posts_per_page' => $posts_per_page,
			'orderby' => array(
			'post_date' => 'DESC',
			),
			'tax_query' => array(),
		);
		if ( ! empty( $locations ) ) :
			$blog_query_vars['tax_query']['location'] = array(
				'taxonomy' => 'location',
				'terms' => $locations,
			);
		endif;
		if ( ! empty( $topics ) ) :
			$blog_query_vars['tax_query']['topic'] = array(
				'taxonomy' => 'topic',
				'terms' => $topics,
			);
		endif;

		$blog_query = new WP_Query( $blog_query_vars );

		?>

		<div class="content-section-list container-10<?php echo ( $show_pager ? ' facetwp-template' : '' ); ?>">
			
			<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>

				<article class="list-item">

					<?php if ( $image_id = get_post_thumbnail_id() ) : ?>
						<div class="entry-image">
											<?php echo wp_get_attachment_image( $image_id, 'news-logo', false ); ?>
													</div>
					<?php endif; ?>

					<div class="entry-summary">

						<?php crate_posted_on(); ?>

						<h3 class="entry-title">
							<a href="<?php the_permalink();?>"><?php echo esc_html( get_the_title() ); ?></a>
						</h3>
						<div>
							<?php the_excerpt() ?> 
						</div>

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
