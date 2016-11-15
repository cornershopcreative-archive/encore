<?php
/**
 * The template for displaying Circle Grid sections.
 */
?>

<?php

	$textgrid_button_url = get_sub_field('button_url');
	$textgrid_button_label = get_sub_field('button_text');
	$textgrid_color = get_sub_field('background_color');
	$textgrid_URL = get_sub_field('item_url');
	$textgrid_title = get_sub_field('title');

?>

<?php echo '<div class="content-section '.$textgrid_color.' "' . crate_get_section_id_attr() . '>' ;?>
		<div style="color: black;">
		<h2 class="section-title"><?php the_sub_field('heading'); ?></h3>
		<div class="content-section-grid container text-grid">
				<?php while ( have_rows( 'grid-items' ) ) : the_row(); ?>
					<div class="text-grid-item grid-item-3">
					<h3 class="grid-item-name"><?php the_sub_field('name'); ?></h3>
					<span class="grid-item-title">
						<?php the_sub_field('title'); ?>
					</span>
					</div>
				<?php endwhile; ?>
		</div>
		<center><?php if (empty($textgrid_button_label)) {  }
			 else { echo '<a class="button button-gold" href="' . $textgrid_button_url . '"> ' . $textgrid_button_label . '</a>' ;} ?></center>
		</div>
	</div>
