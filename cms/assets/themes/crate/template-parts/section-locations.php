<?php
/**
 * The template for displaying Learning Lab Communities (Locations) sections.
 */
?>

	<div class="content-section section-locations">

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>

		<?php

		$terms = get_terms( array(
			'taxonomy' => 'location',
			'hide_empty' => false,
			// Use default sort order (name ascending).
		) );

		if ( $terms ) : ?>

		<div class="content-section-grid container-10">
			<?php foreach ( $terms as $term ) : ?>
				<div class="grid-item grid-item-2">
					<a href="<?php echo esc_attr( get_term_link( $term, 'location' ) ); ?>">
						<?php echo wp_get_attachment_image( get_field( 'logo', $term ) ); ?>
					</a>
					<div class="grid-item-blurb">
						<?php crate_item_link(); ?>
							<?php echo apply_filters( 'the_content', $term->description ); ?>
						<?php crate_item_link_close(); ?>
					</div>
					<div class="button-group">
						<a href="<?php echo esc_attr( get_term_link( $term, 'location' ) ); ?>" class="button button-gold button-solid"><?php esc_html_e( 'Get Involved', 'crate' ); ?></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php endif; ?>

	</div>
