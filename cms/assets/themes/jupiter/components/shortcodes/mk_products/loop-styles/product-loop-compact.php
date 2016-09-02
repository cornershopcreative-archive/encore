
<article <?php echo post_class('item mk--col '. $view_params['product_col'].get_viewport_animation_class($view_params['animation']) ); ?>  >
	<div class="mk-product-holder">
		<div class="product-loop-thumb">
			<?php echo $view_params['out_of_stock_badge']; ?>
			<?php echo $view_params['sale_of_stock_badge']; ?>
			<a href="<?php echo $view_params['product_link']; ?>" title="<?php echo $view_params['thumb_title']; ?>" class="product-link">
				<img src="<?php echo $view_params['thumb_image']; ?>" class="product-loop-image" alt="<?php echo $view_params['thumb_title']; ?>" title="<?php echo $view_params['thumb_title']; ?>" itemprop="image">
				<span class="product-loading-icon added-cart"></span>
				<?php if(!empty($view_params['thumb_hover_image'])) { ?>
					<img src="<?php echo $view_params['thumb_hover_image']; ?>" alt="<?php echo $view_params['thumb_title']; ?>" class="product-hover-image" title="<?php echo $view_params['thumb_title']; ?>" >
				<?php } ?>	
			</a>
			<div class="product-item-footer">
				<span class="product-item-rating">
					<?php echo $view_params['product_rating']; ?>
				</span>
				<a href="<?php echo $view_params['product_add_link']; ?>" data-product_id="<?php echo $view_params['product_id']; ?>" rel="nofollow" class="add_to_cart_button product_type_<?php echo $view_params['product_type']; ?>">
					<i class="<?php echo $view_params['product_add_icon']; ?>"></i>
					<?php echo $view_params['product_add_label']; ?>
				</a>	
			</div>
		</div>
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		<div class="mk-shop-item-detail">
			<div class="mk-love-holder">
				<?php if( function_exists('mk_love_this') ) mk_love_this(); ?>
			</div>
			<h3 class="product-title">
				<a href="<?php echo $view_params['product_link']; ?>" title="<?php echo $view_params['thumb_title']; ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
			<div class="product-item-desc">
				<?php echo $view_params['item_desc']; ?>
			</div>
		</div>
	</div>
</article>
