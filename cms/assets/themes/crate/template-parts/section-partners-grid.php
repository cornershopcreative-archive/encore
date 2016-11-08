<?php
/**
 * The template for displaying Partners sections.
 */
?>

	<div class="content-section section-stories-grid">

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>

		<?php

		$show_pager = get_sub_field( 'show_pager' );

		// Set up custom query.
		$story_query = crate_section_query( array(
			'post_type' => 'partners',
		) );

		?>

		<div class="content-section-grid container<?php echo ( $show_pager ? ' facetwp-template' : '' ); ?>">
			<?php while ( $story_query->have_posts() ) : $story_query->the_post(); ?>

				<article class="grid-item grid-item-3<?php if ( get_field( 'bright_spot' ) ) echo ' bright-spot'; ?>">

					<div class="entry-thumbnail">

						<?php echo get_the_post_thumbnail( null, 'square-md' ); ?>

						<div class="entry-quote">
							<?php the_field( 'quote' ); ?>
						</div>

					</div>

					<div class="entry-summary">

						<h3 class="entry-title">
							<a href="<?php echo esc_attr( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
						</h3>

						<p><?php the_field( 'subtitle' ); ?></p>

					</div>

					<a href="<?php echo esc_url( get_permalink() ); ?>" class="overlay-link"></a>

				</article>

			<?php endwhile; ?>
		</div>

		<?php if ( $show_pager ) :
			echo facetwp_display( 'pager' );
		endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php crate_section_links(); ?>

	</div>
