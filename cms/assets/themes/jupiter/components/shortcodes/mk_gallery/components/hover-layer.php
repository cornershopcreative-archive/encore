<?php 
$lightbox_push_top = '';
$custom_links = $view_params['custom_links'];
$link = isset($custom_links[$view_params['i']]) ? $custom_links[$view_params['i']] : '';
$image_title     = get_the_title();
$alt             = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
$caption             = $post->post_excerpt;
$image_title     = $view_params['collection_title'] ? $view_params['collection_title'] : $image_title;

if($view_params['hover_scenarios'] == 'none') { 

    if (isset($link) && $link != '') { ?>
        <a href="<?php echo $link; ?>" class="full-cover-link"></a>
    <?php } else { ?>
        <a href="<?php echo wp_get_attachment_image_src($post->ID, 'full', true)[0]; ?>" alt="<?php echo $alt; ?>" title="<?php echo $image_title; ?>" data-fancybox-group="gallery-<?php echo $view_params['id']; ?>" class="mk-lightbox full-cover-link"></a>
    <?php }
}
?>


<?php if($view_params['hover_scenarios'] != 'none') { ?>

    <div class="image-hover-overlay"></div>
      
    <?php if ($view_params['hover_scenarios'] == "overlay_layer" || $view_params['disable_title'] != 'false' && !empty($image_title)) { ?>
        <div class="gallery-desc">
            <div class="gallery-title"><?php echo $image_title; ?></div>
            <div class="gallery-caption"><?php echo $caption; ?></div>
        </div>
        <?php $lightbox_push_top = 'lightbox-push-top';
    }


     if (isset($link) && $link != '') { ?>
        
        <a href="<?php echo $link; ?>" class="mk-image-lightbox">
            <i class="mk-jupiter-icon-plus-circle"></i>
        </a>

    <?php } else { ?>

        <a href="<?php echo wp_get_attachment_image_src($post->ID, 'full', true)[0]; ?>" alt="<?php $alt; ?>" title="<?php $image_title; ?>" data-fancybox-group="gallery-<?php echo $view_params['id']; ?>" class="mk-lightbox <?php $lightbox_push_top; ?> mk-image-lightbox">
            <i class="mk-jupiter-icon-plus-circle"></i>
        </a>

    <?php }
}