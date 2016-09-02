<?php
if($view_params['layout_structure'] == 'full') return false;

$video_atts = array(
    'bg_video'              => $view_params['bg_video'],
    'poster_image'          => $view_params['poster_image'],
    'video_loop'            => $view_params['video_loop'],
    'mp4'                   => $view_params['mp4'],
    'webm'                  => $view_params['webm'],
    'ogv'                   => $view_params['ogv'],
    'stream_host_website'   => $view_params['stream_host_website'],
    'stream_video_id'       => $view_params['stream_video_id'],
);
?> 
 

 <div class="mk-half-layout <?php echo $view_params['layout_structure']; ?>_layout">
    <?php echo mk_get_shortcode_view('mk_page_section', 'components/video-background', true, $video_atts); ?>
 </div>

 <div class="mk-half-layout-container page-section-content <?php echo $view_params['layout_structure']; ?>_layout">
 	<?php echo wpb_js_remove_wpautop( $view_params['content'] ); ?>
 </div>

