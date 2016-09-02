<?php
if (has_post_thumbnail()) {
$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => 120, 'height' => 120)); 
?>

	<div class="mk-testimonial-image">
		<img width="50" height="50" src="<?php echo mk_image_generator($image_src, 120, 120); ?>" alt="<?php echo strip_tags( get_post_meta( get_the_ID(), '_author', true ) ); ?>" />
	</div>

<?php } ?>