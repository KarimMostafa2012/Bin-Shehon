<?php
namespace OKThemes\Toolkit\TemplateBuilder\Core;

defined('ABSPATH') || exit;

/**
 * Font Manager
 *
 * Optimizes Google Fonts loading for Elementor templates
 */
class Font_Manager {

    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('elementor/frontend/print_google_fonts', [$this, 'optimize_google_fonts'], 999);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_template_fonts'], 20);
    }

    public function optimize_google_fonts($fonts) {
        if (isset($_GET['elementor-preview'])) {
            return $fonts;
        }

        if (!empty($fonts)) {
            set_transient('okthemes_template_fonts', $fonts, WEEK_IN_SECONDS);
        }

        return [];
    }

    public function enqueue_template_fonts() {
        if (is_admin() || isset($_GET['elementor-preview'])) {
            return;
        }

        $template_ids = $this->get_current_template_ids();
        if (empty($template_ids)) {
            return;
        }

        $all_fonts = get_transient('okthemes_template_fonts');
        if (empty($all_fonts) || !is_array($all_fonts)) {
            return;
        }

        $fonts_to_load = $this->get_fonts_for_templates($template_ids, $all_fonts);
        if (empty($fonts_to_load)) {
            return;
        }

        $this->enqueue_optimized_fonts($fonts_to_load);
    }

    private function get_current_template_ids() {
        $template_ids = [];

        // This class must be loaded before
        $template_frontend = \OKThemes\Toolkit\TemplateBuilder\Frontend::instance();
        $header_id = $template_frontend->get_template_id('header');
        if ($header_id) $template_ids[] = $header_id;

        $footer_id = $template_frontend->get_template_id('footer');
        if ($footer_id) $template_ids[] = $footer_id;

        $popup_id = $template_frontend->get_template_id('popup');
        if ($popup_id) $template_ids[] = $popup_id;

        global $post;
        if (is_object($post) && has_shortcode($post->post_content, 'okthemes-tb-block')) {
            preg_match_all('/\[okthemes-tb-block id="(\d+)"\]/', $post->post_content, $matches);
            if (!empty($matches[1])) {
                $template_ids = array_merge($template_ids, $matches[1]);
            }
        }

        return array_unique($template_ids);
    }

    private function get_fonts_for_templates($template_ids, $all_fonts) {
        if (empty($template_ids) || empty($all_fonts)) {
            return [];
        }

        $theme_fonts = [
            'Barlow' => ['300', '400', '500', '600', '700'],
        ];

        foreach ($template_ids as $template_id) {
            if (class_exists('\Elementor\Plugin')) {
                $document = \Elementor\Plugin::$instance->documents->get($template_id);
                if ($document) {
                    $data = $document->get_elements_data();
                    if (!empty($data)) {
                        $template_fonts = $this->extract_fonts_from_data($data);
                        foreach ($template_fonts as $font => $variants) {
                            if (isset($theme_fonts[$font])) {
                                $theme_fonts[$font] = array_unique(array_merge($theme_fonts[$font], $variants));
                            } else {
                                $theme_fonts[$font] = $variants;
                            }
                        }
                    }
                }
            }
        }

        $fonts_to_load = [];
        foreach ($theme_fonts as $font => $variants) {
            if (isset($all_fonts[$font])) {
                $fonts_to_load[$font] = array_intersect($variants, $all_fonts[$font]);
            }
        }

        return $fonts_to_load;
    }

    private function extract_fonts_from_data($elements) {
        $fonts = [];

        if (!is_array($elements)) return $fonts;

        foreach ($elements as $element) {
            if (!empty($element['settings'])) {
                foreach ($element['settings'] as $key => $value) {
                    if (is_array($value) && isset($value['font_family']) && !empty($value['font_family'])) {
                        $font = $value['font_family'];
                        $weight = isset($value['font_weight']) ? $value['font_weight'] : '400';

                        if (!isset($fonts[$font])) {
                            $fonts[$font] = [];
                        }

                        $fonts[$font][] = $weight;
                    }
                }
            }

            if (!empty($element['elements'])) {
                $child_fonts = $this->extract_fonts_from_data($element['elements']);
                foreach ($child_fonts as $font => $weights) {
                    if (!isset($fonts[$font])) {
                        $fonts[$font] = [];
                    }

                    $fonts[$font] = array_merge($fonts[$font], $weights);
                }
            }
        }

        foreach ($fonts as $font => $weights) {
            $fonts[$font] = array_unique($weights);
        }

        return $fonts;
    }

    private function enqueue_optimized_fonts($fonts) {
        if (empty($fonts)) return;

        $font_families = [];
        foreach ($fonts as $font => $variants) {
            if (empty($variants)) continue;

            $font_families[] = urlencode($font) . ':' . implode(',', $variants);
        }

        if (empty($font_families)) return;

        $query_args = [
            'family' => implode('|', $font_families),
            'display' => 'swap',
        ];

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        wp_enqueue_style('okthemes-google-fonts', $fonts_url, [], null);
    }
}

Font_Manager::instance();
