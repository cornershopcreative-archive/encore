<?php
/**
 *  Shortcode defaults and user settings
 * ============================================================== */

extract( shortcode_atts( array(
	'latitude' 			=> '',
	'longitude' 		=> '',
	'address' 			=> '',

	'latitude_2' 		=> '',
	'longitude_2' 		=> '',
	'address_2' 		=> '',

	'latitude_3'		=> '',
	'longitude_3'		=> '',
	'address_3' 		=> '',

	'height' 			=> '300',
	'map_height' 		=> 'custom',
	'map_type' 			=> 'ROADMAP',
	'zoom' 				=> '14',
	'draggable' 		=> 'true',
	'pan_control'		=> 'true',
	'zoom_control' 		=> 'true',
	'map_type_control' 	=> 'true',
	'scale_control' 	=> 'true',
	'pin_icon' 			=> '',
	'modify_json'		=> 'false',
	'map_json' 			=> '',
	'modify_coloring' 	=> 'false',
	'hue' 				=> '#ccc',
	'saturation' 		=> '1',
	'lightness'			=> '1',
	'content_bg_color'	=> '#4f4f4f',
	'content_font_color'=> '#fff',
), $atts ) );
Mk_Static_Files::addAssets('mk_advanced_gmaps');