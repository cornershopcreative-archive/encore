<?php

/**
 * Define widget regions
 * Put any custom-built widgets here
 */

if( !defined('ABSPATH') ) { die('Direct access not allowed'); }

/**
 * Register widgetized areas...
 */
function crate_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'crate' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'crate' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'crate' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'crate' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'crate' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'crate' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'crate' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'crate' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running crate_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'crate_widgets_init' );


function crate_custom_widgets() {
  //register_widget('sample_widget');
}
add_action('widgets_init', 'crate_custom_widgets');


/**
 * Custom Widget example
 */
class sample_widget extends WP_Widget {

  function __construct() {
    $widget_ops = array( 'classname' => 'sample-widget', 'description' => 'List of schools' );
    $control_ops = array( 'width' => 300, 'height' => 250, 'id_base' => 'sample-widget' );
    parent::__construct( 'sample-widget', 'Sample Custom Widget', $widget_ops, $control_ops );
  }

  function widget($args, $instance) {
    extract($args);
    echo $before_widget;

		if (!empty($instance['title'])) echo $before_title . $instance['title'] . $after_title;

    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    /* Strip tags (if needed) and update the widget settings. */
    $instance['title'] = strip_tags( $new_instance['title'] );
    return $instance;
  }

  function form($instance) { ?>
    <p>
     <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title", 'crate'); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
  <?php
  }
}