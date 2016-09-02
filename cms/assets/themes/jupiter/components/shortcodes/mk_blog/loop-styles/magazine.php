<?php
global $mk_options;

if ($view_params['i'] == 1) {
    $loop_style = 'magazine-featured-post';
    
    if ($view_params['layout'] == 'full') {
        $image_width = $mk_options['grid_width'] - 40;
        $image_height = ($image_width) * 0.6;
    } 
    else {
        $image_width = (($mk_options['content_width'] / 100) * $mk_options['grid_width']) - 40;
        $image_height = ($image_width) * 0.6;
    }
    if ($view_params['image_size'] == 'crop') {
        $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true);
        $image_output_src = bfi_thumb($image_src_array[0], array(
            'width' => $image_width * $view_params['image_quality'],
            'height' => $image_height * $view_params['image_quality']
        ));
    } 
    else {
        $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , $view_params['image_size'], true);
        $image_output_src = $image_src_array[0];
    }
} 
else {
    $loop_style = 'magazine-thumb-post';
    $image_output_src = wp_get_attachment_image_src(get_post_thumbnail_id() , 'blog-magazine-thumbnail', true)[0];
    $image_width = $image_height = 200;
}

$post_type = get_post_meta($post->ID, '_single_post_type', true);
$post_type = !empty($post_type) ? $post_type : 'image';

$output = '<article id="' . get_the_ID('Y-m-d') . '" class="mk-blog-magazine-item ' . $loop_style . ' '.$post_type.'-post-type mk-isotop-item"><div class="blog-item-holder">';

if (has_post_thumbnail()) {
    
    $output.= '<div class="featured-image"><a title="' . get_the_title() . '" href="' . get_permalink() . '">';
    $output.= '  <img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . mk_image_generator($image_output_src, $image_width, $image_height) . '" itemprop="image" />';
    $output.= '  <div class="image-gradient-overlay"></div>';
    $output.= '</a></div>';
}

$output.= '<div class="item-wrapper">';

$output.= mk_get_shortcode_view('mk_blog', 'components/title', true);

// start: [mk-blog-meta]
$output.= '<div class="mk-blog-meta">';
$output.= '<time datetime="' . get_the_date() . '">';
$output.= '<a href="' . get_month_link(get_the_time("Y") , get_the_time("m")) . '">' . get_the_date() . '</a>';
$output.= '</time>';
$output.= '<span class="mk-categories">&nbsp;' . __('', 'mk_framework') . ' ' . get_the_category_list(', ') . '</span>';
$output.= '<div class="clearboth"></div>';
$output.= '</div>';

// end: [mk-blog-meta]

if ($view_params['i'] == 1) {
    $output.= mk_get_shortcode_view('mk_blog', 'components/excerpt', true, ['excerpt_length' => $view_params['excerpt_length'], 'full_content' => false]);
    
    if ($view_params['comments_share'] != 'false') {
        $output.= '<div class="blog-magazine-social-section">';
        $output.= mk_get_shortcode_view('mk_blog', 'components/comments', true);
        $output.= mk_get_shortcode_view('mk_blog', 'components/love-this', true);
        $output.= '</div>';
    }
}

$output.= '</div>';
$output.= '</article>' . "\n\n\n";

echo $output;
?>
