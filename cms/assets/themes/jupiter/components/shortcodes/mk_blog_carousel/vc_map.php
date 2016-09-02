<?php
    vc_map(array(
    "name" => __("Posts Carousel", "mk_framework"),
    "base" => "mk_blog_carousel",
    'icon' => 'icon-mk-blog-carousel vc_mk_element-icon',
    "category" => __('Loops', 'mk_framework'),
    'description' => __( 'Shows blog posts in carousel.', 'mk_framework' ),
    "params" => array(
        array(
            "heading" => __("Choose Post Type", 'mk_framework'),
            "description" => __("", 'mk_framework'),
            "param_name" => "post_type",
            "value" => array(
                __("Blog", 'mk_framework') => "post",
                __("News", 'mk_framework') => "news"
            ),
            "type" => "dropdown"
        ),
        array(
            "type" => "textfield",
            "heading" => __("Heading Title", "mk_framework"),
            "param_name" => "title",
            "value" => "",
            "description" => __("", "mk_framework")
        ),
        array(
            "heading" => __("View All Page", 'mk_framework'),
            "description" => __("Select the page you would like to navigate when [View All] link is clicked.", 'mk_framework'),
            "param_name" => "view_all",
            "value" => mk_get_page_enteries(50),
            "type" => "dropdown"
        ),
        array(
            "type" => "range",
            "heading" => __("How many Posts?", "mk_framework"),
            "param_name" => "count",
            "value" => "10",
            "min" => "-1",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("How many Posts would you like to show? (-1 means unlimited)", "mk_framework")
        ),
        array(
            "type" => "toggle",
            "heading" => __("Enable Excerpt", "mk_framework"),
            "param_name" => "enable_excerpt",
            "value" => "false",
            "description" => __("", "mk_framework"),
            "dependency" => array(
                'element' => "post_type",
                'value' => array(
                    'post'
                )
            )
        ),
        array(
            "type" => "range",
            "heading" => __("Offset", "mk_framework"),
            "param_name" => "offset",
            "value" => "0",
            "min" => "0",
            "max" => "50",
            "step" => "1",
            "unit" => 'posts',
            "description" => __("Number of post to displace or pass over, it means based on your order of the loop, this number will define how many posts to pass over and start from the nth number of the offset.", "mk_framework")
        ),
        array(
            "type" => "multiselect",
            "heading" => __("Select Specific Categories to Show", "mk_framework"),
            "param_name" => "cat",
            "options" => mk_get_category_enteries('category', 50),
            "value" => '',
            "description" => __("", "mk_framework"),
            "dependency" => array(
                'element' => "post_type",
                'value' => array(
                    'post'
                )
            )
        ),
        array(
            "type" => "multiselect",
            "heading" => __("Select Specific Posts", "mk_framework"),
            "param_name" => "posts",
            "options" => mk_get_post_enteries('post', 40),
            "value" => '',
            "description" => __("", "mk_framework"),
            "dependency" => array(
                'element' => "post_type",
                'value' => array(
                    'post'
                )
            )
        ),
        array(
            "type" => "multiselect",
            "heading" => __("Select Specific Authors", "mk_framework"),
            "param_name" => "author",
            "options" => mk_get_authors(50),
            "value" => '',
            "description" => __("", "mk_framework")
        ),
        array(
            "heading" => __("Order", 'mk_framework'),
            "description" => __("Designates the ascending or descending order of the 'orderby' parameter.", 'mk_framework'),
            "param_name" => "order",
            "value" => array(
                __("DESC (descending order)", 'mk_framework') => "DESC",
                __("ASC (ascending order)", 'mk_framework') => "ASC"

            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Orderby", 'mk_framework'),
            "description" => __("Sort retrieved Blog items by parameter.", 'mk_framework'),
            "param_name" => "orderby",
            "value" => $mk_orderby,
            "type" => "dropdown"
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "mk_framework"),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", "mk_framework")
        )
    )
));