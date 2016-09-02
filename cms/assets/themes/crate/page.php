<?php
/**
 * The basic Page template.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#common-wordpress-template-files
 *
 * @package Crate
 */

?><?php get_header() ?>

<?php

$images = get_field('gallery');

if( $images ): ?>
  <ul class="gallery">
    <?php foreach( $images as $image ): ?>
      <li>
        <a href="<?php echo $image['url']; ?>">
          <img src="<?php echo $image['sizes']['gallery']; ?>" alt="<?php echo $image['alt']; ?>" />
        </a>
        <p><?php echo $image['caption']; ?></p>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php get_footer() ?>