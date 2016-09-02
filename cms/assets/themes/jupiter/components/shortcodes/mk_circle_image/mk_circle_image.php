<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );
$html = file_get_contents( $path . '/template.php' );
$html = phpQuery::newDocument( $html );
$id = Mk_Static_Files::shortcode_id();

require_once(THEME_FUNCTIONS . "/bfi_cropping.php");
$image_src = bfi_thumb( $src, array('width' => $image_diameter, 'height' => $image_diameter));

$container = pq('.mk-circle-image');
$containerHolder = $container->find('.mk-circle-image__holder');

$container->attr('id', 'mk-circle-image-'.$id);
$container->addClass($el_class);
$container->addClass($visibility);

if ( $animation != '' ) {
	$container->addClass(get_viewport_animation_class($animation));
}
if ( !empty( $heading_title ) ) {
	$container->prepend('<h3 class="mk-circle-image__title mk-fancy-title pattern-style"></h3>')
		->find('.mk-circle-image__title')
		->html('<span>'.$heading_title.'</span>');
}
$containerHolder->append('<img class="mk-circle-image__img">')
		->find('.mk-circle-image__img')
		->attr('title', $heading_title)
		->attr('alt', $heading_title)
		->attr('src', mk_image_generator($image_src, $image_diameter, $image_diameter));

if($link) {
	$containerHolder->find('.mk-circle-image__img')
			->wrap('<a href="'.$link.'">');
}


print $html;
