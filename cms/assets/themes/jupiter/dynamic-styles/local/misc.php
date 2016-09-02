<?php

global $mk_options;

if(!is_header_show()) return false;

$toolbar_toggle_height = (is_header_toolbar_show() == 'true') ? 32 : 0;
$header_padding_holder = $mk_options['header_height'] + $toolbar_toggle_height;

$sticky_style = !empty($mk_options['header_sticky_style']) ? $mk_options['header_sticky_style'] : 'false';

if($sticky_style == 'false') return false;

if(is_header_transparent()) return false;

Mk_Static_Files::addLocalStyle("

	.header-style-1 .mk-header-padding-wrapper,
	.header-style-2 .mk-header-padding-wrapper,
	.header-style-3 .mk-header-padding-wrapper {
		padding-top:{$header_padding_holder}px;
	}

");