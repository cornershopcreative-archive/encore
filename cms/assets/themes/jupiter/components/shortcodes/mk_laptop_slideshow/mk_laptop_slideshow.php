<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );


if ( $images == '' ) return null;

$id = uniqid();

require_once(THEME_FUNCTIONS . "/bfi_cropping.php");

$images = explode( ',', $images );


$slider_atts[] = 'data-animation="fade"';
$slider_atts[] = 'data-smoothHeight="false"';
$slider_atts[] = 'data-animationSpeed="'.$animation_speed.'"';
$slider_atts[] = 'data-slideshowSpeed="'.$slideshow_speed.'"';
$slider_atts[] = 'data-pauseOnHover="'.$pause_on_hover.'"';
$slider_atts[] = 'data-controlNav="false"';
$slider_atts[] = 'data-directionNav="true"';
$slider_atts[] = 'data-isCarousel="false"';

$container_class[] = 'mk-laptop-slideshow-shortcode';
$container_class[] = 'mk-flexslider';
$container_class[] = 'js-flexslider';
$container_class[] = 'mk-script-call';
$container_class[] = get_viewport_animation_class($animation);
$container_class[] = $size.'-laptop';
$container_class[] = $el_class;


mk_get_view('global', 'shortcode-heading', false, ['title' => $title]); ?>


<div <?php echo implode(' ', $slider_atts); ?> style="max-height:481px;max-width:836px;" class="<?php echo implode(' ', $container_class); ?>" id="flexslider_<?php echo $id; ?>">
	<img style="display:none" class="mk-laptop-image" alt="Laptop Slideshow" src="<?php echo THEME_IMAGES; ?>/theme-laptop-full.png" alt="laptop" />
	<div class="slideshow-container">
		<ul class="mk-flex-slides" style="max-width:635px;max-height:405px;">

		<?php foreach ( $images as $attach_id ) {
				$image_src_array = wp_get_attachment_image_src( $attach_id, 'full', true );
				$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => 635, 'height' => 405)); ?>
				<li>
					<img alt="<?php echo trim(strip_tags( get_post_meta($attach_id, '_wp_attachment_image_alt', true) )); ?>" src="<?php echo mk_image_generator($image_src, 635, 405); ?>" />
				</li>
		<?php } ?>

		</ul>
	</div>
</div>


<script type="text/javascript">
	jQuery(document).ready(function() {
	        jQuery("#flexslider_<?php echo $id; ?>").find(".mk-laptop-image").fadeIn();
	});
</script>