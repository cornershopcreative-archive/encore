<?php
/**
 * The template for displaying Circle Grid sections.
 */
?>

<?php

	$item_image = get_sub_field('image');

?>


<div class="content-section">
	<h2 class="section-title"><?php the_sub_field('heading'); ?></h2>
		<div class="content-section-grid container text-grid">
				<?php while ( have_rows( 'items' ) ) : the_row(); ?>
					<div class="grid-item grid-item-4">
						<img src="<?php the_sub_field('image'); ?>">
				
				</div>
				<?php endwhile; ?>
		</div>
</div>