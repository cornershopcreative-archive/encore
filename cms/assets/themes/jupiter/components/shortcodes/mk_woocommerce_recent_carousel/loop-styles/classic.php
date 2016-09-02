<?php 

$slider_atts[] = 'data-selector=".woocommerce .mk-product-loop .products .item"';
$slider_atts[] = 'data-animation="slide"';
$slider_atts[] = 'data-easing="swing"';
$slider_atts[] = 'data-direction="horizontal"';
$slider_atts[] = 'data-smoothHeight="false"';
$slider_atts[] = 'data-slideshowSpeed="6000"';
$slider_atts[] = 'data-animationSpeed="500"';
$slider_atts[] = 'data-pauseOnHover="true"';
$slider_atts[] = 'data-controlNav="false"';
$slider_atts[] = 'data-directionNav="' . (!empty($title) ? 'true' : 'false') . '"';
$slider_atts[] = 'data-isCarousel="true"';
$slider_atts[] = 'data-itemWidth="276"';
$slider_atts[] = 'data-itemMargin="0"';
$slider_atts[] = 'data-minItems="1"';
$slider_atts[] = 'data-maxItems="4"';
$slider_atts[] = 'data-move="1"';


if (!empty($view_params['title'])) { ?>
    <h3 class="mk-fancy-title pattern-style"><span><?php echo $view_params['title']; ?></span>
    <a href="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>" class="mk-woo-view-all page-bg-color"><?php _e('VIEW ALL', 'mk_framework'); ?></a></h3>
<?php } ?>

<div <?php echo implode(' ', $slider_atts); ?> class="mk-flexslider mk-script-call js-flexslider">
    <?php echo do_shortcode('['.($view_params['featured'] == 'false' ? 'recent_products' : 'featured_products').' 
                                per_page="' . $view_params['per_page'] . '" 
                                orderby="' . $view_params['orderby'] . '" 
                                order="' . $view_params['order'] . '"]'); ?>
</div>