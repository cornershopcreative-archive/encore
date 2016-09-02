<?php
global $mk_options;

if ( have_posts() ) while ( have_posts() ) : the_post();


$terms = get_the_terms(get_the_id(), 'news_category');
$terms_slug = array();
$terms_name = array();
if (is_array($terms)) {
	foreach($terms as $term) {
		$terms_name[] = $term->name;
			}
}

?>
<div class="news-post-heading">
    <ul class="news-single-social">
        <li><a onClick="window.print()" href="#"><?php _e('Print', 'mk_framework'); ?></a></li>
        <li><a href="mailto:info@company.com?subject=<?php the_title(); ?>&body=<?php the_excerpt(); ?>"><?php _e('Email', 'mk_framework'); ?></a></li>
    </ul>
    <div class="single-news-meta">
        <div class="news-single-categories"><?php echo implode(', ', $terms_name); ?></div>
        <time class="news-single-date" datetime="<?php the_date('Y-m-d') ?>">
            <a href="<?php echo get_month_link( get_the_time( "Y" ), get_the_time( "m" ) ) ?>"><?php echo get_the_date() ?></a>
        </time>
    </div>
    <div class="clearboth"></div>
</div>

<?php mk_get_view('global', 'featured-image', false, ['post_type'=> 'news', 'width' => mk_count_content_width(), 'height' => $mk_options['news_featured_image_height']]); ?>
<div class="news-post-content" itemprop="mainContentOfPage">
    <?php the_content();?>
</div>
<div class="mk-back-top">
    <a href="#top-of-page" class="mk-back-top-link"><i class="mk-icon-arrow-up"></i><?php _e('Back to Top', 'mk_framework'); ?></a>
</div>
<?php endwhile; ?>