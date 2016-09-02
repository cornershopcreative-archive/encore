<?php
extract( shortcode_atts( array(
	'title' 			=> '',
	'number'		 	=> '',
	'display_number' 	=> '',
	"el_class" 			=> '',
), $atts ) );
Mk_Static_Files::addAssets('mk_skype');