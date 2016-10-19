<?php
/**
 * The template for displaying Generic WYSIWYG sections.
 */
?>

	<div class="container-8 prose">
		<?php echo wp_kses_post( get_sub_field( 'content' ) ); ?>
	</div>

