<?php
if ($view_params['image_size'] == 'crop') {
    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true);
    $image_output_src = mk_image_generator($image_src_array[0], $view_params['image_width'], $view_params['image_height']);
    $image_width = $view_params['image_width'];
    $image_height = $view_params['image_height'];
} 
else {
    if(has_post_thumbnail() && !mk_is_default_thumbnail(wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true)[0])) {
        $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , $view_params['image_size'], true);
        $image_output_src = $image_src_array[0];
        $image_width = $image_src_array[1];
        $image_height = $image_src_array[2];    
    } else {
        $image_output_src = '';
        $image_width = 800;
        $image_height = 300;    
    }  
}

switch ($view_params['post_type']) {
    case 'image':
        if (has_post_thumbnail()) {
            echo '<div class="featured-image"><a title="' . get_the_title() . '" href="' . get_permalink() . '">';
            echo '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" width="'.$image_width.'" height="'.$image_height.'" src="' . $image_output_src  . '" itemprop="image" />';
            echo '<div class="image-hover-overlay"></div>';
            echo '<div class="post-type-badge" href="' . get_permalink() . '"><i class="mk-li-' . $view_params['post_type'] . '"></i></div>';
            echo '</a></div>';
        }
        break;

    case 'portfolio':
        $featured_image_id = get_post_thumbnail_id();
        $attachment_ids = get_post_meta($post->ID, '_gallery_images', true);
        
        if (!empty($attachment_ids)) {
            
            if (!empty($featured_image_id)) {
                $attachment_ids = $featured_image_id . ',' . $attachment_ids;
            }
            
            echo '<div class="blog-gallery-type">';
            echo do_shortcode('[mk_swipe_slideshow images="' . $attachment_ids . '" image_size="'.$view_params['image_size'].'" image_width="' . $view_params['image_width'] . '" image_height="' . $view_params['image_height'] . '"]');
            echo '</div>';
        } 
        else {
            echo '<div class="featured-image"><a title="' . get_the_title() . '" href="' . get_permalink() . '">';
            echo '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" width="'.$image_width.'" height="'.$image_height.'" src="' . $image_output_src . '" itemprop="image" />';
            echo '<div class="image-hover-overlay"></div>';
            echo '<div class="post-type-badge" href="' . get_permalink() . '"><i class="mk-li-' . $view_params['post_type'] . '"></i></div>';
            echo '</a></div>';
        }
        break;

    case 'video':
        
        $video_id = get_post_meta($post->ID, '_single_video_id', true);
        $video_site = get_post_meta($post->ID, '_single_video_site', true);
        
        echo '<div class="featured-image">';
        
        if ($video_site == 'vimeo') {
            echo '<div class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http' . ((is_ssl()) ? 's' : '') . '://player.vimeo.com/video/' . $video_id . '?title=0&amp;byline=0&amp;portrait=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
        }
        if ($video_site == 'youtube') {
            echo '<div class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http' . ((is_ssl()) ? 's' : '') . '://www.youtube.com/embed/' . $video_id . '?showinfo=0&amp;theme=light&amp;color=white&amp;rel=0" frameborder="0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
        }
        if ($video_site == 'dailymotion') {
            echo '<div style="width:' . $view_params['image_width'] . 'px;" class="mk-video-wrapper"><div class="mk-video-container"><iframe src="http' . ((is_ssl()) ? 's' : '') . '://www.dailymotion.com/embed/video/' . $video_id . '?logo=0" frameborder="0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div></div>';
        }
        echo '</div>';
        
        break;

    case 'audio':
        $iframe = get_post_meta($post->ID, '_audio_iframe', true);
        if (empty($iframe)) {
            $mp3_file = get_post_meta($post->ID, '_mp3_file', true);
            $ogg_file = get_post_meta($post->ID, '_ogg_file', true);
            $audio_author = get_post_meta($post->ID, '_single_audio_author', true);
            
            echo do_shortcode('[mk_audio mp3_file="' . $mp3_file . '" ogg_file="' . $ogg_file . '" thumb="' . $image_output_src . '" audio_author="' . $audio_author . '"]');
        } 
        else {
            echo '<div class="audio-iframe">' . $iframe . '</div>';
        }
        break;

    case 'twitter':
        $url = get_post_meta($post->ID, '_tweet_oembed', true);
        echo mk_get_shortcode_view('mk_blog', 'components/tweet-status', true, ['url' => $url]);
        
        break;

    case 'blockquote':
        $quote = get_post_meta($post->ID, '_blockquote_content', true);
        $quote_author = get_post_meta($post->ID, '_blockquote_author', true);
        if (!empty($quote)) {
            echo '
                <div class="blog-blockquote-content">
                    <a href="'.get_permalink().'">' . $quote . '</a>
                    <footer> - <q>' . $quote_author . '</q> </footer>
                </div>
            ';
        }
        break;

    case 'instagram':
        $url = get_post_meta($post->ID, '_instagram_url', true);
        echo mk_get_shortcode_view('mk_blog', 'components/instagram-feed', true, ['url' => $url]);
        
        break;
}

