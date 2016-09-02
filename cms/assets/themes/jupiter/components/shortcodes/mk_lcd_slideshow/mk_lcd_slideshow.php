<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

if ( $images == '' ) return null;
$images = explode( ',', $images );

$id = uniqid();

require_once(THEME_FUNCTIONS . "/bfi_cropping.php");

$class[] = get_viewport_animation_class($animation);
$class[] = $el_class;

$slider_atts[] = 'data-animation="fade"';
$slider_atts[] = 'data-smoothHeight="false"';
$slider_atts[] = 'data-animationSpeed="'.$animation_speed.'"';
$slider_atts[] = 'data-slideshowSpeed="'.$slideshow_speed.'"';
$slider_atts[] = 'data-pauseOnHover="'.$pause_on_hover.'"';
$slider_atts[] = 'data-controlNav="false"';
$slider_atts[] = 'data-directionNav="true"';
$slider_atts[] = 'data-isCarousel="false"';

mk_get_view('global', 'shortcode-heading', false, ['title' => $title]); ?>


<div id="lcd-slider-<?php echo $id; ?>" class="mk-lcd-slideshow mk-script-call mk-flexslider js-flexslider <?php echo implode(' ', $class); ?>" style="max-width:872px;" <?php echo implode(' ', $slider_atts); ?>>
	<img style="display:none" class="mk-lcd-image" src="<?php echo THEME_IMAGES; ?>/theme-lcd-slideshow.png" alt="LCD" />
	<div class="slideshow-container">
		<ul class="mk-flex-slides" style="max-width:838px;max-height:506px;">
			<?php 
				$i = -1;
				foreach ( $images as $attach_id ) {
					$i++;
					$image_src_array = wp_get_attachment_image_src( $attach_id, 'full', true );
					$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => 810, 'height' => 475));
					?>
						<li>
							<img alt="<?php echo trim(strip_tags( get_post_meta($attach_id, '_wp_attachment_image_alt', true) )); ?>" src="<?php echo mk_image_generator($image_src, 810, 475); ?>" />
						</li>
					<?php
				}
			?>
		</ul>
	</div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
            jQuery("#lcd-slider-<?php echo $id; ?>").find(".mk-lcd-image").fadeIn();
    });
</script>