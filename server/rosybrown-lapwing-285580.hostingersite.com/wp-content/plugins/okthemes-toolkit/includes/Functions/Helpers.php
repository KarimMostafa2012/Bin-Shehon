<?php
namespace OKThemes\Toolkit\Functions;

if (!defined('ABSPATH')) exit;

class Helpers {
    public static function init() {
        add_filter('csf_customize_complex_fields', [__CLASS__, 'add_number_to_complex_fields']);
        add_filter('wp_check_filetype_and_ext', [__CLASS__, 'check_svg_upload'], 10, 4);
        add_action('ocdi/before_content_import', [__CLASS__, 'before_content_import']);
        add_action('ocdi/after_import', [__CLASS__, 'after_import_complete'], 999);

        // Re-add SVG mime filter on every request during multi-chunk AJAX import.
        // before_content_import only runs once; subsequent chunk requests need the filter too.
        if ( get_option('okthemes_svg_import_active') === 'yes' ) {
            add_filter('upload_mimes', [__CLASS__, 'import_svg']);
        }
    }

    public static function add_number_to_complex_fields($fields) {
        $fields[] = 'number';
        return $fields;
    }

    public static function get_theme_icon($icon_name) {
        $icon_function = strtolower(get_template()) . '_get_icons';
        return function_exists($icon_function) ? call_user_func($icon_function, $icon_name) : '';
    }

    public static function import_svg($mimes) {
        if (apply_filters('okthemes_enable_svg_support', true)) {
            $mimes['svg']   = 'image/svg+xml';
            $mimes['svgz']  = 'image/svg+xml';
            $mimes['woff']  = 'application/font-woff';
            $mimes['woff2'] = 'font/woff2';
            $mimes['ttf']   = 'application/x-font-ttf';
            $mimes['eot']   = 'application/vnd.ms-fontobject';
            $mimes['json']  = 'application/json'; // Lottie animations
        }
        return $mimes;
    }

    public static function check_svg_upload($data, $file, $filename, $mimes) {
        if (isset($data['ext']) && $data['ext'] === 'svg') {
            if (isset($data['type']) && $data['type'] === 'image/svg+xml') {
                $content = file_get_contents($file);
                if (strpos($content, '<?xml') === false && strpos($content, '<svg') === false) {
                    $data['ext'] = '';
                    $data['type'] = '';
                }
            }
        }
        return $data;
    }

    public static function before_content_import() {
        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
        update_option('elementor_global_image_lightbox', 'no');
        update_option('elementor_load_fa4_shim', 'yes');
        update_option('elementor_unfiltered_files_upload', true);

        add_filter('upload_mimes', [__CLASS__, 'import_svg']);
        update_option('okthemes_svg_import_active', 'yes');

        if (function_exists('torac_is_wc_activated') && torac_is_wc_activated()) {
            $shop_page_id = wc_get_page_id('shop');
            if ($shop_page_id > 0) {
                wp_delete_post($shop_page_id, true);
            }
        }
    }

    public static function after_import_complete() {
        if (get_option('okthemes_svg_import_active') === 'yes') {
            remove_filter('upload_mimes', [__CLASS__, 'import_svg']);
            delete_option('okthemes_svg_import_active');
        }
        update_option('elementor_unfiltered_files_upload', false);
    }
}
