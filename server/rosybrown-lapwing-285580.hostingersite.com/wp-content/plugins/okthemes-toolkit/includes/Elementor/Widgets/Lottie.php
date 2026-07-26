<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit;

class Lottie extends Widget_Base {

    public function get_name() {
        return 'okthemes-lottie';
    }

    public function get_title() {
        return esc_html__( 'OKT - Lottie', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-lottie';
    }

    public function get_categories() {
        return [ 'okthemes_elements' ];
    }

    public function get_keywords() {
        return [ 'lottie', 'animation', 'json', 'svg', 'okthemes' ];
    }

    public function get_script_depends() {
        return [ 'okthemes-lottie-lib', 'okthemes-lottie' ];
    }

    public function get_style_depends() {
        return [ 'okthemes-lottie' ];
    }

    protected function register_controls() {

        // ==================== Source ====================
        $this->start_controls_section(
            'section_source',
            [
                'label' => esc_html__( 'Lottie Animation', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'source',
            [
                'label'   => esc_html__( 'Source', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'url'        => esc_html__( 'External URL', 'okthemes-toolkit' ),
                    'media_file' => esc_html__( 'Media File', 'okthemes-toolkit' ),
                ],
                'default' => 'url',
            ]
        );

        $this->add_control(
            'source_url',
            [
                'label'         => esc_html__( 'Animation URL', 'okthemes-toolkit' ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://assets.lottiefiles.com/packages/lf20_example.json',
                'options'       => false,
                'default'       => [
                    'url' => 'https://assets.lottiefiles.com/packages/lf20_UJNc2t.json',
                ],
                'condition'     => [
                    'source' => 'url',
                ],
            ]
        );

        $this->add_control(
            'source_json',
            [
                'label'       => esc_html__( 'Upload JSON File', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::MEDIA,
                'media_types' => [ 'application/json' ],
                'condition'   => [
                    'source' => 'media_file',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'     => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::URL,
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // ==================== Settings ====================
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'trigger',
            [
                'label'   => esc_html__( 'Trigger', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'autoplay' => esc_html__( 'Auto Play', 'okthemes-toolkit' ),
                    'hover'    => esc_html__( 'On Hover', 'okthemes-toolkit' ),
                    'click'    => esc_html__( 'On Click', 'okthemes-toolkit' ),
                    'viewport' => esc_html__( 'On Viewport', 'okthemes-toolkit' ),
                    'none'     => esc_html__( 'None (Freeze)', 'okthemes-toolkit' ),
                ],
                'default' => 'autoplay',
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'     => esc_html__( 'Loop', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'trigger' => [ 'autoplay', 'viewport' ],
                ],
            ]
        );

        $this->add_control(
            'loop_times',
            [
                'label'       => esc_html__( 'Times to Loop', 'okthemes-toolkit' ),
                'description' => esc_html__( '0 = infinite', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'min'         => 0,
                'condition'   => [
                    'trigger' => [ 'autoplay', 'viewport' ],
                    'loop'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label'   => esc_html__( 'Animation Speed', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SLIDER,
                'range'   => [
                    'px' => [
                        'min'  => 0.1,
                        'max'  => 5,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'direction',
            [
                'label'   => esc_html__( 'Direction', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'forward' => esc_html__( 'Forward', 'okthemes-toolkit' ),
                    'reverse' => esc_html__( 'Reverse', 'okthemes-toolkit' ),
                ],
                'default' => 'forward',
            ]
        );

        $this->add_control(
            'renderer',
            [
                'label'       => esc_html__( 'Renderer', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'svg'    => esc_html__( 'SVG (Best Quality)', 'okthemes-toolkit' ),
                    'canvas' => esc_html__( 'Canvas (Best Performance)', 'okthemes-toolkit' ),
                    'html'   => esc_html__( 'HTML', 'okthemes-toolkit' ),
                ],
                'default'     => 'svg',
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Layout ====================
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => esc_html__( 'Layout', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label'      => esc_html__( 'Width', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range'      => [
                    'px' => [ 'min' => 10, 'max' => 1200 ],
                    '%'  => [ 'min' => 1,  'max' => 100  ],
                    'vw' => [ 'min' => 1,  'max' => 100  ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-lottie-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__( 'Alignment', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-lottie-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'opacity',
            [
                'label'     => esc_html__( 'Opacity', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-lottie-container' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'css_filters',
                'selector' => '{{WRAPPER}} .okt-lottie-container',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-lottie-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Resolve animation src
        if ( 'media_file' === $settings['source'] ) {
            $src = ! empty( $settings['source_json']['url'] ) ? $settings['source_json']['url'] : '';
        } else {
            $src = ! empty( $settings['source_url']['url'] ) ? $settings['source_url']['url'] : '';
        }

        if ( empty( $src ) ) {
            echo '<p>' . esc_html__( 'Please set a Lottie animation source.', 'okthemes-toolkit' ) . '</p>';
            return;
        }

        $loop       = ( $settings['loop'] === 'yes' ) ? 'true' : 'false';
        $loop_times = isset( $settings['loop_times'] ) ? intval( $settings['loop_times'] ) : 0;
        $speed      = isset( $settings['speed']['size'] ) ? floatval( $settings['speed']['size'] ) : 1;
        $direction  = ( $settings['direction'] === 'reverse' ) ? '-1' : '1';
        $renderer   = esc_attr( $settings['renderer'] );
        $trigger    = esc_attr( $settings['trigger'] );

        $has_link = ! empty( $settings['link']['url'] );

        $this->add_render_attribute( 'wrapper', 'class', 'okt-lottie-wrapper' );

        $this->add_render_attribute( 'container', [
            'class'              => 'okt-lottie-container',
            'data-src'           => esc_url( $src ),
            'data-trigger'       => $trigger,
            'data-loop'          => $loop,
            'data-loop-times'    => $loop_times,
            'data-speed'         => $speed,
            'data-direction'     => $direction,
            'data-renderer'      => $renderer,
        ] );

        if ( $has_link ) {
            $this->add_link_attributes( 'link', $settings['link'] );
        }
        ?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( $has_link ) : ?>
                <a <?php $this->print_render_attribute_string( 'link' ); ?>>
            <?php endif; ?>

            <div <?php $this->print_render_attribute_string( 'container' ); ?>></div>

            <?php if ( $has_link ) : ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var src = '';
        if ( settings.source === 'media_file' ) {
            src = settings.source_json ? settings.source_json.url : '';
        } else {
            src = settings.source_url ? settings.source_url.url : '';
        }
        if ( ! src ) { return; }
        #>
        <div class="okt-lottie-wrapper">
            <div class="okt-lottie-container"
                 data-src="{{ src }}"
                 data-trigger="{{ settings.trigger }}"
                 data-loop="{{ settings.loop === 'yes' ? 'true' : 'false' }}"
                 data-loop-times="{{ settings.loop_times }}"
                 data-speed="{{ settings.speed.size }}"
                 data-direction="{{ settings.direction === 'reverse' ? '-1' : '1' }}"
                 data-renderer="{{ settings.renderer }}">
            </div>
        </div>
        <?php
    }
}
