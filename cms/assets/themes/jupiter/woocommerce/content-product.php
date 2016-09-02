<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $mk_options;


// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ){
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ){
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product->is_visible() ){
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;


$grid_width = $mk_options['grid_width'];
$content_width = $mk_options['content_width'];
$height = $mk_options['woo_loop_img_height'];
$quality = $mk_options['woo_image_quality'];

// Sets the shop loop columns from theme options.
if(is_shop()) {
	$layout = get_post_meta( global_get_post_id(), '_layout', true );
	if($layout == 'full') {
		$classes[] = 'item mk--col mk--col--3-12';
		$width = round($grid_width/4) - 28;
		$column_width = round($grid_width/4);
	} else {
		$classes[] = 'item mk--col mk--col--4-12';
		$width = round((($content_width / 100) * $grid_width)/3) - 31;
		$column_width = round($grid_width/3);
	}
} else {
	switch ($woocommerce_loop['columns']) {

	case 4:
			$classes[] = 'item mk--col mk--col--3-12';
			$width = round($grid_width/4) - 28;
			$column_width = round($grid_width/4);
		break;
	case 3:
			$classes[] = 'item mk--col mk--col--4-12';
			$width = round($grid_width/3) - 33;
			$column_width = round($grid_width/3);
		break;
	case 2:
			$classes[] = 'item mk--col mk--col--6-12';
			$width = round($grid_width/2) - 38;
			$column_width = round($grid_width/2);
		break;
	case 1:
			$classes[] = 'item mk--col mk--col--12-12';
			$width = $grid_width - 58;
			$column_width = $grid_width;
		break;			
	
	default:
			$classes[] = 'item mk--col mk--col--3-12';
			$width = round($grid_width/4) - 28;
			$column_width = round($grid_width/4);
		break;
}

}

?>

<article <?php post_class($classes); ?>>
<div class="mk-product-holder">
		<div class="product-loop-thumb">
		<?php

if ( ! $product->is_in_stock() ) {
	echo '<span class="mk-out-stock">'.__( 'OUT OF STOCK', 'mk_framework' ).'</span>';
}

if ($product->is_on_sale()) :
 echo apply_filters('woocommerce_sale_flash', '<span class="mk-onsale">'.__( 'Sale', 'mk_framework' ).'</span>', $post, $product);
endif;


$loop_image_size = isset($mk_options['woo_loop_image_size']) ? $mk_options['woo_loop_image_size'] : 'crop';

if ( has_post_thumbnail() ) {
	require_once(THEME_FUNCTIONS . "/bfi_cropping.php");	

	echo '<a href="'. get_permalink().'" class="product-link">';

	if($loop_image_size == 'crop') {
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
        $image_output_src = mk_image_generator($image_src_array[0], $width*$quality, $height*$quality, 'true');
	} else {
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), $loop_image_size, true);
        $image_output_src = $image_src_array[0];
	}

	echo '<img src="'.$image_output_src.'" class="product-loop-image" alt="'.get_the_title().'" title="'.get_the_title().'" itemprop="image" />';	
	
	echo '<span class="product-loading-icon added-cart"></span>';

	$product_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );

	if ( !empty( $product_gallery ) ) {
		$gallery = explode( ',', $product_gallery );
		$hover_image_id  = $gallery[0];

    if($loop_image_size == 'crop') {
		$image_src_hover_array = wp_get_attachment_image_src($hover_image_id, 'full', true);
        $image_hover_src = mk_image_generator($image_src_hover_array[0], $width*$quality, $height*$quality, 'true');

	} else {
		$image_src_hover_array = wp_get_attachment_image_src($hover_image_id, $loop_image_size, true);
        $image_hover_src = $image_src_hover_array[0];
	}
		
		echo '<img src="'.$image_hover_src.'" alt="'.get_the_title().'" class="product-hover-image" title="'.get_the_title().'">';


	}
	echo '</a>';

} else {

	echo '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="'.$width*$quality.'" height="'.$height*$quality.'" />';

}
?>
	
	<?php if($mk_options['woocommerce_catalog'] == 'false') { ?>
	<div class="product-item-footer">
			<?php if ( $rating_html = $product->get_rating_html() ) : ?>
				<span class="product-item-rating"><?php echo $rating_html; ?></span>
			<?php endif; ?>

			<?php
				/**
				 * woocommerce_after_shop_loop_item hook
				 *
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
			?>
	</div>
<?php } ?>
</div>
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

		<div class="mk-shop-item-detail">
			<div class="mk-love-holder">
						<?php if( function_exists('mk_love_this') ) mk_love_this(); ?>
			</div>
			
			<h3 class="product-title"><a href="<?php echo get_permalink();?>"><?php the_title(); ?></a></h3>
			<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>

			<?php 
			if($mk_options['woocommerce_loop_show_desc'] == 'true') : 
				echo '<div class="product-item-desc">' . apply_filters( 'woocommerce_short_description', $post->post_excerpt ) . '</div>'; 
			endif;
			?>
		</div>
</div>
</article>
