<?php

    global $mk_options;

    if ($view_params['thumbnail_align'] == 'left'){
        $align_class = ' content-align-right';
    }else{
        $align_class = ' content-align-left';
    }
    
    $post_type = get_post_meta($post->ID, '_single_post_type', true);
    $post_type = !empty($post_type) ? $post_type : 'image';


    $image_output_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-thumbnail-style', true)[0];
    $image_output_src_2x = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-thumbnail-style-2x', true)[0];
    

    $output = '<article id="' . get_the_ID() . '" class="mk-blog-thumbnail-item '.$post_type.'-post-type mk-isotop-item ' . $post_type . '-post-type'.$align_class.'">' . "\n";

    if (has_post_thumbnail()) {
        $output .= '<div class="featured-image" ><a href="' . get_permalink() . '" title="' . get_the_title() . '">';
        $output .= '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" data-src-retina="'.mk_image_generator($image_output_src_2x, 800, 350) . '" width="400" height="350" src="' . mk_image_generator($image_output_src, 400, 350) . '" itemprop="image" />';
        $output .= '<div class="image-hover-overlay"></div>';
        $output .= '<div class="post-type-badge" href="' . get_permalink() . '"><i class="mk-li-' . $post_type . '"></i></div>';
        $output .= '</a></div>';
    }

    $output .= '<div class="item-wrapper">';

        // start: [mk-blog-meta]
        $output .= '<div class="mk-blog-meta">';
        $output.= mk_get_shortcode_view('mk_blog', 'components/meta', true);   
        $output.= mk_get_shortcode_view('mk_blog', 'components/title', true);
        $output.= mk_get_shortcode_view('mk_blog', 'components/excerpt', true, ['excerpt_length' => $view_params['excerpt_length'], 'full_content' => false]);

        $output .= '<div class="mk-teader-button">';
        $output .= do_shortcode( '[mk_button dimension="outline" corner_style="pointed" outline_skin="dark" margin_bottom="0" size="medium" align="left" url="'.get_permalink().'"]'.__('READ MORE', 'mk_framework').'[/mk_button]' );
        $output .= '</div>';

        $output .= '</div>';
        // end: [mk-blog-meta]


    $output .= '</div>'; // end: [item-wrapper]

    $output .= '</article>' . "\n\n\n";
    echo $output;
