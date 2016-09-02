<?php
if (!defined('THEME_FRAMEWORK')) exit('No direct script access allowed');

/**
 * Function to generate the custom WP_Query
 *
 * @author      Bob Ulusoy
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @version     5.0
 * @package     artbees
 */

if (!function_exists('mk_wp_query')) {
    function mk_wp_query($atts) {
        
        extract($atts);
        
        $count = isset($count) ? $count : 10;

        $query = array(
            'post_type' => $post_type,
            'posts_per_page' => (int)$count,
            'suppress_filters' => 0,
            'post_status' => 'publish'
        );
        
        if ($post_type == 'attachment') {
            $query['post_mime_type'] = 'image';
            $query['post_status'] = 'inherit';
        }
        
        if (isset($cat) && !empty($cat) && $post_type == 'post') {
            $query['cat'] = $cat;
        }
        
        if (isset($categories) && !empty($categories) && $post_type != 'post') {
            $query['tax_query'] = array(
                array(
                    'taxonomy' => $post_type . '_category',
                    'field' => 'slug',
                    'terms' => explode(',', $categories)
                )
            );
        }
        
        if (isset($author) && !empty($author)) {
            $query['author'] = $author;
        }
        if (isset($posts) && !empty($posts)) {
            $query['post__in'] = explode(',', $posts);
        }
        if (isset($orderby) && !empty($orderby)) {
            $query['orderby'] = $orderby;
        }
        if (isset($order) && !empty($order)) {
            $query['order'] = $order;
        }
        if(isset($paged) && !empty($paged)) {
            $query['paged'] = $paged;    
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
            $query['paged'] = $paged;
            if ($paged == 1) {
                if (isset($offset) && !empty($offset)) {
                    $query['offset'] = $offset;
                }
            } 
            else {
                if (isset($offset) && !empty($offset)) {
                    $offset = $offset + (($paged - 1) * $count);
                    $query['offset'] = $offset;
                }
            }
        }    
        
        return array(
            'wp_query' => new WP_Query($query) ,
            'paged' => $paged
        );
    }
}
