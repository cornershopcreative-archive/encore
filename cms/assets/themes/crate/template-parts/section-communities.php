<?php
/**
 * The template for displaying Learning Lab Communities sections.
 */
?>

	<div class="content-section section-communities">

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>

		<?php

		$communities = crate_section_query( array(
			'post_type' => 'community',
			'orderby' => 'post_title',
			'order' => 'ASC',
		) );

		if ( $communities->have_posts() ) : ?>

		<div class="content-section-grid container-10">
			<?php while ( $communities->have_posts() ) : $communities->the_post(); ?>
				<div class="grid-item grid-item-2">
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php echo wp_get_attachment_image( get_field( 'logo' ) ); ?>
					</a>
					<div class="grid-item-blurb">
						<?php the_excerpt(); ?>
					</div>
					<div class="button-group">
						<a href="<?php echo esc_url( get_permalink() ); ?>" class="button button-gold button-solid"><?php esc_html_e( 'Get Involved Locally', 'crate' ); ?></a>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
