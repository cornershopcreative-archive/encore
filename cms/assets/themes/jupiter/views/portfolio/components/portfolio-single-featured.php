<?php 	
	if(get_post_meta( $post->ID, '_portfolio_featured_image', true ) == 'false') return false;
	global $mk_options;

	$image_height = $mk_options['Portfolio_single_image_height'];
	$image_width = mk_count_content_width();
	
	$post_type = get_post_meta( get_the_id(), '_single_post_type', true );
	$post_type = $post_type ? $post_type : 'image';
	

	switch ($post_type) {
		case 'image': 
			require_once(THEME_FUNCTIONS . "/bfi_cropping.php");
			$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
			$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => $image_width, 'height' => $image_height));
			?>
			<div class="featured-image">
				<a class="mk-lightbox" data-fancybox-group="portfolio-single-featured" title="<?php the_title(); ?>" href="<?php echo $image_src_array[0]; ?>">
					<img src="<?php echo mk_image_generator($image_src, $image_width, $image_height); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  height="<?php echo $image_height; ?>" width="<?php echo $image_width; ?>" itemprop="image" />
				</a>
			</div>
			<?php 	
			break;


			
		case 'video': 
		$skin_color = $mk_options['skin_color'];
		$video_id = get_post_meta( $post->ID, '_single_video_id', true );
		$video_site  = get_post_meta( $post->ID, '_single_video_site', true );
		?>
		<div class="mk-portfolio-video">
			<div class="mk-video-container">
				<?php
					switch ($video_site) {
						case 'vimeo':
							echo '<iframe src="//player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;color='.str_replace( "#", "", $skin_color ).'" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
							break;
						case 'youtube':
							echo '<iframe src="//www.youtube.com/embed/'.$video_id.'?showinfo=0" frameborder="0" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
							break;
						case 'dailymotion':
							echo '<iframe src="//www.dailymotion.com/embed/video/'.$video_id.'?logo=0" frameborder="0" width="'.$image_width.'" height="'.$image_height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
							break;		
					}
				?>
			</div>
		</div>
	<?php }

