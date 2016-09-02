<?php
vc_map(array(
    "name" => __("Skype Number", "mk_framework"),
    "base" => "mk_skype",
    'icon' => 'icon-mk-skype vc_mk_element-icon',
    'description' => __( 'Adds your Skype ID or Number.', 'mk_framework' ),
    "category" => __('Social', 'mk_framework'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Skype Number (For Display)", "mk_framework"),
            "param_name" => "display_number",
            "value" => "",
            "description" => __("Please provide your skype number. Once user clicks on the link it will make a calls if user has already installed skype. Feel Free to make spaces.", "mk_framework")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Your Skype Number (For making a call - exact number)", "mk_framework"),
            "param_name" => "number",
            "value" => "",
            "description" => __("Please enter your skype number exactly how you dial a number. Without spaces.", "mk_framework")
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