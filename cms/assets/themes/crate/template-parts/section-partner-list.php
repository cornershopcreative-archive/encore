<?php
/**
 * The template for displaying Partner List sections.
 */
?>

	<div class="content-section section-partner-list"<?php crate_section_id_attr(); ?>>

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>
		<?php

		$show_pager = get_sub_field( 'show_pager' );

		// Set up custom query.
		$partner_query = crate_section_query( array(
			'post_type' => 'partner',
			'orderby' => 'post_title',
			'order' => 'ASC',
		) );

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

						<div class="button-group">
								<?php crate_post_item_link( array(
								'class' => 'button button-gold button-solid',
								'data-org-name' => trim( esc_attr( get_the_title() ) )
							) ); ?>
								<?php esc_html_e( 'Get Involved', 'crate' ); ?>
							<?php crate_post_item_link_close(); ?>
						</div>

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

	<?php get_template_part( 'template-parts/modal', 'opportunity' ); ?>
