<?php
/**
 * The template for displaying Column Grid sections.
 */
?>

<?php

	$textgrid_button_url = get_sub_field('button_url');
	$textgrid_button_label = get_sub_field('button_text');
?>

<div class="content-section"<?php crate_section_id_attr(); ?>>
		<?php if ( $heading = get_sub_field( 'heading' ) ) : ?>	
			<h2 class="section-title"><?php the_sub_field('heading'); ?></h2>
		<?php endif; ?>
		<div class="content-section-grid container text-grid">
				<?php while ( have_rows( 'grid-items' ) ) : the_row(); ?>
					<div class="grid-item-2">
					<?php if ( $url = get_sub_field( 'url' ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="remove-underline">
					<?php endif; ?>
					<?php if ( $name = get_sub_field( 'name' ) ) : ?>	
						<h3 class="grid-item-name"><?php the_sub_field('name'); ?></h3>
					<?php endif; ?>
						<span class="content-section section-wysiwyg container-8 prose">
							<?php the_sub_field('content'); ?>
						</spanv>
					<?php if ( $url ) : ?>
						</a>
					<?php endif; ?>
					</div>
				<?php endwhile; ?>
		</div>
		<center><?php if (empty($textgrid_button_label)) {  }
			 else { echo '<a class="button button-gold" href="' . $textgrid_button_url . '"> ' . $textgrid_button_label . '</a>' ;} ?></center>
</div>
