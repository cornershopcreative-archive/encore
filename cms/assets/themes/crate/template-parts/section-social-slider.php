<?php
/**
 * The template for displaying Social Sliders.
 */
?>

<?php $socialshortcode = get_sub_field( 'shortcode' ); ?>

	<div class="content-section section-social-slider"<?php crate_section_id_attr(); ?>>
		<div class="orange-title"><h2 class="section-title"><?php echo get_sub_field( 'title' ); ?></h2></div>
		<div class="container">
			<?php echo do_shortcode( $socialshortcode ); ?>
		</div>
	</div>


