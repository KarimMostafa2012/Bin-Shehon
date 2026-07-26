<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit;

class Particles extends Widget_Base {

    public function get_name()       { return 'okthemes-particles'; }
    public function get_title()      { return esc_html__( 'OKT - Particles', 'okthemes-toolkit' ); }
    public function get_icon()       { return 'eicon-product-related'; }
    public function get_categories() { return [ 'okthemes_elements' ]; }
    public function get_keywords()   { return [ 'particles', 'background', 'canvas', 'animation', 'okthemes' ]; }

    public function get_script_depends() { return [ 'okthemes-particles-lib', 'okthemes-particles' ]; }
    public function get_style_depends()  { return [ 'okthemes-particles' ]; }

    protected function register_controls() {

        $this->start_controls_section(
            'section_config',
            [ 'label' => esc_html__( 'Particles Configuration', 'okthemes-toolkit' ) ]
        );

        $this->add_control(
            'config_info',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => wp_kses(
                    __( '<strong>How to use:</strong><br>1. Generate your config at <a href="https://vincentgarreau.com/particles.js/" target="_blank">vincentgarreau.com/particles.js</a><br>2. Click <em>Download current config (JSON)</em><br>3. Paste the JSON below.<br><br><strong>Placement:</strong> Drop this widget as the first child inside an Elementor container that has a background image set.', 'okthemes-toolkit' ),
                    [ 'strong' => [], 'br' => [], 'em' => [], 'a' => [ 'href' => [], 'target' => [] ] ]
                ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->add_control(
            'json_config',
            [
                'label'       => esc_html__( 'JSON Config', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::CODE,
                'language'    => 'json',
                'rows'        => 20,
                'default'     => '',
                'placeholder' => esc_html__( 'Paste your particles.js JSON config here…', 'okthemes-toolkit' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $raw      = trim( $settings['json_config'] );

        if ( empty( $raw ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="okt-particles-placeholder" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.15);color:#fff;font-size:13px;pointer-events:none;">Paste a particles.js JSON config in the widget panel</div>';
            }
            return;
        }

        // Decode to validate, then re-encode cleanly — never trust raw user input.
        $config = json_decode( $raw );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="okt-particles-placeholder" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(180,0,0,.3);color:#fff;font-size:13px;pointer-events:none;">Invalid JSON — please check the config</div>';
            }
            return;
        }

        $uid = 'okt-particles-' . $this->get_id();

        echo '<div class="okt-particles-wrapper" id="' . esc_attr( $uid ) . '" data-config="' . esc_attr( wp_json_encode( $config ) ) . '"></div>';
    }
}
