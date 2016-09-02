<?php

/**
 * Define any shortcodes unique to this site
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

/**
 * Implements the 'get_posts' shortcode
 */
function crate_get_posts($args = '') {

	$r = shortcode_atts(
		array(
			'numberposts'			=> '10',
			'offset'					 => '',
			'category'				 => '',
			'category_name'		=> '',
			'tag'							=> '',
			'orderby'					=> 'date',
			'order'						=> '',
			'include'					=> '',
			'exclude'					=> '',
			'meta_key'				 => '',
			'meta_value'			 => '',
			'post_type'				=> '',
			'post_status'			=> '',
			'post_parent'			=> '',
			'nopaging'				 => '',
			'ul_class'				 => 'get-posts',
			'fields'					 => 'post_title',
			'fields_classes'	 => 'post-title',
			'fields_make_link' => 'true'),
		$args );

	$fields_list = explode(",", $r['fields']);
	$fields_classes_list = explode(",", $r['fields_classes']);
	$fields_make_link_list = explode(",", $r['fields_make_link']);

	$content = "\n\n<ul class=\"" . $r['ul_class'] . "\">\n";

	$posts = get_posts($args);
	foreach( $posts as $post ) {

		$content .= "	<li>";
		$i = 0;

		foreach ( $fields_list as $field ) {

			if (isset($fields_classes_list[$i])) {
				$content .= '<span class="' . trim($fields_classes_list[$i]) . '">';
			}

			if (isset($fields_make_link_list[$i]) &&
				($fields_make_link_list[$i] == "true" ||
				$fields_make_link_list[$i] == 1)) {
				$content .= '<a href="' . get_permalink($post->ID) . '">';
			}

			$field = trim($field);
			$content = $content . $post->$field;

			if (isset($fields_make_link_list[$i]) &&
				($fields_make_link_list[$i] == "true" ||
				$fields_make_link_list[$i] == 1)) {
				$content .= "</a>";
			}

			if (isset($fields_classes_list[$i])) {
				$content .= "</span>";
			}

			$i++;
		}

		$content .= "</li>\n";
	}

	$content .= '</ul>';

	return $content;
}

add_shortcode('get_posts', 'crate_get_posts');