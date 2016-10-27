<?php
/**
 * The template for displaying Callout sections.
 */
?>

		<?php
				$callout_type = get_sub_field('callout_type');
				$callout_title = get_sub_field( 'title' );
				$callout_title_two = get_sub_field( 'title_two' );
				$callout_text = get_sub_field('text');
				$callout_text_two = get_sub_field('text_two');
				$callout_color = get_sub_field('color');
				$callout_color_two = get_sub_field('color_two');
				$callout_button_url = get_sub_field('button_url');
				$callout_button_label = get_sub_field('button_label');
				$callout_button_url_two = get_sub_field('button_url_two');
				$callout_button_label_two = get_sub_field('button_label_two');
				$callout_image = get_sub_field('circle_image');
				$callout_caption = get_sub_field('image_caption');
				$callout_background_image = get_sub_field('background_image');
		?>

<?php echo '<div class="section-callout '.$callout_color .' ">' ;?>

	<?php echo '<div class="callout-content '.$callout_background_image .'  '.$callout_type .'">' ;?>

		<?php if (empty($callout_image)) {  }
			 else { echo '<img class="callout_circle" src="' . $callout_image . '"> ' ;} ?>

		<?php if (empty($callout_caption)) {  }
			 	else { echo $callout_caption ;} ?>


		<h3 class="callout-heading"> <?php echo $callout_title ?> </h3>
	 
		<?php echo $callout_text ?> 
			

			
		<?php if (empty($callout_button_label)) {  }
			 else { echo '<a style="margin-top: 20px" class="button button-white" href="' . $callout_button_url . '"> ' . $callout_button_label . '</a>' ;} ?>
</div>
			 <?php if (empty($callout_title_two)) {  }
	else  echo '<div class="second_column '.$callout_color_two .'">' ;?> 

				<h3 class="callout-heading"> <?php echo $callout_title_two ?> </h3>
				<?php echo $callout_text_two ?>

				<?php if (empty($callout_button_label_two)) {  }
				 else { echo '<a style="margin-top: 20px" class="button button-white" href="' . $callout_button_url_two . '"> ' . $callout_button_label_two . '</a>' ;} ?>
	</div>
	</div>		
	
	</div>	
