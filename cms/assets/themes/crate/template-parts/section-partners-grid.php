<?php
/**
 * The template for displaying Partners sections.
 */
?>

	<div class="content-section section-stories-grid">
    <div class="partners-header prose">
		<?php if ( get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>

		<?php if ( $subtitle = get_sub_field( 'subtitle' ) ): ?>
			<center><p><?php echo wp_kses_post( wptexturize( $subtitle ) ); ?></p></center>
		<?php endif; ?>
	</div>
		<?php

		$show_pager = get_sub_field( 'show_pager' );

		// Set up custom query.
		$story_query = crate_section_query( array(
			'post_type' => 'partner',
		) );

		?>

		<div class="content-section-grid container<?php echo ( $show_pager ? ' facetwp-template' : '' ); ?>">
			<?php while ( $story_query->have_posts() ) : $story_query->the_post(); ?>

				<article class="partners-grid-item grid-item">

					<div class="logo-container">

						<img src="<?php the_post_thumbnail_url(); ?>" class="partner-logo">


					</div>

					<div>

						<h3 class="partner-title">
							<a href="<?php echo esc_url( get_field( 'link_url' ) ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
						</h3>


						<?php

if(get_sub_field('description') == "show")
{
	echo '<br><p style="padding-top: 10px">'.the_excerpt().'</p>';
}

?>

				        

					</div>

					<a href="<?php echo esc_url( get_field( 'link_url' ) ); ?>" target="_blank" class="overlay-link"></a>



				</article>

			<?php endwhile; ?>
		</div>

		<?php if ( $show_pager ) :
			echo facetwp_display( 'pager' );
		endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php crate_section_links(); ?>

	</div>
