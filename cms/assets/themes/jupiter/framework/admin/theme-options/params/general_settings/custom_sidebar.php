<?php

$general_section[] = array(
    "type" => "sub_group",
    "id" => "mk_options_sidebar",
    "name" => __("General / Custom Sidebar", "mk_framework") ,
    "desc" => __("", "mk_framework") ,
    "fields" => array(
        array(
            "name" => __("Create a new sidebar", "mk_framework") ,
            "desc" => __("Enter a name for new sidebar. It must be a valid name which starts with a letter, followed by letters, numbers, spaces, or underscores", "mk_framework") ,
            "id" => "sidebars",
            "default" => '',
            "type" => 'custom_sidebar',
        ) ,
    ) ,
);
