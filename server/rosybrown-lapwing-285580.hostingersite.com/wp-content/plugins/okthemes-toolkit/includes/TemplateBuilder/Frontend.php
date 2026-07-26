<?php
namespace OKThemes\Toolkit\TemplateBuilder;

defined('ABSPATH') || exit;

class Frontend {
    private static $instance = null;

    protected $is_header;
    protected $header_id;
    protected $is_footer;
    protected $footer_id;
    protected $is_popup;
    protected $popup_id;
    protected $loaded_templates = [];

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'register_scripts'], 10);

        add_action('wp', function () {
            $this->init();
            add_action('okthemes_builder_header', [$this, 'header'], 5);
            if (get_post_type() !== 'okthemes_template') {
                add_action('okthemes_builder_footer', [$this, 'popup'], 10);
            }
            add_action('okthemes_builder_footer', [$this, 'footer'], 5);
        });

        add_shortcode('okthemes-tb-block', [$this, 'blocks_shortcode']);
    }

    public function register_scripts() {
        if (isset($_GET['activate']) || isset($_GET['activated'])) return;

        wp_register_style('okthemes-popup', OKT_URL . 'includes/TemplateBuilder/assets/popup.css', [], '1.0.0');
        wp_register_script('okthemes-popup', OKT_URL . 'includes/TemplateBuilder/assets/popup.js', ['jquery'], '1.0.0', true);
    }

    public function init() {
        $this->get_settings('footer');
        $this->get_settings('header');
        $this->get_settings('popup');
        $this->load_template_assets();
    }

    private function load_template_assets() {
        if (is_admin() || isset($_GET['elementor-preview'])) return;

        if ($this->is_header && $this->header_id) $this->loaded_templates[] = $this->header_id;
        if ($this->is_footer && $this->footer_id) $this->loaded_templates[] = $this->footer_id;

        if ($this->is_popup && $this->popup_id) {
            $this->loaded_templates[] = $this->popup_id;
            wp_enqueue_style('okthemes-popup');
            wp_enqueue_script('okthemes-popup');
        }

        if (!empty($this->loaded_templates) && class_exists('\Elementor\Plugin')) {
            $this->load_elementor_core_assets();
        }
    }

    private function load_elementor_core_assets() {
        wp_enqueue_style('elementor-frontend');
        if (class_exists('\ElementorPro\Plugin')) {
            wp_enqueue_style('elementor-pro-frontend');
        }
    }

    public function get_settings($type) {
        $templates = $this->get_template_id($type);
        $template = is_array($templates) ? $templates[0] : $templates;

        if ($template) {
            switch ($type) {
                case 'footer':
                    $this->is_footer = true;
                    $this->footer_id = $template;
                    break;
                case 'header':
                    $this->is_header = true;
                    $this->header_id = $template;
                    break;
                case 'popup':
                    $this->is_popup = true;
                    $this->popup_id = $template;
                    break;
            }
        }
    }

    public function get_template_id($type) {
        $templates = Rule::instance()->get_templates_by_condition();
        foreach ($templates as $item) {
            if ($item['type'] === $type) return $item['id'];
        }
        return '';
    }

    public function header() {
        if ($this->is_header) $this->display('header');
    }

    public function footer() {
        if ($this->is_footer) $this->display('footer');
    }

    public function popup() {
        if ($this->is_popup) $this->display('popup');
    }

    public function display($type) {
        $id = null;
        switch ($type) {
            case 'header':
                $id = $this->header_id;
                if ($id) echo '<header class="site-header elementor-powered">' . self::get_elementor_content($id) . '</header>';
                break;
            case 'footer':
                $id = $this->footer_id;
                if ($id) echo '<footer class="site-footer elementor-powered">' . self::get_elementor_content($id) . '</footer>';
                break;
            case 'popup':
                $id = $this->popup_id;
                if ($id && !\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                    $content = self::get_elementor_content($id);
                    self::popup_markup($content, $id);
                }
                break;
        }
        if ($id) $this->loaded_templates[] = $id;
    }

    public static function get_elementor_content($content_id) {
        if (class_exists('\Elementor\Plugin')) {
            return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($content_id, true);
        }
        return '';
    }

    public static function popup_markup($content, $id, $editing = false) {
        $meta = get_post_meta($id, 'okthemes_tb_settings', true);
        $wrapper_style = $container_style = $overly_color = $close_style = $delay = '';
        $wrapper_class = 'okthemes-popup-wrapper' . ($editing ? ' show-popup editing' : '');

        if (is_array($meta)) {
            $container_style .= 'width: 100%; max-height: 100vh;';
            $overly_color .= 'background: ' . $meta['popup_overly_color'] . ';';
            $close_style .= 'color: ' . $meta['popup_close_color'] . '; background: ' . $meta['popup_close_bg'] . '; width: ' . $meta['popup_close_size']['width'] . 'px; height: ' . $meta['popup_close_size']['height'] . 'px; border-radius: ' . $meta['popup_close_radius'] . 'px;';
            $delay = $meta['popup_delay'];
        }

        echo '<div data-lenis-prevent="true" id="popup-' . esc_attr($id) . '" class="' . esc_attr($wrapper_class) . '" style="' . esc_attr($wrapper_style) . '" data-delay="' . esc_attr($delay) . '">';
        echo '<div class="popup-overly" style="' . esc_attr($overly_color) . '"></div>';
        echo '<div class="popup-container" style="' . esc_attr($container_style) . '">';
        if ($meta['popup_close'] === 'show') {
            echo '<button data-popup="popup-' . esc_attr($id) . '" class="popup-close" style="' . esc_attr($close_style) . '"><i class="fal fa-times"></i></button>';
        }
        echo $content;
        echo '</div></div>';
    }

    public function blocks_shortcode($atts) {
        $attr = shortcode_atts(['id' => false], $atts);
        if ($attr['id']) {
            $this->loaded_templates[] = $attr['id'];
            return self::get_elementor_content($attr['id']);
        }
        return '';
    }
}

Frontend::instance();
