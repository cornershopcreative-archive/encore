<?php
/**
 * The template for displaying dropdown sections.
 */

?>
<div class="content-section section-dropdown"<?php crate_section_id_attr(); ?>>

	<div class="section-dropdown-menu-container">
		<?php /* TODO: make this text customizable */ ?>
		<span class="section-dropdown-label"><?php the_sub_field( 'dropdown_label' ); ?></span>

		<?php
		// We'll iterate over the dropdown-options field twice.
		// First, output the dropdown menu.
		?>
		<div class="section-dropdown-menu">
			<?php
			$options = get_sub_field( 'dropdown-options', $post->ID, false );
			$first_label = ( $options ?
				$options[0]['label'] :
				''
			);
			?>
			<span aria-role="presentation" class="current-option"><?php echo esc_html( $first_label ); ?></span>
			<ul class="section-dropdown-options">
				<?php while ( have_rows( 'dropdown-options' ) ) : the_row(); ?>
					<li><a class="option" href="#<?php echo crate_get_section_id() . '-' . esc_attr( sanitize_title( get_sub_field( 'label' ) ) ); ?>"><?php the_sub_field( 'label' ); ?></a></li>
				<?php endwhile; ?>
			</ul>
		</div>
	</div>

	<?php
	// Then output the sections for each dropdown option.
	?>
	<div class="section-dropdown-sets">
		<?php $i = 0; ?>
		<?php while ( have_rows( 'dropdown-options' ) ) : the_row(); ?>

			<div class="section-dropdown-set<?php echo ( 0 === $i ? ' is-active' : '' ); ?>" id="<?php echo crate_get_section_id() . '-' . esc_attr( sanitize_title( get_sub_field( 'label' ) ) ); ?>">
				
				<h2 class="screen-reader-text"><?php the_sub_field( 'label' ); ?></h2>

				<?php if ( have_rows( 'section_items' ) ) :
					while ( have_rows( 'section_items' ) ) : the_row();
						get_template_part( 'template-parts/section', get_row_layout() );
					endwhile;
				endif; ?>

			</div>

			<?php $i += 1; ?>
		<?php endwhile; ?>
	</div>
</div>
