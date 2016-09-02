<?php

global $mk_options;

$is_smooth_scroll 	= ($mk_options['smoothscroll'] == 'true'); // global mouse wheel settings
$is_fixed 			= ($view_params['attachment'] == 'fixed');
$is_full_structure 	= ($view_params['layout_structure'] == 'full');
$has_image 			= (!empty($view_params['bg_image']));
$has_parallax 		= ($view_params['parallax'] == 'true');
$has_clipper 		= (!$is_smooth_scroll) || ($is_fixed && !$has_parallax);
$has_blend_mode 	= ($view_params['blend_mode'] !== 'none');


// Escape if nothing to do here
if(!$is_full_structure || !$has_image) return false;



$layer_config[] = ( $has_parallax ) ? 'data-mk-component="Parallax"' : '';
$layer_config[] = ( $has_parallax ) ? 'data-parallax-config=\'{"speed" : ' . floatval($view_params['speed_factor']) . ' }\'' : '';

$id = 'background-layer--'.$view_params['id'];

$class[] = 'background-layer';
$class[] = mk_get_bg_cover_class($view_params['bg_stretch']);
// $class[] = $view_params['blend_mode'].'-blend-effect';
$class[] = $has_blend_mode ? 'js-blend-mode' : '';
$class[] = 'js-el';
$class[] = ($view_params['top_shadow'] == 'true') ? 'drop-top-shadow' : '';

$blend = $has_blend_mode ? 'data-blend="'.$view_params['blend_mode'].'"' : '';

?>


<?php 
/*
Cliper is helper for cases that parallax and position fixed is set.
so we do use this to enhance the scrolling performance! Thanks to @bart :)

We translate "background-attachement" by moving bg image onto separate layer and setting its "position".
For fixed position we need hard cropping to overcome the issue of escaping element from regular document flow.
It's tricky but far more performant than bg attachment fixed (which doesn't repaint in current [47.0] chrome at all) 
~ Bart
*/
if($has_clipper) { ?> 
	<div class="background-clipper"> 
<?php } ?>

	<?php if ( $has_parallax ) { ?>
		<div class="mk-section-preloader js-el" data-mk-component="Preloader">
			<div class="mk-section-preloader__icon"></div>
		</div>
	<?php } ?>	

	<div class="background-layer-holder">
		<div id="<?php echo $id ?>" class="<?php echo implode(' ', $class); ?>" <?php echo implode(' ', $layer_config); ?> <?php echo $blend; ?>>
			<?php if($has_blend_mode) { ?> 
				<div class="mk-blend-layer"></div>
				<canvas class="hidden-canvas"></canvas> <?php } 
			?>
		</div>
	</div>

<?php if($has_clipper) { ?> 
	</div> 
<?php }


