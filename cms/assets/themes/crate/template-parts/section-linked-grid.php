<?php
/**
 * The template for displaying Linked Grid sections.
 */
?>

<div class="content-section section-grid"<?php crate_section_id_attr(); ?>>
	<div class="content-section-grid container">
			<?php while ( have_rows( 'items' ) ) : the_row(); ?>
				<div class="grid-item grid-item-3">
					<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'square-md', false, array( 'class' => 'grid-item-image' ) ); ?>
							<?php if ( $grid_item_heading = get_sub_field( 'item_heading' ) ) : ?>
								<h3 class="grid-item-heading">
									<?php echo wp_kses_post( wptexturize( $grid_item_heading ) ); ?></h3>
								</h3>
							<?php endif; ?>
							<div class="grid-item-blurb">
								<?php echo wp_kses_post( get_sub_field( 'item_blurb' ) ); ?>
							</div>
							<?php if ( crate_item_has_link() ) : ?>
								<div class="button-group">
									<?php crate_item_link( array(
										'class' => 'button button-gold button-solid',
									) ); ?>
										<?php echo esc_html( get_sub_field( 'link_text' ) ); ?>
									<?php crate_item_link_close(); ?>
								</div>
							<?php endif; ?>
				</div>
			<?php endwhile; ?>
	</div>
</div>

