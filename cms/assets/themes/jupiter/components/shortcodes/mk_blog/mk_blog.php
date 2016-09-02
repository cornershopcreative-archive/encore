<?php
$path = pathinfo(__FILE__) ['dirname'];

include ($path . '/config.php');

global $mk_options, $wp_query;

require_once (THEME_FUNCTIONS . "/bfi_cropping.php");


$id = Mk_Static_Files::shortcode_id();

$query_options = array(
    'post_type' => 'post',
    'count' => $count,
    'offset' => $offset,
    'cat' => $cat,
    'author' => $author,
    'posts' => $posts,
    'orderby' => $orderby,
    'order' => $order,
);

$query = mk_wp_query($query_options);
$r = $query['wp_query'];



if (is_singular()) {
    global $post;
    $layout = get_post_meta($post->ID, '_layout', true);
    $layout = (!empty($layout)) ? $layout : 'full';
} 
else if (is_search()) {
    $layout = $mk_options['search_page_layout'];
} 
else if (is_archive()) {
    $layout = $mk_options['archive_page_layout'];
} 
else {
    $layout = 'right';
}
$theme_images = THEME_IMAGES;
Mk_Static_Files::addCSS("
    #loop-{$id} .blog-twitter-content:before,
    #loop-{$id} .mk-blog-modern-item.twitter-post-type .blog-twitter-content footer:before {
        background-image: url('{$theme_images}/social-icons/twitter-blue.svg');
    }
    #loop-{$id} .mk-blog-meta-wrapper:before {
        background: url('{$theme_images}/social-icons/instagram.png') center center no-repeat;
    }
", $id);


$atts = array(
    'shortcode_name' => 'mk_blog',
    'style' => $style,
    'layout' => $layout,
    'column' => $column,
    'disable_meta' => $disable_meta,
    'grid_image_height' => $grid_image_height,
    'comments_share' => $comments_share,
    'full_content' => $full_content,
    'image_size' => $image_size,
    'excerpt_length' => $excerpt_length,
    'thumbnail_align' => $thumbnail_align,
    'image_quality' => $image_quality,
    'i' => 0
);


/* Main wrapper classes */
$wrapper_class[] = 'mk-blog-container';
$wrapper_class[] = 'mk-'.$style.'-wrapper';
$wrapper_class[] = $el_class;
$wrapper_class[] = ($style == "grid" && $transparent_border == 'true') ? 'no-border' : '';

switch ($magazine_strcutre) {
    case 1:
        $wrapper_class[] = 'mag-one-column';
        break;

    case 2:
        $wrapper_class[] = 'mag-two-column-left';
        break;

    case 3:
        $wrapper_class[] = 'mag-two-column-right';
        break;

    default:
        $wrapper_class[] = 'mag-one-column';
        break;
}
/*********/


$data_config[] = 'data-query="'.base64_encode(json_encode($query_options)).'"';
$data_config[] = 'data-loop-atts="'.base64_encode(json_encode($atts)).'"';
$data_config[] = 'data-pagination-style="'.$pagination_style.'"';
$data_config[] = 'data-max-pages="'.$r->max_num_pages.'"';
$data_config[] = 'data-loop-iterator="'.$r->query['posts_per_page'].'"';

if($style == 'grid' || $style == 'newspaper' || $style == 'spotlight') {
    $data_config[] = 'data-mk-component="Grid"';
    $data_config[] = 'data-grid-config=\'{"container":"#loop-'.$id.'", "item":".mk-isotop-item"}\'';
}
?>


<section id="loop-<?php echo $id; ?>" <?php echo implode(' ', $data_config); ?> class="js-loop js-el clear <?php echo implode(' ', $wrapper_class); ?>" <?php echo get_schema_markup('blog'); ?>>
    <?php
    if (is_archive()):
        $r = $wp_query;
        if (have_posts()):
            while (have_posts()):
                the_post();
                $atts['i']++;
                echo mk_get_shortcode_view('mk_blog', 'loop-styles/' . $style, true, $atts);
            endwhile;
        endif;
    else:
        if ($r->have_posts()):
            while ($r->have_posts()):
                $r->the_post();
                $atts['i']++;
                echo mk_get_shortcode_view('mk_blog', 'loop-styles/' . $style, true, $atts);
            endwhile;
        endif;
    endif;
    ?>    
</section>


<?php


if ($pagination != 'false') {
    echo mk_get_view('global', 'loop-pagination', true, ['pagination_style' => $pagination_style, 'r' => $r]);
}

wp_reset_postdata();