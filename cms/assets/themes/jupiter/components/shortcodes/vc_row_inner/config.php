<?php
extract(shortcode_atts(array(
    'id' => '',
    'visibility' => '',
    'is_fullwidth_content' => 'true',
    'css' => '',
    'animation' => '',
    'column_padding' => 0,
    'padding' => 0,
    'attached' => 'false',
    'el_class' => '',
) , $atts));
Mk_Static_Files::addAssets('vc_row_inner');

