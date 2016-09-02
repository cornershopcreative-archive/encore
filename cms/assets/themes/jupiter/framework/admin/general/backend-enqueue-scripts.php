<?php

// TODO move variables declaration outsice. maybe include with file? for the moment it doesnt work when defined globally outside of functions

if ((mk_theme_is_masterkey()) || mk_theme_is_post_type() || mk_is_control_panel()) {
     add_action('admin_init', 'theme_admin_add_script');
     add_action('admin_init', 'theme_admin_add_style');
     add_action('admin_head', 'add_script_to_head');
}

if (mk_theme_is_masterkey()) add_action('admin_init', 'mk_masterkey_specific_enqueue');
if (mk_theme_is_widgets())  add_action('admin_init', 'mk_add_widgets_scripts');
if (mk_theme_is_icon_library()) add_action('admin_init', 'mk_enqueue_icon_lib');


function theme_admin_add_script() {
     global $mk_options;
     $theme_data = wp_get_theme("Jupiter");
     $is_js_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     $is_css_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);

     wp_enqueue_script('jquery-ui-tabs');
     wp_enqueue_script('jquery-ui-slider');
     wp_enqueue_script('wp-color-picker');
     wp_enqueue_script('attrchange', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/attrchange.js', array('jquery'), $theme_data['Version'], true);
     wp_enqueue_script('mk-options-dependency', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/options-dependency.js', array('jquery'), $theme_data['Version'], true);
     wp_enqueue_script('mk-select2', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/select2.js', array('jquery'), $theme_data['Version'], true);
     wp_enqueue_script('progress-circle', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/progress-circle.js', array('jquery' ), false, true);
     wp_enqueue_script('admin-scripts', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/backend-scripts.js', array('jquery'), $theme_data['Version'], true);
}


function theme_admin_add_style() {
     global $mk_options;
     $theme_data = wp_get_theme("Jupiter");
     $is_js_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     $is_css_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     wp_enqueue_style('wp-color-picker');
     wp_enqueue_style('theme-style', THEME_ADMIN_ASSETS_URI . '/stylesheet/css'. ($is_css_min ? '/min' : '') .'/theme-backend-styles.css');
     wp_enqueue_style('mk-select2', THEME_ADMIN_ASSETS_URI . '/stylesheet/css'. ($is_css_min ? '/min' : '') .'/select2.css');
}


function mk_masterkey_specific_enqueue() {
     global $mk_options;
     $theme_data = wp_get_theme("Jupiter");
     $is_js_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     $is_css_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     wp_enqueue_style('mk-theme-icons', THEME_STYLES . ($is_css_min ? '/min' : '') .'/theme-icons.css', false, false, 'all');
     if (function_exists('wp_enqueue_media')) wp_enqueue_media();
}


function add_script_to_head() {
     echo '<script type="text/javascript">
     		var mk_theme_admin_uri = "' . THEME_ADMIN_URI . '";
     		var mk_theme_imges = "' . THEME_IMAGES . '";
     	 </script>';
}


function mk_add_widgets_scripts() {
     global $mk_options;
     $theme_data = wp_get_theme("Jupiter");
     $is_js_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     $is_css_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     wp_enqueue_script('wp-color-picker');
     wp_enqueue_script('widget-scripts', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/widgets.js', array('jquery'), $theme_data['Version'], true);
     wp_enqueue_script('mk-select2', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/select2.js', array('jquery'), $theme_data['Version'], true);

     wp_enqueue_style('wp-color-picker');
     wp_enqueue_style('theme-style', THEME_ADMIN_ASSETS_URI . '/stylesheet/css'. ($is_css_min ? '/min' : '') .'/widgets.css');
     wp_enqueue_style('mk-select2', THEME_ADMIN_ASSETS_URI . '/stylesheet/css'. ($is_css_min ? '/min' : '') .'/select2.css');
}


function mk_enqueue_icon_lib() {
     global $mk_options;
     $theme_data = wp_get_theme("Jupiter");
     $is_js_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     $is_css_min = !(defined('MK_DEV') ? constant("MK_DEV") : true);
     wp_enqueue_style('mk-icon-libs', THEME_ADMIN_ASSETS_URI . '/stylesheet/css'. ($is_css_min ? '/min' : '') .'/icon-library.css', false, $theme_data['Version'], 'all');
     wp_enqueue_script('icon-libs-filter', THEME_ADMIN_ASSETS_URI . '/js'. ($is_js_min ? '/min' : '') .'/icon-libs-filter.js', array('jquery'), $theme_data['Version'], true);
     wp_enqueue_style('theme-icons', THEME_STYLES . '/theme-icons.css', false, $theme_data['Version'], 'all');
}
