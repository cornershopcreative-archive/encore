<?php

/**
 * template part for post featured image. views/global
 *
 * @author      Artbees
 * @package     jupiter/views
 * @version     5.0.0
 */
if (get_the_post_thumbnail() != ''):
    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true);
    require_once (THEME_FUNCTIONS . "/bfi_cropping.php");
    $image_src = bfi_thumb($image_src_array[0], array(
        'width' => $view_params['width'],
        'height' => $view_params['height']
    ));

    $el_class =  isset($view_params['el_class']) ? $view_params['el_class'] : '';
    
    $output = '<div class="' . $view_params['post_type'] . '-featured-image ' . $el_class . '">';
    $output.= '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . mk_image_generator($image_src, $view_params['width'],  $view_params['height']) . '" height="' . $view_params['height'] . '" width="' . $view_params['width'] . '" />';
    $output.= '</div>';
    echo $output;
endif;
