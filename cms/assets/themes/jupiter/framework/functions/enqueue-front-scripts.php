<?php


add_action('wp_enqueue_scripts', 'mk_enqueue_api_modules', 10);
add_action('wp_enqueue_scripts', 'mk_enqueue_scripts', 10);
add_action('wp_enqueue_scripts', 'mk_enqueue_styles', 10);
add_action('wp_enqueue_scripts', 'mk_enqueue_fonts', 10);


if (!function_exists('mk_enqueue_scripts')) {
	function mk_enqueue_scripts() {

		global $mk_options;
		$theme_data = wp_get_theme();
		$is_admin = !(!is_admin() && !( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') )));

	    if($is_admin) return;

		$is_js_min = ( !(defined('MK_DEV') ? constant("MK_DEV") : true) || $mk_options['minify-js'] == 'true' );
		$is_smoothscroll = ( $mk_options['smoothscroll'] == 'true' );
        
        // Register / Enqueue Plugins
        wp_register_script('jquery-raphael', THEME_JS . '/plugins/wp-enqueue'. ($is_js_min ? '/min' : '') .'/jquery.raphael.js', array('jquery') , $theme_data['Version'], false);
        wp_register_script('instafeed', THEME_JS . '/plugins/wp-enqueue'. ($is_js_min ? '/min' : '') .'/instafeed.js', array('jquery') , false, true);
        if ($is_smoothscroll) wp_enqueue_script('smoothscroll', THEME_JS . '/plugins/wp-enqueue'. ($is_js_min ? '/min' : '') .'/smoothscroll.js', array() , $theme_data['Version'], true);
        
        // Theme scripts
        if (is_singular()) wp_enqueue_script('comment-reply');
        wp_enqueue_script('theme-scripts', THEME_JS . ($is_js_min ? '/min' : '') .'/scripts.js', array('jquery') , $theme_data['Version'], true);
	}
}



if (!function_exists('mk_enqueue_styles')) {
	function mk_enqueue_styles() {

		global $mk_options;
		$theme_data = wp_get_theme();
		$is_admin = !(!is_admin() && !( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') )));

	    if($is_admin) return;
	        
		$is_css_min = ( !(defined('MK_DEV') ? constant("MK_DEV") : true) || $mk_options['minify-css'] == 'true' );

        remove_action('bbp_enqueue_scripts', 'enqueue_styles'); 

        // Register Plugins
		//  wp_enqueue_style('critical-path', THEME_STYLES  .'/min/critical-path.css', false, $theme_data['Version'], 'all');
        wp_enqueue_style('js-media-query', THEME_STYLES . ($is_css_min ? '/min' : '') .'/media.css', false, $theme_data['Version'], 'all');
        wp_enqueue_style('theme-styles', THEME_STYLES . ($is_css_min ? '/min' : '') .'/styles.css', false, $theme_data['Version'], 'all');
        wp_enqueue_style('theme-icons', THEME_STYLES . ($is_css_min ? '/min' : '') .'/theme-icons.css', false, $theme_data['Version'], 'all');
        
	}
}

/**
 * Register our module scripts early and later lazy load them when module is ininitialized
 * by including in its file wp_enqueue_script('module-name');
 */
if (!function_exists('mk_enqueue_api_modules')) {
	function mk_enqueue_api_modules() {
		wp_register_script( 'api-vimeo', 'http' . ((is_ssl())? 's' : '') . '://a.vimeocdn.com/js/froogaloop2.min.js', array(), false, false);
		wp_register_script( 'api-youtube', 'http' . ((is_ssl())? 's' : '') . '://www.youtube.com/player_api', array(), false, false);
	}
}

/**
 * Google fonts
 */
if (!function_exists('mk_enqueue_fonts')) {
	function mk_enqueue_fonts() {

		global $mk_options;
		$theme_data = wp_get_theme();
		$is_admin = !(!is_admin() && !( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') )));

	    if ($mk_options['special_fonts_type_1'] == 'google' && !empty($mk_options['special_fonts_list_1'])) {
            $subset_1 = !empty($mk_options['google_font_subset_1']) ? (':&subset=' . $mk_options['google_font_subset_1']) : '';
            wp_enqueue_style('google-font-api-special-1', 'http' . ((is_ssl()) ? 's' : '') . '://fonts.googleapis.com/css?family=' . $mk_options['special_fonts_list_1'] . ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900' . $subset_1, false, false, 'all');
        }
        
        if ($mk_options['special_fonts_type_2'] == 'google' && !empty($mk_options['special_fonts_list_2'])) {
            $subset_2 = !empty($mk_options['google_font_subset_2']) ? ('&subset=' . $mk_options['google_font_subset_2']) : '';
            wp_enqueue_style('google-font-api-special-2', 'http' . ((is_ssl()) ? 's' : '') . '://fonts.googleapis.com/css?family=' . $mk_options['special_fonts_list_2'] . ':100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic,100,200,300,400,500,600,700,800,900' . $subset_2, false, false, 'all');
        }
	}
}

/**
 * Adding font icons in HTML document to prevent issues when using CDN
 */
if (!function_exists('mk_enqueue_font_icons')) {
	function mk_enqueue_font_icons() {
	    
	    $styles_dir = THEME_DIR_URI . '/assets/stylesheet';
	    $output = "
			@font-face {
				font-family: 'Pe-icon-line';
				src:url('{$styles_dir}/icons/pe-line-icons/Pe-icon-line.eot?lqevop');
				src:url('{$styles_dir}/icons/pe-line-icons/Pe-icon-line.eot?#iefixlqevop') format('embedded-opentype'),
					url('{$styles_dir}/icons/pe-line-icons/Pe-icon-line.woff?lqevop') format('woff'),
					url('{$styles_dir}/icons/pe-line-icons/Pe-icon-line.ttf?lqevop') format('truetype'),
					url('{$styles_dir}/icons/pe-line-icons/Pe-icon-line.svg?lqevop#Pe-icon-line') format('svg');
				font-weight: normal;
				font-style: normal;
			}
			@font-face {
			  font-family: 'FontAwesome';
			  src:url('{$styles_dir}/icons/awesome-icons/fontawesome-webfont.eot?v=4.2');
			  src:url('{$styles_dir}/icons/awesome-icons/fontawesome-webfont.eot?#iefix&v=4.2') format('embedded-opentype'),
			  url('{$styles_dir}/icons/awesome-icons/fontawesome-webfont.woff?v=4.2') format('woff'),
			  url('{$styles_dir}/icons/awesome-icons/fontawesome-webfont.ttf?v=4.2') format('truetype');
			  font-weight: normal;
			  font-style: normal;
			}
			@font-face {
				font-family: 'Icomoon';
				src: url('{$styles_dir}/icons/icomoon/fonts-icomoon.eot');
				src: url('{$styles_dir}/icons/icomoon/fonts-icomoon.eot?#iefix') format('embedded-opentype'), 
				url('{$styles_dir}/icons/icomoon/fonts-icomoon.woff') format('woff'), 
				url('{$styles_dir}/icons/icomoon/fonts-icomoon.ttf') format('truetype'), 
				url('{$styles_dir}/icons/icomoon/fonts-icomoon.svg#Icomoon') format('svg');
				font-weight: normal;
				font-style: normal;
			} 
			@font-face {
			  font-family: 'themeIcons';
			  src: url('{$styles_dir}/icons/theme-icons/theme-icons.eot?wsvj4f');
			  src: url('{$styles_dir}/icons/theme-icons/theme-icons.eot?#iefixwsvj4f') format('embedded-opentype'), 
			  url('{$styles_dir}/icons/theme-icons/theme-icons.woff?wsvj4f') format('woff'), 
			  url('{$styles_dir}/icons/theme-icons/theme-icons.ttf?wsvj4f') format('truetype'), 
			  url('{$styles_dir}/icons/theme-icons/theme-icons.svg?wsvj4f#icomoon') format('svg');
			  font-weight: normal;
			  font-style: normal;
			}

			@font-face {
				font-family: 'star';
				src: url('{$styles_dir}/icons/woocommerce/star.eot');
				src: url('{$styles_dir}/icons/woocommerce/star.eot?#iefix') format('embedded-opentype'), 
				url('{$styles_dir}/icons/woocommerce/star.woff') format('woff'), 
				url('{$styles_dir}/icons/woocommerce/star.ttf') format('truetype'), 
				url('{$styles_dir}/icons/woocommerce/star.svg#star') format('svg');
				font-weight: normal;
				font-style: normal;
			}
			@font-face {
				font-family: 'WooCommerce';
				src: url('{$styles_dir}/icons/woocommerce/WooCommerce.eot');
				src: url('{$styles_dir}/icons/woocommerce/WooCommerce.eot?#iefix') format('embedded-opentype'), 
				url('{$styles_dir}/icons/woocommerce/WooCommerce.woff') format('woff'), 
				url('{$styles_dir}/icons/woocommerce/WooCommerce.ttf') format('truetype'), 
				url('{$styles_dir}/icons/woocommerce/WooCommerce.svg#WooCommerce') format('svg');
				font-weight: normal;
				font-style: normal;
			}";
			return $output;
	}
}





/**
 * Adds media.css into DOM
 * @author      Bob ULUSOY & Ugur Mirza ZEYREK
 * @copyright   Artbees LTD (c)
 * @link        http://artbees.net
 * @since       Version 5.0
 * @last_update Version 5.0.5
 */
/*if (!function_exists('mk_output_media_css')) {
    function mk_output_media_css() {
        $wp_remote_get_file_body = '';
        $base_dir = mk_base_url();

        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        if (is_numeric(strpos(THEME_STYLES, $base_dir))) {
            $styles_uri = THEME_STYLES;
        } 
        else {
            $styles_uri = $base_dir . THEME_STYLES;
        }

        $theme_dir = get_template_directory();
        $styles_dir = $theme_dir.THEME_STYLES_SUFFIX;
        $wp_remote_get_file_dir = $styles_dir."/min/media.css";

        $wp_remote_get_file_body = $wp_filesystem->get_contents($wp_remote_get_file_dir);

        if($wp_remote_get_file_body == false) {
            $wp_remote_get_file = wp_remote_get($styles_uri . '/min/media.css');
            if (!is_wp_error($wp_remote_get_file) and is_array($wp_remote_get_file) and array_key_exists('body', $wp_remote_get_file)) {

                $wp_remote_get_file_body = $wp_remote_get_file['body'];
            }
            else if (is_numeric(strpos($styles_uri, "https://"))) {

                $styles_uri = str_replace("https://", "http://", $styles_uri);
                $wp_remote_get_file = wp_remote_get($styles_uri . '/min/media.css');

                if (!is_array($wp_remote_get_file) or !array_key_exists('body', $wp_remote_get_file)) {
                    echo "SSL connection error. Code: ds-ConcatenateAssetsByExtension";
                    die;
                }

                $wp_remote_get_file_body = $wp_remote_get_file['body'];
            }
        }
        
        $output = '<style type="text/css" id="js-media-query">';
        $output.= $wp_remote_get_file_body;
        $output.= '</style>';
        
        echo $output;
    }
}

*/

