<?php
$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => $view_params['width']*$view_params['image_quality'], 'height' => $view_params['height']*$view_params['image_quality']));
?>

<img width="<?php echo $view_params['width']; ?>" height="<?php echo $view_params['height']; ?>" src="<?php echo mk_image_generator($image_src, $view_params['width'], $view_params['height']); ?>" alt="<?php echo the_title(); ?>" title="<?php the_title(); ?>"  class="item-featured-image" />
