<?php
/**
 * The template for displaying Action Slider sections.
 */
?>

	<div class="content-section section-action-slider">
		<div class="content-section-slider container-10">
			<div class="slider-items">
				<?php while ( have_rows( 'items' ) ) : the_row(); ?>
					<div class="slider-item">
						<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'slider-item', false, array( 'class' => 'slider-item-image' ) ); ?>
						<div class="slider-item-text prose prose-compact container-8 container-flex">
							<?php if ( $grid_item_heading = get_sub_field( 'item_heading' ) ) : ?>
								<h3 class="grid-item-heading">
									<?php echo wp_kses_post( wptexturize( $grid_item_heading ) ); ?></h3>
								</h3>
							<?php endif; ?>
							<div class="grid-item-blurb">
								<?php echo wp_kses_post( get_sub_field( 'item_blurb' ) ); ?>
							</div>
							<?php if ( $link_url = get_sub_field( 'link_url' ) ) :
								$link_options = get_sub_field( 'link_options' );
								if ( is_array( $link_options) && in_array( 'target_blank', $link_options ) ) :
									$link_attrs = ' target="_blank" rel="noopener noreferrer"';
								endif;
								?>
								<div class="button-group">
									<a href="<?php echo esc_url( $link_url ); ?>" class="button button-gold button-solid"<?php echo $link_attrs; ?>>
										<?php echo esc_html( get_sub_field( 'link_text' ) ); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
			<a href="#" class="slider-prev"><span class="icon-slider-arrow-white"></span><span class="screen-reader-text"><?php esc_html_e( 'Previous slide' ); ?></span></a>
			<a href="#" class="slider-next"><span class="icon-slider-arrow-white"></span><span class="screen-reader-text"><?php esc_html_e( 'Next slide' ); ?></span></a>
		</div>
	</div>
