<?php
namespace OKThemes\Toolkit\TemplateBuilder\Core;

defined('ABSPATH') || exit;

/**
 * CSS Optimizer
 *
 * Optimizes Elementor CSS loading
 */
class CSS_Optimizer {

    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('elementor/frontend/print_styles', [$this, 'optimize_elementor_css'], 999);
        add_action('template_redirect', [$this, 'disable_elementor_features']);

        // Clear transients on template update
        add_action('save_post_okthemes_template', function () {
            delete_transient('okthemes_template_widgets');
            delete_transient('okthemes_template_animations');
        });
    }

    public function optimize_elementor_css($print_styles) {
        if (is_admin() || isset($_GET['elementor-preview'])) {
            return $print_styles;
        }

        add_filter('elementor/frontend/print_widget_styles', function($widget_styles) {
            $used_widgets = $this->get_widgets_used_in_templates();
            if (empty($used_widgets)) return [];

            $filtered_styles = [];
            foreach ($widget_styles as $style) {
                $widget_name = str_replace('elementor-widget-', '', $style);
                if (in_array($widget_name, $used_widgets)) {
                    $filtered_styles[] = $style;
                }
            }
            return $filtered_styles;
        });

        return $print_styles;
    }

    public function disable_elementor_features() {
        if (is_admin() || isset($_GET['elementor-preview'])) {
            return;
        }

        if (!$this->templates_use_animations()) {
            add_filter('elementor/frontend/print_animations', '__return_false');
        }

        add_filter('elementor/frontend/should_enqueue_frontend_resource', [$this, 'should_load_resource'], 10, 2);
    }

    public function should_load_resource($load, $resource_name) {
        if (is_admin() || isset($_GET['elementor-preview'])) {
            return $load;
        }

        $conditional_resources = [
            'dialog', 'swiper', 'share-link', 'accordion', 'tabs', 'toggle', 'video', 'image-carousel', 'text-editor'
        ];

        if (in_array($resource_name, $conditional_resources)) {
            return $this->is_resource_used_in_templates($resource_name);
        }

        return $load;
    }

    private function get_widgets_used_in_templates() {
        $used_widgets = get_transient('okthemes_template_widgets');
        if (false !== $used_widgets) return $used_widgets;

        $used_widgets = [];
        $templates = get_posts([
            'post_type' => 'okthemes_template',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        foreach ($templates as $template) {
            if (class_exists('Elementor\\Plugin')) {
                $document = \Elementor\Plugin::$instance->documents->get($template->ID);
                if ($document) {
                    $data = $document->get_elements_data();
                    if (!empty($data)) {
                        $widgets = $this->extract_widgets_from_data($data);
                        $used_widgets = array_merge($used_widgets, $widgets);
                    }
                }
            }
        }

        $used_widgets = array_unique($used_widgets);
        set_transient('okthemes_template_widgets', $used_widgets, WEEK_IN_SECONDS);
        return $used_widgets;
    }

    private function extract_widgets_from_data($elements) {
        $widgets = [];
        if (!is_array($elements)) return $widgets;

        foreach ($elements as $element) {
            if (empty($element)) continue;
            if (!empty($element['widgetType'])) {
                $widgets[] = $element['widgetType'];
            }
            if (!empty($element['elements'])) {
                $widgets = array_merge($widgets, $this->extract_widgets_from_data($element['elements']));
            }
        }

        return $widgets;
    }

    private function templates_use_animations() {
        $uses_animations = get_transient('okthemes_template_animations');
        if (false !== $uses_animations) return $uses_animations;

        $uses_animations = false;
        $templates = get_posts([
            'post_type' => 'okthemes_template',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        foreach ($templates as $template) {
            if (class_exists('Elementor\\Plugin')) {
                $document = \Elementor\Plugin::$instance->documents->get($template->ID);
                if ($document) {
                    $data = $document->get_elements_data();
                    if (!empty($data) && $this->data_has_animations($data)) {
                        $uses_animations = true;
                        break;
                    }
                }
            }
        }

        set_transient('okthemes_template_animations', $uses_animations, WEEK_IN_SECONDS);
        return $uses_animations;
    }

    private function data_has_animations($elements) {
        if (!is_array($elements)) return false;

        foreach ($elements as $element) {
            if (empty($element)) continue;
            if (!empty($element['settings']) && (
                !empty($element['settings']['_animation']) ||
                !empty($element['settings']['animation']) ||
                !empty($element['settings']['entrance_animation'])
            )) {
                return true;
            }
            if (!empty($element['elements']) && $this->data_has_animations($element['elements'])) {
                return true;
            }
        }

        return false;
    }

    private function is_resource_used_in_templates($resource_name) {
        $resource_widget_map = [
            'dialog' => ['popup', 'lightbox'],
            'swiper' => ['slides', 'carousel', 'slider'],
            'share-link' => ['share-buttons'],
            'accordion' => ['accordion'],
            'tabs' => ['tabs'],
            'toggle' => ['toggle'],
            'video' => ['video'],
            'image-carousel' => ['image-carousel'],
            'text-editor' => ['text-editor'],
        ];

        $used_widgets = $this->get_widgets_used_in_templates();

        if (isset($resource_widget_map[$resource_name])) {
            foreach ($resource_widget_map[$resource_name] as $widget_type) {
                if (in_array($widget_type, $used_widgets)) {
                    return true;
                }
            }
        }

        return false;
    }
}

CSS_Optimizer::instance();
