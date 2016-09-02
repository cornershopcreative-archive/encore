<?php
$image_src = wp_get_attachment_image_src(get_post_thumbnail_id() , $view_params['image_dimension'][0] , true)[0];

if(!empty($view_params['link'])) { ?>
	<a href="<?php echo $view_params['link']; ?>">
<?php } ?>
		<img alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="<?php echo mk_image_generator($image_src, $view_params['image_dimension'][1], $view_params['image_dimension'][1]); ?>" />

<?php if(!empty($view_params['link'])) { ?>
	</a>
<?php } ?>
