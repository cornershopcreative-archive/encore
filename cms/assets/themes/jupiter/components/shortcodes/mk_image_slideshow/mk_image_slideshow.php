<?php

$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$id = uniqid();

if ( $images == '' ) return null;

$images = explode( ',', $images );

require_once(THEME_FUNCTIONS . "/bfi_cropping.php");

// $slider_atts[] = 'data-animation="'.$effect.'"';
// $slider_atts[] = 'data-easing="swing"';
// $slider_atts[] = 'data-direction="horizontal"';
// $slider_atts[] = 'data-smoothHeight="'.$smooth_height.'"';
// $slider_atts[] = 'data-animationSpeed="'.$animation_speed.'"';
// $slider_atts[] = 'data-slideshowSpeed="'.$slideshow_speed.'"';
$slider_atts[] = 'data-pauseOnHover="'.$pause_on_hover.'"';
// $slider_atts[] = 'data-controlNav="false"';
// $slider_atts[] = 'data-directionNav="'.$direction_nav.'"';
// $slider_atts[] = 'data-isCarousel="true"';



mk_get_view('global', 'shortcode-heading', false, ['title' => $title]); ?>


<div class="mk-slideshow mk-flexslider mk-slider js-el <?php echo $el_class; ?>" <?php // echo implode(' ', $slider_atts); ?> 
	style="max-width:<?php echo $image_width; ?>px; margin-top:<?php echo $margin_top; ?>px; margin-bottom:<?php echo $margin_bottom; ?>px;"
	data-mk-component='SwipeSlideshow'
	data-swipeSlideshow-config='{
		"effect" : "<?php echo $effect ?>",
		"displayTime" : "<?php echo $slideshow_speed ?>",
		"transitionTime" : "<?php echo $animation_speed ?>",
		"nav" : "#flex-direction-nav-<?php echo $id ?>",
		"hasNav" : "<?php echo $direction_nav; ?>",
		"fluidHeight" :  <?php echo $smooth_height; ?>}' >

	<div class="mk-swiper-wrapper mk-slider-holder">

		<?php 
		foreach ( $images as $attach_id ) {
			$image_src_array = wp_get_attachment_image_src( $attach_id, 'full', true );
		?>
			<div class="mk-slider-slide">
				<img src="<?php echo mk_image_generator($image_src_array[ 0 ], $image_width, $image_height); ?>" alt="<?php echo trim(strip_tags( get_post_meta($attach_id, '_wp_attachment_image_alt', true) )); ?>" />
			</div>

		<?php } ?>


		<!-- empty PNG to stretch slider and make it responsive outside of js as the slider adjusts height and width to container sizes  -->
		<img src="<?php echo mk_image_generator('', $image_width, $image_height) ; ?>"  style="visibility: hidden;" />

	</div>


	<?php if( $direction_nav == 'true' ) { ?>
	<ul id="flex-direction-nav-<?php echo $id; ?>" class="flex-direction-nav">
		<li> <a class="flex-prev" href="#"  data-direction="prev"><i class="mk-jupiter-icon-arrow-left"></i></a> </li>
		<li> <a class="flex-next" href="#"  data-direction="next"><i class="mk-jupiter-icon-arrow-right"></i></a> </li>
	</ul>
	<?php } ?>
</div>
