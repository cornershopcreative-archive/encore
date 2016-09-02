<?php

$maxPages = $view_params['r']->max_num_pages;
// No need to load pagination if all posts are shown and no more posts to load.
if($maxPages <= 1) return false;

// Classic Pagination
if ($view_params['pagination_style'] == 1) {
		?>
		<div class="mk-pagination mk-grid js-el" data-number-pages="8" data-max-pages="<?php echo $maxPages; ?>" data-mk-component="Pagination">
			<a href="#" class="mk-pagination-previous is-vis-hidden js-pagination-prev"></a>

				<?php for($i = 0; $i < $maxPages && $i < 10; $i++) { ?>
					<a class="page-number js-pagination-page <?php if($i == 0) echo 'current-page'; ?>" href="#" data-page-id="<?php echo $i + 1; ?>">
						<?php 
							if($i == 9 && $maxPages > 10) echo '...';
							else echo $i + 1; 
						?>
					</a>
				<?php } ?>

			<a href="#" class="mk-pagination-next js-pagination-next"></a>

			<div class="mk-total-pages">
				<?php _e('page', 'mk_framework'); ?>
				<span class="pagination-current-page js-current-page">1</span>
				<?php _e('of', 'mk_framework'); ?>
				<span class="pagination-max-pages"><?php echo $maxPages; ?></span>
			</div>
		</div>

		<?php 
} 
?>

<?php
// Pagination with load more button
if ($view_params['pagination_style'] == 2) { ?>
	<a class="mk-loadmore-button js-loadmore-button clear" href="javascript:;">
		<i class="mk-moon-loop-4"></i>
		<i class="mk-moon-arrow-down-4"></i>
		<?php _e('Load More', 'mk_framework'); ?>
	</a>
<?php } ?>    

