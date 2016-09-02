<?php
vc_map(array(
    "name" => __("Portfolio Carousel", "mk_framework"),
    "base" => "mk_portfolio_carousel",
    'icon' => 'icon-mk-portfolio-carousel vc_mk_element-icon',
    "category" => __('Loops', 'mk_framework'),
    'description' => __( 'Shows Portfolio loop in carousel.', 'mk_framework' ),
    "params" => array(
        array(
            "heading" => __("Style", 'mk_framework'),
            "description" => __("Select which style you would like to use.", 'mk_framework'),
            "param_name" => "style",
            "value" => array(
                __("Classic", 'mk_framework') => "classic",
                __("Modern (Screen wide)", 'mk_framework') => "modern"
            ),
            "type" => "dropdown"
        ),
        array(
            "heading" => __("Hover Scenarios", 'mk_framework'),
            "description" => __("This is what happens when user hovers over a portfolio item. Different animations and styles will be showed up on each scenario.", 'mk_framework'),
            "param_name" => "hover_scenarios",
            "value" => array(
                __("Slide Box", 'mk_framework') => "slidebox",
                __("Fade Box", 'mk_framework') => "fadebox",
                __("Zoom In Box", 'mk_framework') => "zoomin",
                __("Zoom Out Box", 'mk_framework') => "zoomout",
                __("Light Zoom In", 'mk_framework') => "light-zoomin",
                __("3D Cube", 'mk_framework') => "cube",
                __("None (only link to the single portfolio page)", 'mk_framework') => "none"
            ),
            "type" => "dropdown",
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'modern'
                )
            )
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
            "description" => __("Select the page you would like to navigate if [View All] link is clicked.", 'mk_framework'),
            "param_name" => "view_all",
            "value" => mk_get_page_enteries(50),
            "type" => "dropdown",
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'classic'
                )
            )
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
            "type" => "range",
            "heading" => __("Visible Items at Once", "mk_framework"),
            "param_name" => "show_items",
            "value" => "4",
            "min" => "1",
            "max" => "10",
            "step" => "1",
            "unit" => 'items',
            "description" => __("How many items you would like to show in carousel?", "mk_framework"),
            "dependency" => array(
                'element' => "style",
                'value' => array(
                    'modern'
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
            "type" => "dropdown",
            "heading" => __("Increase Quality of Image", "mk_framework"),
            "param_name" => "image_quality",
            "value" => array(
                __("Normal Quality", 'mk_framework') => "1",
                __("Images 2 times bigger (retina compatible)", 'mk_framework') => "2",
                __("Images 3 times bigger (fullwidth row compatible)", 'mk_framework') => "3"
            ),
            "description" => __("If you want portfolio images to be retina compatible or you just want to use it in fullwidth row, you may consider increasing the image size. This option will help you define the image quality yourself.", "mk_framework")
        ),
        array(
            "type" => "multiselect",
            "heading" => __("Select specific Posts", "mk_framework"),
            "param_name" => "posts",
            "options" => mk_get_post_enteries('portfolio', 40),
            "value" => '',
            "description" => __("", "mk_framework")
        ),
        array(
            "type" => "multiselect",
            "heading" => __("Select specific Authors", "mk_framework"),
            "param_name" => "author",
            "options" => mk_get_authors(50),
            "value" => '',
            "description" => __("", "mk_framework")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Select Specific Categories.", "mk_framework"),
            "param_name" => "categories",
            "value" => '',
            "description" => __("You will need to go to Wordpress Dashboard => Portfolios => Portfolio Categories. In the right hand find Slug column. You will need to add portfolio category slugs in this optionand add comma to separate them.", "mk_framework")
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
            "description" => __("Sort retrieved Portfolio items by parameter.", 'mk_framework'),
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