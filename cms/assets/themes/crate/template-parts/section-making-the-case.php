<?php
/**
 * The template for displaying Circle Grid sections.
 */
?>

<div class="content-section">
		<h2 class="section-title">Making the Case</h3>
		<p class="section-subtitle">Read the latest research and thinking behind Generation to Generation</p>
		<div class="content-section-grid container making-the-case" style="padding-left: 0px; padding-right: 0px;">
				<?php while ( have_rows( 'items' ) ) : the_row(); ?>
					<a class="remove-underline" href="<?php the_sub_field('url'); ?>" target="_blank">
					<div class="grid-item-2 mtc-grid-item">
					<h3 class="grid-item-name"><?php the_sub_field('title'); ?></h3>
					<br>
						<span class="grid-item-title"> <?php the_sub_field('source_name'); ?></a>
					</div>
					</a>
				<?php endwhile; ?>
		</div>
	</div>