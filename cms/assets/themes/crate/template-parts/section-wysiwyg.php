<?php
/**
 * The template for displaying Generic WYSIWYG sections.
 */
?>

	<div class="content-section section-wysiwyg container-8 prose">
		<?php echo wp_kses_post( get_sub_field( 'content' ) ); ?>
	</div>

