<?php
/**
 * The template for displaying Photo Gallery sections.
 */
?>

	<div class="content-section section-action-slider"<?php crate_section_id_attr(); ?>>
		<div class="content-section-slider container-8 container-bleed" data-timeout="<?php echo esc_attr( $autoplay_rate ); ?>">
			<div class="slider-controls">
				<a href="#" class="slider-prev" id="prev">
					<svg class="icon" viewBox="0 0 100 100">
						<use xlink:href="#icon-slider-arrow-heavy"></use>
					</svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Previous slide' ); ?></span>
				</a>
				<a href="#" class="slider-next" id="next">
					<svg class="icon" viewBox="0 0 100 100">
						<use xlink:href="#icon-slider-arrow-heavy"></use>
					</svg>
					<span class="screen-reader-text"><?php esc_html_e( 'Next slide' ); ?></span>
				</a>
			</div>
			  <div class="row cycle-slideshow" data-cycle-fx=carousel data-cycle-timeout=5000 data-cycle-carousel-visible=3  data-cycle-slides="a"     data-cycle-next="#next"  data-cycle-prev="#prev"  >
	    
				<?php while ( have_rows( 'items' ) ) : the_row(); ?>
			
				<?php $lightboxid = uniqid();
?>
					<div class="slider-item">
				
					<a href="#" data-featherlight="#<?php echo $lightboxid; ?>">
						<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'square-sm', 							false, array( 'class' => 'slider-photo' ) ); ?></a>
					
					
					<div class="totallyhidden">		
					<div id="<?php echo $lightboxid; ?>">
						
							<?php echo wp_get_attachment_image( get_sub_field( 'image' ), array('700', '600'), "", array( "class" => "img-responsive" ) );  ?><br>
							<?php echo wp_kses_post( get_sub_field( 'item_blurb' ) ); ?>	
							
					</div>
					</div>
					</div>
				
					<?php endwhile; ?>
			</div>
		




					</div>

