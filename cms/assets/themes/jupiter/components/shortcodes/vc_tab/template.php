<div id="<?php echo $tab_id ?>" class="mk-tabs-pane">

	<div class="title-mobile">
		<?php if(isset($icon)) { ?>
			<i class="<?php echo $icon ?>"></i>
		<?php } ?>
		<?php echo $title ?>
	</div>

	<?php echo wpb_js_remove_wpautop( $content ) ?>

	<div class="clearboth"></div>
</div>