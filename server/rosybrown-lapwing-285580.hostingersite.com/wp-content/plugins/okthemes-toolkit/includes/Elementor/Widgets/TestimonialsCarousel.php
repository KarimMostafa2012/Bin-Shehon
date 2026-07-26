<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class TestimonialsCarousel extends Widget_Base {

    public function get_name() {
        return 'okthemes-testimonials-carousel';
    }

    public function get_title() {
        return esc_html__('OKT - Testimonials Carousel', 'okthemes-toolkit');
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_script_depends() {
        return ['okthemes-swiper', 'okthemes-testimonials-carousel'];
    }

    public function get_style_depends() {
        return ['okthemes-swiper','okthemes-testimonials-carousel'];
    }

    protected function register_controls() {
        
        // ==================== Content Section ====================
        $this->start_controls_section(
            'section_testimonials',
            [
                'label' => esc_html__('Testimonials', 'okthemes-toolkit'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'quote',
            [
                'label' => esc_html__('Quote', 'okthemes-toolkit'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('This is an amazing product. It exceeded my expectations in every way!', 'okthemes-toolkit'),
                'placeholder' => esc_html__('Enter testimonial quote...', 'okthemes-toolkit'),
                'rows' => 5,
            ]
        );

        $repeater->add_control(
            'author_name',
            [
                'label' => esc_html__('Author Name', 'okthemes-toolkit'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('John Doe', 'okthemes-toolkit'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'author_meta',
            [
                'label' => esc_html__('Author Meta', 'okthemes-toolkit'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('CEO, Company Name', 'okthemes-toolkit'),
                'description' => esc_html__('Job title, city, or any additional info', 'okthemes-toolkit'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__('Testimonials List', 'okthemes-toolkit'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'author_name' => esc_html__('Sarah Johnson', 'okthemes-toolkit'),
                        'author_meta' => esc_html__('Marketing Director, Tech Corp', 'okthemes-toolkit'),
                        'quote' => esc_html__('Outstanding service! The attention to detail and professionalism exceeded all expectations.', 'okthemes-toolkit'),
                    ],
                    [
                        'author_name' => esc_html__('Michael Chen', 'okthemes-toolkit'),
                        'author_meta' => esc_html__('Founder, StartupCo', 'okthemes-toolkit'),
                        'quote' => esc_html__('Absolutely brilliant! This solution transformed our business completely.', 'okthemes-toolkit'),
                    ],
                    [
                        'author_name' => esc_html__('Emma Williams', 'okthemes-toolkit'),
                        'author_meta' => esc_html__('Designer, Creative Studio', 'okthemes-toolkit'),
                        'quote' => esc_html__('Beautiful design and flawless execution. Highly recommended!', 'okthemes-toolkit'),
                    ],
                ],
                'title_field' => '{{{ author_name }}}',
            ]
        );

        $this->end_controls_section();

        // ==================== Layout Settings ====================
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'okthemes-toolkit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'okthemes-toolkit'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'okthemes-toolkit'),
                        'icon' => 'eicon-text-align-center',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-card' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Carousel Settings ====================
        $this->start_controls_section(
            'section_carousel_settings',
            [
                'label' => esc_html__('Carousel Settings', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'slides_per_view',
            [
                'label' => esc_html__('Slides Per View', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'slides_per_view_tablet',
            [
                'label' => esc_html__('Slides Per View (Tablet)', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'okthemes-toolkit'),
                    '1' => '1',
                    '2' => '2',
                ],
                'default' => '',
                'condition' => [
                    'slides_per_view!' => '1',
                ],
            ]
        );

        $this->add_control(
            'slides_per_view_mobile',
            [
                'label' => esc_html__('Slides Per View (Mobile)', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'okthemes-toolkit'),
                    '1' => '1',
                ],
                'default' => '',
                'condition' => [
                    'slides_per_view!' => '1',
                ],
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__('Space Between Slides', 'okthemes-toolkit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'condition' => [
                    'slides_per_view!' => '1',
                ],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'okthemes-toolkit'),
                    'arrows' => esc_html__('Arrows', 'okthemes-toolkit'),
                    'dots' => esc_html__('Dots', 'okthemes-toolkit'),
                    'both' => esc_html__('Arrows and Dots', 'okthemes-toolkit'),
                ],
                'default' => 'both',
            ]
        );

        $this->add_control(
            'prev_arrow_icon',
            [
                'label'     => esc_html__( 'Previous Arrow Icon', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [ 'value' => '', 'library' => '' ],
                'condition' => [ 'navigation' => [ 'arrows', 'both' ] ],
            ]
        );

        $this->add_control(
            'next_arrow_icon',
            [
                'label'     => esc_html__( 'Next Arrow Icon', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [ 'value' => '', 'library' => '' ],
                'condition' => [ 'navigation' => [ 'arrows', 'both' ] ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'okthemes-toolkit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed (ms)', 'okthemes-toolkit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'okthemes-toolkit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Infinite Loop', 'okthemes-toolkit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => esc_html__('Transition Speed (ms)', 'okthemes-toolkit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Card ====================
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__('Card', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Padding', 'okthemes-toolkit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '40',
                    'right' => '30',
                    'bottom' => '40',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label' => esc_html__('Background Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .okt-testimonial-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => esc_html__('Border Radius', 'okthemes-toolkit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .okt-testimonial-card',
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Quote ====================
        $this->start_controls_section(
            'section_style_quote',
            [
                'label' => esc_html__('Quote', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__('Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .okt-testimonial-quote',
            ]
        );

        $this->add_responsive_control(
            'quote_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'okthemes-toolkit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-quote' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Author Name ====================
        $this->start_controls_section(
            'section_style_author_name',
            [
                'label' => esc_html__('Author Name', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'author_name_color',
            [
                'label' => esc_html__('Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_name_typography',
                'selector' => '{{WRAPPER}} .okt-testimonial-author-name',
            ]
        );

        $this->add_responsive_control(
            'author_name_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'okthemes-toolkit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-author-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Author Meta ====================
        $this->start_controls_section(
            'section_style_author_meta',
            [
                'label' => esc_html__('Author Meta', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'author_meta_color',
            [
                'label' => esc_html__('Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .okt-testimonial-author-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_meta_typography',
                'selector' => '{{WRAPPER}} .okt-testimonial-author-meta',
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Navigation ====================
        $this->start_controls_section(
            'section_style_navigation',
            [
                'label' => esc_html__('Navigation', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $this->add_control(
            'navigation_color',
            [
                'label' => esc_html__('Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_hover_color',
            [
                'label' => esc_html__('Hover Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-next:hover, {{WRAPPER}} .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Pagination ====================
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => esc_html__('Pagination', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__('Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => esc_html__('Active Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $testimonials = $settings['testimonials'];

        if (empty($testimonials)) {
            return;
        }

        $widget_id = $this->get_id();
        $navigation = $settings['navigation'];
        $space_between = isset($settings['space_between']['size']) ? $settings['space_between']['size'] : 30;
        
        ?>
        <div class="okthemes-testimonials-carousel-wrapper">
            <div class="okthemes-testimonials-carousel swiper"
                 data-navigation="<?php echo esc_attr($navigation); ?>"
                 data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>"
                 data-autoplay-speed="<?php echo esc_attr($settings['autoplay_speed']); ?>"
                 data-loop="<?php echo esc_attr($settings['loop']); ?>"
                 data-transition-speed="<?php echo esc_attr($settings['transition_speed']); ?>"
                 data-pause-on-hover="<?php echo $settings['pause_on_hover'] === 'yes' ? 'true' : 'false'; ?>"
                 data-slides-per-view="<?php echo esc_attr($settings['slides_per_view']); ?>"
                 data-slides-per-view-tablet="<?php echo esc_attr($settings['slides_per_view_tablet']); ?>"
                 data-slides-per-view-mobile="<?php echo esc_attr($settings['slides_per_view_mobile']); ?>"
                 data-space-between="<?php echo esc_attr($space_between); ?>">
                
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $index => $item) : ?>
                        <div class="swiper-slide">
                            <div class="okt-testimonial-card">
                            
                            <?php if (!empty($item['quote'])) : ?>
                                <div class="okt-testimonial-quote">
                                    <?php echo wp_kses_post($item['quote']); ?>
                                </div>
                            <?php endif; ?>

                            <div class="okt-testimonial-author">
                                <?php if (!empty($item['author_name'])) : ?>
                                    <div class="okt-testimonial-author-name">
                                        <?php echo esc_html($item['author_name']); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['author_meta'])) : ?>
                                    <div class="okt-testimonial-author-meta">
                                        <?php echo esc_html($item['author_meta']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
                </div>

                <?php if ($navigation === 'arrows' || $navigation === 'both') : ?>
                    <div class="swiper-button-prev">
                        <?php
                        if ( ! empty( $settings['prev_arrow_icon']['value'] ) ) {
                            Icons_Manager::render_icon( $settings['prev_arrow_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </div>
                    <div class="swiper-button-next">
                        <?php
                        if ( ! empty( $settings['next_arrow_icon']['value'] ) ) {
                            Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ($navigation === 'dots' || $navigation === 'both') : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}