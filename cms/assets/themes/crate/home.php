<?php
/*
Template Name: Blog
*/
?>

<?php
	get_header(); ?>
	<header class="entry-header hero">
		<?php if ( has_post_thumbnail() ) : ?>

			<picture class="hero-image">
				<?php
				list( $src ) = wp_get_attachment_image_src( get_post_thumbnail_id(), 'hero-lg' );
				if ( $src ) : ?>
					<source media="(min-width: 640px)" srcset="<?php echo esc_url( $src ); ?>">
				<?php endif; ?>
				<?php the_post_thumbnail( 'hero-sm' ); ?>
			</picture>

		<?php endif; ?>
		<div class="hero-text prose prose-compact container-10 container-flex">
			<?php single_post_title( '<h1 class="entry-title">', '</h1>' ); ?>
	
		</div>
	</header><!-- .entry-header -->
	<main>
	
<div class="content-section"<?php crate_section_id_attr(); ?>>
		<div class="content-section-grid container making-the-case facetwp-template" style="padding-left: 0px; padding-right: 0px;">
			
			
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div class="grid-item-2 mtc-grid-item">
			<a class="remove-underline" target="_blank" href="<?php the_permalink() ?>">
				<h3 class="grid-item-name">
				<?php the_title(); ?></h3>
				<span class="grid-item-title"><?php echo get_the_date() ?></span>
			</a>
		</div>
		
 <?php endwhile; 
 wp_reset_postdata();
 else : ?>
 <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
 <?php endif; ?>
</div>
</div>
	</main>
<?php
get_footer();

