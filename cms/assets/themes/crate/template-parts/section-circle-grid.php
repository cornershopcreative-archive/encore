<?php
/**
 * The template for displaying Circle Grid sections.
 */
?>

	<div class="content-section section-circle-grid">
		<div class="content-section-grid container">
			<?php while ( have_rows( 'items' ) ) : the_row(); ?>
				<?php
				$link_open_tag = '';
				$link_close_tag = '';
				$link_url = get_sub_field( 'link_url' );
				$link_options = get_sub_field( 'link_options' );
				if ( $link_url  ) :
					$link_attrs = '';
					if ( is_array( $link_options) && in_array( 'target_blank', $link_options ) ) :
						$link_attrs = ' target="_blank" rel="noopener noreferrer"';
					endif;
					$link_open_tag = '<a href="' . esc_url( $link_url ) . '"' . $link_attrs . '>';
					$link_close_tag = '</a>';
				endif;
				?>
				<div class="grid-item grid-item-3">
					<?php echo $link_open_tag; ?>
						<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'grid-item', false, array( 'class' => 'grid-item-image' ) ); ?>
					<?php echo $link_close_tag; ?>
					<?php if ( $grid_item_heading = get_sub_field( 'item_heading' ) ) : ?>
						<h3 class="grid-item-heading">
							<?php echo $link_open_tag; ?>
								<?php echo wp_kses_post( wptexturize( $grid_item_heading ) ); ?></h3>
							<?php echo $link_close_tag; ?>
						</h3>
					<?php endif; ?>
					<div class="grid-item-blurb">
						<?php echo $link_open_tag; ?>
							<?php echo wp_kses_post( get_sub_field( 'item_blurb' ) ); ?>
						<?php echo $link_close_tag; ?>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
