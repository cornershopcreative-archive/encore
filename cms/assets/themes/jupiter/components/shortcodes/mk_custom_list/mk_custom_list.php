<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$id = Mk_Static_Files::shortcode_id();


if ( substr( $style, 0, 1 ) == 'f' ) {

	$font_family = 'FontAwesome';

} else if(substr( $style, 0, 2 ) == 'e6' ) {

	$font_family = 'Pe-icon-line';

} else {

	$font_family = 'Icomoon';
}

Mk_Static_Files::addCSS('
#list-'.$id.' {margin-bottom:'.$margin_bottom.'px}
#list-'.$id.' ul li:before {
    font-family:'.$font_family.';
    content: "\\'.$style.'";
    color:'.$icon_color.'
}', $id);



$class[] = get_viewport_animation_class($animation);
$class[] = 'mk-align-'.$align;
$class[] = $el_class;

?>

<div id="list-<?php echo $id; ?>" class="mk-list-styles <?php echo implode(' ', $class); ?> clear">
	
	<?php mk_get_view('global', 'shortcode-heading', false, ['title' => $title]); ?>

	<?php echo wpb_js_remove_wpautop( $content, true ); ?>

</div>
