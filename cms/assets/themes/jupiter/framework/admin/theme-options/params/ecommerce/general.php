<?php
$ecommerce_section[] = array(
    "type" => "sub_group",
    "id" => "mk_options_woo_general",
    "name" => __("Woocommerce / General Settings", "mk_framework") ,
    "desc" => __("", "mk_framework") ,
    "fields" => array(
        array(
            "name" => __("Shop Catalog Mode", "mk_framework") ,
            "desc" => __("This option will turn your shop to catalog mode. So users will not be able to shop in your site.", "mk_framework") ,
            "id" => "woocommerce_catalog",
            "default" => 'false',
            "type" => "toggle"
        ) ,
        
        array(
            "name" => __("Product Loop Image Height", "mk_framework") ,
            "desc" => __("Using this option you can change the product loop image height. default : 330", "mk_framework") ,
            "id" => "woo_loop_img_height",
            "default" => "300",
            "min" => "100",
            "max" => "1000",
            "step" => "1",
            "unit" => 'px',
            "type" => "range"
        ) ,
        array(
            "name" => __("Shop Loop Image Quality", "mk_framework") ,
            "id" => "woo_image_quality",
            "default" => "crop",
            "options" => array(
                "1" => __("Normal Size", "mk_framework") ,
                "2" => __("Retina Quality", "mk_framework") ,
            ) ,
            "type" => "dropdown"
        ) ,
        array(
            "name" => __("Shop Loop Image Size", "mk_framework") ,
            "id" => "woo_loop_image_size",
            "default" => "crop",
            "options" => mk_get_image_sizes(true),
            "type" => "dropdown"
        ) ,

        array(
            "name" => __("Product Category Archive Loop Title", "mk_framework") ,
            "desc" => __("Optionally you can change the product category page title. ", "mk_framework") ,
            "id" => "woocommerce_category_page_title",
            "default" => 'Shop',
            "type" => "text"
        ) ,
        array(
            "name" => __("Show Shopping Cart", "mk_framework") ,
            "desc" => __("Using this option you can remove header shopping cart.", "mk_framework") ,
            "id" => "shopping_cart",
            "default" => 'true',
            "type" => "toggle"
        ) ,
        array(
            "name" => __("Excerpt For Products Loop", "mk_framework") ,
            "desc" => __("If you would like to show some small description for products loop enable this option.", "mk_framework") ,
            "id" => "woocommerce_loop_show_desc",
            "default" => 'false',
            "type" => "toggle"
        ) ,
    ) ,
);
