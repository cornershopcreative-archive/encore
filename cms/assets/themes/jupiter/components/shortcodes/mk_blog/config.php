<?php
extract(shortcode_atts(array(
     'style'                  => 'modern',
     'column'                 => 3,
     'disable_meta'           => 'true',
     'full_content'           => 'false',
     'grid_image_height'      => 350,
     'count'                  => 10,
     'offset'                 => 0,
     'cat'                    => '',
     'posts'                  => '',
     'author'                 => '',
     'comments_share'         => 'true',
     'pagination'             => 'true',
     'pagination_style'       => '1',
     'image_size'             => 'crop',
     'orderby'                => 'date',
     'order'                  => 'DESC',
     'excerpt_length'         => 200,
     'image_quality'          => 1,
     'thumbnail_align'        => 'left',
     'magazine_strcutre'      => '1',
     'el_class'               => '',
     'transparent_border'     => 'false',
), $atts));
Mk_Static_Files::addAssets('mk_blog');
Mk_Static_Files::addAssets('mk_button'); 
Mk_Static_Files::addAssets('mk_audio');
Mk_Static_Files::addAssets('mk_swipe_slideshow');