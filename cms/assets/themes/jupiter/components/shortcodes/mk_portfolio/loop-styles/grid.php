<?php

    
    
    global $mk_options;
    switch ($view_params['column']) {
        case 1:
            if ($view_params['layout'] == 'full') {
                $width = $mk_options['grid_width'] - 40;
            } else {
                $width = round(($mk_options['content_width'] / 100) * $mk_options['grid_width']) - 40;
            }
            $item_classes[] = 'one-column';
            break;
        case 2:
            if ($view_params['layout'] == 'full') {
                $width = round($mk_options['grid_width'] / 2) - 25;
            } else {
                $width = round((($mk_options['content_width'] / 100) * $mk_options['grid_width']) / 2) - 25;
            }
            $item_classes[] = 'two-column';
            break;
        case 3:
            if ($view_params['layout'] == 'full') {
                $width = round($mk_options['grid_width'] / 3) - 20;
            } else {
                $width = round((($mk_options['content_width'] / 100) * $mk_options['grid_width']) / 2) - 25;
            }
            $item_classes[] = 'three-column';
            break;
        case 4:
            if ($view_params['layout'] == 'full') {
                $width = round($mk_options['grid_width'] / 4) - 15;
            } else {
                $width = round((($mk_options['content_width'] / 100) * $mk_options['grid_width']) / 2) - 25;
            }
            $item_classes[] = 'four-column';
            break;
        case 5:
            if ($view_params['layout'] == 'full') {
                $width = round($mk_options['grid_width'] / 5) - 10;
            } else {
                $width = round((($mk_options['content_width'] / 100) * $mk_options['grid_width']) / 2) - 25;
            }
            $item_classes[] = 'five-column';
            break;
        case 6:
            if ($view_params['layout'] == 'full') {
                $width = round($mk_options['grid_width'] / 6) - 15;
            } else {
                $width = round((($mk_options['content_width'] / 100) * $mk_options['grid_width']) / 2) - 25;
            }
            $item_classes[] = 'six-column';
            break;
    }

    if ($view_params['image_size'] == 'crop') {
        $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true);
        $image_output_src = mk_image_generator($image_src_array[0], $width * $view_params['image_quality'], $view_params['height'] * $view_params['image_quality']);
    } 
    else {
        $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id() , $view_params['image_size'], true);
        $image_output_src = $image_src_array[0];
    }

    /* Dynamic color for slidebox meta overlay hover scenario */
    $hover_overlay_value = get_post_meta($post->ID, '_hover_skin', true);
    $hover_overlay       = !empty($hover_overlay_value) ? (' style="background-color:' . $hover_overlay_value . '"') : '';
    /* --- */
    

    /* Dynamic class names to be added to article tag. */
    $item_classes[] =  $view_params['hover_scenarios'] . '-hover';
    $item_classes[] = implode(' ', mk_get_custom_tax(get_the_id(), 'portfolio', false, true));
    /* ---- */

?>

<article id="<?php the_ID(); ?>" class="mk-portfolio-item mk-portfolio-grid-item <?php echo implode(' ', $item_classes); ?>">

    <div style="" class="item-holder">
        
        <div class="featured-image <?php if($view_params['permalink_icon'] == 'false' && $view_params['zoom_icon'] == 'false') echo 'buttons-disabled'; ?>" onclick="">
            
            <img alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="<?php echo $image_output_src; ?>" width="<?php echo $width; ?>" height="<?php echo $view_params['height']; ?>"  />
            
                <?php echo mk_get_shortcode_view('mk_portfolio', 'components/hover-overlay', true, ['hover_scenarios' => $view_params['hover_scenarios']]); ?>

                <?php if($view_params['hover_scenarios'] != 'none') : ?>    
                    <div class="icons-holder">
                        <?php
                            echo mk_get_shortcode_view('mk_portfolio', 'components/permalink-icon', true, ['permalink_icon' => $view_params['permalink_icon'],'target' => $view_params['target']]);
                            echo mk_get_shortcode_view('mk_portfolio', 'components/zoom-icon', true, ['zoom_icon' => $view_params['zoom_icon'], 'permalink_icon' => $view_params['permalink_icon']]);
                        ?>
                    </div>
                    
                    <div class="portfolio-meta"<?php if($view_params['hover_scenarios'] == 'slidebox') $hover_overlay; ?>>
                        <div class="add-middle-align">
                            <?php 
                                echo mk_get_shortcode_view('mk_portfolio', 'components/title', true, ['permalink_icon' => $view_params['permalink_icon'],'target' => $view_params['target']]);
                                echo mk_get_shortcode_view('mk_portfolio', 'components/meta-category-date', true, ['meta_type' => $view_params['meta_type']]);    
                            ?>        
                        </div>
                    </div><!-- Portfolio meta -->
                <?php endif; ?>

        </div><!-- Featured Image -->
    </div><!-- Item Holder -->
</article>