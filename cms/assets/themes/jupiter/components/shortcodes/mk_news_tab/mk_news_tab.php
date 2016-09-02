<?php
$phpinfo = pathinfo(__FILE__);
$path = $phpinfo['dirname'];
include ($path . '/config.php');

$id = uniqid();

$output = '';
require_once (THEME_FUNCTIONS . "/bfi_cropping.php");

$output .= mk_get_view('global', 'shortcode-heading', false, ['title' => $widget_title]);

$output .= '<div class="mk-news-tab mobile-' . $responsive . ' ' . $el_class . ' js-el" data-mk-component="Tabs">';

$cat_terms = get_terms('news_category', 'hide_empty=1');
$output .= '<div class="mk-news-tab-heading clear">';
$output .= '<span class="mk-news-tab-title">' . $tab_title . '</span>';
$output .= '<ul class="mk-tabs-tabs">';
$tab_count = 0;
foreach ($cat_terms as $cat_term) {

    $output.= '<li class="mk-tabs-tab';
    if($tab_count == 0) {
    	$output .= ' is-active';
    }
    $output .= '"><a href="#">' . $cat_term->name . '</a></li>';
    $tab_count++;
}
$output .= '</ul>';
$output .= '</div>';
$output.= '<div class="mk-tabs-panes page-bg-color clear">';

$pane_count = 0;
foreach ($cat_terms as $cat_term) {

    $output.= '<div class="mk-tabs-pane';
    if($pane_count == 0) {
    	$output .= ' is-active ';
    }
     $output.= ' clear">';
    $output.= '<div class="title-mobile">' . $cat_term->name . '</div>';
    $query = array(
        'post_type' => 'news',
        'posts_per_page' => 3,
        'offset' => 0
    );
    
    $query['tax_query'] = array(
        array(
            'taxonomy' => 'news_category',
            'field' => 'slug',
            'terms' => $cat_term->slug
        )
    );
    
    $r = new WP_Query($query);
    $i = 0;
    if ($r->have_posts()):
        while ($r->have_posts()):
            $r->the_post();
            $i++;
            
            $terms = get_the_terms(get_the_id() , 'news_category');
            $terms_slug = array();
            $terms_name = array();
            if (is_array($terms)) {
                foreach ($terms as $term) {
                    $terms_slug[] = $term->slug;
                    $terms_name[] = $term->name;
                }
            }
            if ($i == 1) {
                $output.= '<div class="news-tab-wrapper left-side">';
                
                $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true);
                $image_src = bfi_thumb($image_src_array[0], array(
                    'width' => 500,
                    'height' => 340
                ));
                if (has_post_thumbnail()) {
                    $output.= '<a href="' . get_permalink() . '" class="news-tab-thumb"><img alt="' . get_the_title() . '" title="' . get_the_title() . '" src="' . $image_src . '" /></a>';
                }
                $output.= '<h3 class="the-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                $output.= '<div class="the-excerpt">' . get_the_excerpt() . '</div>';
                $output.= '<a class="new-tab-readmore" href="' . get_permalink() . '">' . __('Read More', 'mk_framework') . '<i class="mk-icon-caret-right"></i></a>';
                $output.= '</div>';
            } 
            else {
                $output.= '<div class="news-tab-wrapper">';
                $output.= '<h3 class="the-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                $output.= '<div class="the-excerpt">' . get_the_excerpt() . '</div>';
                $output.= '<a class="new-tab-readmore" href="' . get_permalink() . '">' . __('Read More', 'mk_framework') . '<i class="mk-icon-caret-right"></i></a>';
                $output.= '</div>';
            }
        endwhile;
        wp_reset_query();
    endif;
    $output.= '</div>';
    $pane_count++;
}

$output.= '</div>';
$output.= '</div> ';

echo $output;

