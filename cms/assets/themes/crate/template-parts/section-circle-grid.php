<?php
/**
 * The template for displaying Circle Grid sections.
 */
?>

	<div class="content-section section-circle-grid"<?php crate_section_id_attr(); ?>>
		<div class="content-section-grid container">
			<?php while ( have_rows( 'items' ) ) : the_row(); ?>
				<div class="grid-item grid-item-3">
					<?php crate_item_link(); ?>
						<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'square-md', false, array( 'class' => 'grid-item-image' ) ); ?>
					<?php crate_item_link_close(); ?>
					<?php if ( $grid_item_heading = get_sub_field( 'item_heading' ) ) : ?>
						<h3 class="grid-item-heading">
							<?php crate_item_link(); ?>
								<?php echo wp_kses_post( wptexturize( $grid_item_heading ) ); ?></h3>
							<?php crate_item_link_close(); ?>
						</h3>
					<?php endif; ?>
					<div class="grid-item-blurb">
						<?php crate_item_link(); ?>
							<?php echo wp_kses_post( get_sub_field( 'item_blurb' ) ); ?>
						<?php crate_item_link_close(); ?>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
