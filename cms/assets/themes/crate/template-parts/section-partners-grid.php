<?php
/**
 * The template for displaying Partners sections.
 */
?>

	<div class="content-section section-partners-grid"<?php crate_section_id_attr(); ?>>
<?php $link_url = get_field('link_url')?>

		<?php

				if(get_sub_field('background') == "silver")
				{
					echo '<div class="partners-header prose">';
				}
				else
				{
					echo '<div class="prose">';
				}?>

		<?php if ( $title = get_sub_field( 'title' ) ): ?>
			<h2 class="section-title"><?php echo wp_kses_post( wptexturize( $title ) ); ?></h2>
		<?php endif; ?>

		<?php if ( $subtitle = get_sub_field( 'subtitle' ) ): ?>
			<center><p><?php echo wp_kses_post( wptexturize( $subtitle ) ); ?></p></center>
		<?php endif; ?>
	</div>
		<?php

		$show_pager = get_sub_field( 'show_pager' );
		$show_facet = get_sub_field( 'filtering' );

		// Set up custom query.
		$partner_query = crate_section_query( array(
			'post_type' => 'partner',
			'orderby' => 'post_title',
			'order' => 'ASC',
		) );

		?>
		<?php if (get_sub_field('filtering') == "yes"): ?>
			<div class="section-facets section-facets-basic">
				<?php echo facetwp_display( 'facet', 'topics' ); ?>
				<?php echo facetwp_display( 'facet', 'locatio' ); ?>
				<?php echo facetwp_display( 'facet', 'search' ); ?>
			</div>
		<?php endif; ?>
		<div class="content-section-grid container<?php echo ( $show_pager || $show_facet ? ' facetwp-template' : '' ); ?>">

			<?php while ( $partner_query->have_posts() ) : $partner_query->the_post(); ?>


				<article class="partners-grid-item">

					<?php $is_featured = has_term( 'featured-partners', 'topic' ); ?>

					<div class="logo-container">
						<a href="<?php echo get_field( 'link_url' ); ?>" target="_blank"<?php if ( $is_featured ) echo ' class="modal-trigger"'; ?> data-org-name="<?php echo trim( esc_attr( get_the_title() ) ); ?>">
						<img src="<?php the_post_thumbnail_url(); ?>" class="partner-logo">
						</a>

					</div>

					<div>

						<h3 class="partner-title">
						<?php if (get_sub_field('button') == "hide") {

							echo '<a href="'.get_field( 'link_url' ).'"' . (  $is_featured ? ' class="modal-trigger"' : '' ) . ' target="_blank" >'.get_the_title().'</a>';
						}
						else
						{} ?>

						</h3>


						<?php

if(get_sub_field('description') == "show")
{
	echo '<br><p style="padding-top: 10px">'.the_excerpt().'</p>';
}

?>

						<?php

if(get_sub_field('button') == "show")
{
	echo '<center><a class="button button-solid button-gold' . ( $is_featured ? ' modal-trigger' : '' ) . '" href="' . get_field( 'link_url' ) . '" data-org-name="' . trim( esc_attr( get_the_title() ) ) . '">Get Involved</a></center>';
}
else
{

}
?>





					</div>






				</article>

			<?php endwhile; ?>
		</div>

		<?php if ( $show_pager ) :
			echo facetwp_display( 'pager' );
		endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php crate_section_links(); ?>

	</div>

<?php get_template_part( 'template-parts/modal', 'opportunity' ); ?>
