<?php 
$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-carousel', true)[0];
?>

<img src="<?php echo mk_image_generator($image_src, 245, 180); ?>" width="245" height="180" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" />