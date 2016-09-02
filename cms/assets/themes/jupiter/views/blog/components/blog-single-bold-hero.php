<?php

/**
 * template part for blog single bold style header heading single.php. views/blog/components
 *
 * @author      Artbees
 * @package     jupiter/views
 * @version     5.0.0
 */

global $mk_options;

if (mk_get_blog_single_style() != 'bold') return false;

$hero_image_background = wp_get_attachment_image_src(get_post_thumbnail_id() , 'full', true)[0];
$blog_type_theme_options = $mk_options['single_blog_style'];
$blog_type = get_post_meta($post->ID, '_single_blog_style', true);

if ( $blog_type == '' || $blog_type == 'default' ) {
	$blog_style = $blog_type_theme_options;
}else {
	$blog_style = $blog_type;
}
?>

<div class="mk-blog-hero <?php echo $blog_style; ?>-style js-el" style="background-image:url(<?php echo $hero_image_background; ?>)" data-mk-component="FullHeight">
	<div class="content-holder">
		<h1 class="the-title">
			<?php the_title(); ?>
		</h1>
		<div class="mk-author-avatar">
			<?php global $user; echo get_avatar( get_the_author_meta('email'), '75',false ,get_the_author_meta('display_name', $user['ID'])); ?>
		</div>
		<div class="mk-author-name">
			<?php echo __('By', 'mk_framework'); ?>
			<a class="mk-author-name" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>">
				 <?php the_author_meta('display_name'); ?>
			</a>	
		</div>
		
		<time class="mk-publish-date" datetime="<?php the_date('Y-m-d') ?>">
			<a href="<?php echo get_month_link( get_the_time( "Y" ), get_the_time( "m" ) ); ?>"><?php echo get_the_date(); ?></a>
		</time>
	</div>
</div>