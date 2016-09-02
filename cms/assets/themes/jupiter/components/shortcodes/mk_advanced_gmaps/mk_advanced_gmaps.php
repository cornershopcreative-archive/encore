<?php

$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$id = uniqid();

// Quit if no lat / lang
if( $longitude == '' && $latitude == '') return null;

// Zoom cannot be less than one
if( $zoom < 1 ) $zoom = 1;

// Disable coloring options when full JSON customization is passed
if( $modify_json == 'true' ) $modify_coloring = 'false';



/**
 * Collect JSON config for JS
 * ==================================================================================*/

$json = array();
$json['places']  = array();
$json['options'] = array();
$json['style']   = array();
$json['icon']    = $pin_icon;


$json['places'][] = array(
	"address"   => htmlentities($address),
	"latitude"  => $latitude,
	"longitude" => $longitude
);

if( !empty($latitude_2) && !empty($longitude_2) ) {
	$json['places'][] = array(
		"address"   => htmlentities($address_2),
		"latitude"  => $latitude_2,
		"longitude" => $longitude_2
	);
}

if( !empty($latitude_3) && !empty($longitude_3) ) {
	$json['places'][] = array(
		"address"   => htmlentities($address_3),
		"latitude"  => $latitude_3,
		"longitude" => $longitude_3
	);
}


$json['options'] = array(
	"zoom"      		=> intval($zoom),
	"draggable"		=> $draggable == 'true' ? true : false,
	"panControl"		=> $pan_control == 'true' ? true : false,
	"zoomControl"		=> $zoom_control == 'true' ? true : false,
	"scaleControl"		=> $scale_control == 'true' ? true : false,
	"mapTypeControl"	=> $map_type_control == 'true' ? true : false,
    	"mapTypeId"		=> $map_type
);


if( $modify_coloring != 'false' ) {
	$json['style'][] = array(
	    "stylers" => array(
	        array(  "hue" => $hue ),
		array(  "saturation" 	 => $saturation ),
	    	array(  "lightness"   	=> $lightness ),
		array(  "featureType" 	=> "landscape.man_made",
			"stylers" 		=> array(
				array( "visibility" => "on" )
		    	)
		)
	    )
	);
}
else if( $modify_json != 'false' ) {
	$json['style'] = json_decode( urldecode(base64_decode($map_json)), true );
}


$json = str_replace("'", "&apos;", json_encode( $json ) );


/**
 * Custom CSS Output
 * ==================================================================================*/

$style = array();
$class = '';


if( $map_height == 'custom' ) {
	$style['mk_advanced_gmap'] = 'height: '. $height .'px;';
}

// $map_height = 'test';
$full_height = $map_height != 'custom' ? true : false;



// Mk_Static_Files::addCSS('
// 	#mk-advanced-gmaps-'.$id.' .gm-style-iw {
// 	    background-color: '.$content_bg_color.';
// 	}
// 	#mk-advanced-gmaps-'.$id.' .gm-style-iw::after {
// 		border-color: '.$content_bg_color.' transparent transparent;
// 	}
// 	#mk-advanced-gmaps-'.$id.' .gm-style-iw .info_content p,
// 	#mk-advanced-gmaps-'.$id.' .gm-style-iw .info_content p strong {
// 		color: '.$content_font_color.';
// 	}
// ', $id);


include( $path . '/template.php' );
