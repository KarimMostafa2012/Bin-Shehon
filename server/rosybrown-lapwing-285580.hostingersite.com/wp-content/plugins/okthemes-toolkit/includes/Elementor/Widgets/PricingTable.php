<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class PricingTable extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'okthemes-pricing-table';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'OKT - Pricing Table', 'okthemes-toolkit' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-price-table';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['okthemes_elements'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['okthemes', 'toolkit', 'price', 'table'];
    }

    public function get_style_depends() {
        return [ 'okthemes-pricing-table' ];
    }


    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_header',
            [
                'label' => esc_html__( 'Header', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'header_image',
            [
                'label'   => esc_html__( 'Image', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [ 'url' => '' ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Signature Plan', 'okthemes-toolkit' ),
            ]
        );
        $this->add_control(
            'subtitle',
            [
                'label'       => esc_html__( 'Subtitle', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( '12 bottles', 'okthemes-toolkit' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pricing',
            [
                'label' => esc_html__( 'Pricing', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'currency_symbol',
            [
                'label'     => esc_html__( 'Currency Symbol', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default' => '$',
            ]
        );

        $this->add_control(
            'price',
            [
                'label'   => esc_html__( 'Price', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '79',
            ]
        );
        $this->add_control(
            'price_sup',
            [
                'label'   => esc_html__( 'Price Sup', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '99',
            ]
        );

        $this->add_control(
            'period',
            [
                'label'   => esc_html__( 'Period', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Monthly', 'okthemes-toolkit' ),
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_features',
            [
                'label' => esc_html__( 'Features', 'okthemes-toolkit' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'text',
            [
                'label'   => esc_html__( 'Text', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Exciting Feature', 'okthemes-toolkit' ),
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label'            => esc_html__( 'Icon', 'okthemes-toolkit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [
                    'value'   => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'recommended'      => [
                    'fa-regular' => [
                        'check-square',
                        'window-close',
                    ],
                    'fa-solid'   => [
                        'check',
                    ],
                ],
            ]
        );

        $this->add_control(
            'features_list',
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'show_label'  => false,
                'default'     => [
                    [
                        'text' => esc_html__( 'Dummy feature #1', 'okthemes-toolkit' ),
                        'icon' => '',
                    ],
                    [
                        'text' => esc_html__( 'Dummy feature #2', 'okthemes-toolkit' ),
                        'icon' => '',
                    ],
                    [
                        'text' => esc_html__( 'Dummy feature #3', 'okthemes-toolkit' ),
                        'icon' => '',
                    ],
                    [
                        'text' => esc_html__( 'Dummy feature #3', 'okthemes-toolkit' ),
                        'icon' => '',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Button', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => esc_html__( 'Button Text', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Choose Package', 'okthemes-toolkit' ),
                'placeholder' => esc_html__( 'Type button text here', 'okthemes-toolkit' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label'       => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => 'https://example.com',
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_badge',
            [
                'label' => esc_html__( 'Badge', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label'        => esc_html__( 'Show', 'okthemes-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'okthemes-toolkit' ),
                'label_off'    => esc_html__( 'Hide', 'okthemes-toolkit' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label'       => esc_html__( 'Badge Text', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Popular', 'okthemes-toolkit' ),
                'placeholder' => esc_html__( 'Type badge text', 'okthemes-toolkit' ),
                'condition'   => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => esc_html__( 'Header', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'header_image_width',
            [
                'label'      => esc_html__( 'Image Width', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'range'      => [
                    'px'  => [ 'min' => 10, 'max' => 400 ],
                    '%'   => [ 'min' => 5, 'max' => 100 ],
                ],
                'default'    => [ 'size' => 60, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-price-table__header-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [ 'header_image[url]!' => '' ],
            ]
        );

        $this->add_responsive_control(
            'header_image_spacing',
            [
                'label'      => esc_html__( 'Image Bottom Spacing', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default'    => [ 'size' => 16, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-price-table__header-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [ 'header_image[url]!' => '' ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__header .pricing-table-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__header .pricing-table-title',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => esc_html__( 'Subtitle Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__header .pricing-table-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__header .pricing-table-subtitle',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_pricing',
            [
                'label' => esc_html__( 'Pricing', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_section_background',
            [
                'label'     => esc_html__( 'Section Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_section_padding',
            [
                'label'      => esc_html__( 'Section Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );       
        /* Currency */
        $this->add_control(
            'heading_currency',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Currency', 'okthemes-toolkit' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'currency_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__currency_symbol' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'currency_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__currency_symbol',
            ]
        );
        /* Price */
        $this->add_control(
            'heading_price',
            [
                'type'  => Controls_Manager::HEADING,
                'label' => esc_html__( 'Price', 'okthemes-toolkit' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__price_integer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__price_integer',
            ]
        );

        /* Price sup */
        $this->add_control(
            'heading_price_sup',
            [
                'type'  => Controls_Manager::HEADING,
                'label' => esc_html__( 'Price sup', 'okthemes-toolkit' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'price_sup_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__price_sup' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_sup_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__price_sup',
            ]
        );
        /* Period */
        $this->add_control(
            'heading_period',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Period', 'okthemes-toolkit' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'period_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'period_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__price .okthemes-price-table__period',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_features',
            [
                'label' => esc_html__( 'Features', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'features_section_background',
            [
                'label'     => esc_html__( 'Section Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'features_section_padding',
            [
                'label'      => esc_html__( 'Section Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'features_list_spacing',
            [
                'label'      => esc_html__( 'Spacing Between', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'min'        => 1,
                'max'        => 100,
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list ul li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'features_list_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list ul li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'features_list_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list ul li',
            ]
        );

        $this->add_control(
            'features_icon_color',
            [
                'label'     => esc_html__( 'Icon/Dots Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list ul li::before'    => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__features-list ul li .feature-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__( 'Button', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_section_background',
            [
                'label'     => esc_html__( 'Section Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_section_padding',
            [
                'label'      => esc_html__( 'Section Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer .pricing-table-btn',
            ]
        );

        $this->start_controls_tabs( 'tabs_button' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer .pricing-table-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer .pricing-table-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer .pricing-table-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__footer .pricing-table-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_badge',
            [
                'label' => esc_html__( 'Badge', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'   => [
                    'show_badge' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'badge_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__ribbon-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__ribbon-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'badge_typography',
                'label'    => esc_html__( 'Typography', 'okthemes-toolkit' ),
                'selector' => '{{WRAPPER}} .okthemes-price-table .okthemes-price-table__ribbon-inner',
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    public function render() {
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'badge_text', 'none' );
        $this->add_render_attribute( 'badge_text', 'class', 'okthemes-price-table__ribbon-inner' );

        $this->add_inline_editing_attributes( 'title', 'basic' );
        $this->add_render_attribute( 'title', 'class', 'pricing-table-title' );

        $this->add_inline_editing_attributes( 'subtitle', 'basic' );
        $this->add_render_attribute( 'subtitle', 'class', 'pricing-table-subtitle' );

        
        $this->add_inline_editing_attributes( 'currency_symbol', 'none' );
        $this->add_render_attribute( 'currency_symbol', 'class', 'okthemes-price-table__currency_symbol' );
        
        $this->add_inline_editing_attributes( 'price', 'none' );
        $this->add_render_attribute( 'price', 'class', 'okthemes-price-table__price_integer' );

        $this->add_inline_editing_attributes( 'price_sup', 'none' );
        $this->add_render_attribute( 'price_sup', 'class', 'okthemes-price-table__price_sup' );

        $this->add_inline_editing_attributes( 'period', 'none' );
        $this->add_render_attribute( 'period', 'class', 'okthemes-price-table__period' );


        $this->add_inline_editing_attributes( 'button_text', 'none' );

        $this->add_link_attributes( 'button_url', $settings['button_link'] );
        $this->add_render_attribute( 'button_url', 'class', 'pricing-table-btn elementor-button' );

        $currency = $settings['currency_symbol'];

        $button_migrated = isset( $feature['__fa4_migrated']['selected_button_icon'] );
        $button_is_new   = empty( $feature['button_icon'] ) && Icons_Manager::is_migration_allowed();

        ?>
        <div class="okthemes-price-table">

            <div class="okthemes-price-table__header">
                <?php if ( ! empty( $settings['header_image']['url'] ) ) : ?>
                <div class="okthemes-price-table__header-image">
                    <img src="<?php echo esc_url( $settings['header_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>">
                </div>
                <?php endif; ?>
                <?php if ( $settings['title'] ): ?>
                <h4 <?php echo $this->get_render_attribute_string( 'title' );?>>
                    <?php echo wp_kses_post( $settings['title'] ); ?>
                </h4>
                <?php endif;?>

                <?php if ( $settings['subtitle'] ): ?>
                <p <?php echo $this->get_render_attribute_string( 'subtitle' );?>>
                    <?php echo wp_kses_post( $settings['subtitle'] ); ?>
                </p>
                <?php endif;?>
            </div>

            <div class="okthemes-price-table__price">

                <span <?php echo $this->get_render_attribute_string( 'currency_symbol' ); ?>>
                    <?php echo esc_html( $settings['currency_symbol'] ); ?>
                </span>

                <span <?php echo $this->get_render_attribute_string( 'price' ); ?>>
                    <?php echo esc_html( $settings['price'] ); ?>
                </span>

                <span <?php echo $this->get_render_attribute_string( 'price_sup' ); ?>>
                    <?php echo esc_html( $settings['price_sup'] ); ?>
                </span>

                <span <?php echo $this->get_render_attribute_string( 'period' ); ?>>
                    <?php echo esc_html( $settings['period'] ); ?>
                </span>

            </div>

            <?php if ( is_array( $settings['features_list'] ) ) : ?>
            <div class="okthemes-price-table__features-list">
                <ul class="pricing-features-list">
                    <?php foreach ( $settings['features_list'] as $index => $feature ) :
                        $feature_key = $this->get_repeater_setting_key( 'text', 'features_list', $index );
                        $this->add_inline_editing_attributes( $feature_key, 'none' );
                        $this->add_render_attribute( $feature_key, 'class', 'pricing-feature-text' );

                        $migrated = isset( $feature['__fa4_migrated']['selected_icon'] );
                        $is_new   = empty( $feature['icon'] ) && Icons_Manager::is_migration_allowed();
                        ?>
                        <li>
                            <span class="feature-icon">
                                <?php if ( $is_new || $migrated ): ?>
                                    <?php Icons_Manager::render_icon( $feature['selected_icon'], ['aria-hidden' => 'true'] );?>
                                <?php else: ?>
                                    <i class="<?php echo esc_attr( $feature['icon'] ) ?>"></i>
                                <?php endif;?>
                            </span>
                            <span <?php echo $this->get_render_attribute_string( $feature_key ); ?>>
                                <?php echo esc_html( $feature['text'] ); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="okthemes-price-table__footer">
                <?php if ( $settings['button_text'] ) : ?>
                <a <?php $this->print_render_attribute_string( 'button_url' ); ?>>
                    <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                        <?php echo esc_html( $settings['button_text'] ); ?>
                    </span>
                </a>
                <?php endif; ?>
            </div>

            <?php if ( 'yes' === $settings['show_badge'] ) : ?>
            <div class="okthemes-price-table__ribbon">
                <div <?php echo $this->get_render_attribute_string( 'badge_text' ); ?>>
                    <?php echo esc_html( $settings['badge_text'] ); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * Render heading widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function content_template() {
        ?>
        <#
            view.addInlineEditingAttributes( 'badge_text', 'none' );
            view.addRenderAttribute( 'badge_text', 'class', 'okthemes-price-table__ribbon-inner' );

            view.addInlineEditingAttributes( 'title', 'basic' );
            view.addRenderAttribute( 'title', 'class', 'pricing-table-title' );

            view.addInlineEditingAttributes( 'subtitle', 'basic' );
            view.addRenderAttribute( 'subtitle', 'class', 'pricing-table-subtitle' );

            view.addInlineEditingAttributes( 'currency_symbol', 'none' );
            view.addRenderAttribute( 'currency_symbol', 'class', 'okthemes-price-table__currency_symbol' );

            view.addInlineEditingAttributes( 'price', 'none' );
            view.addRenderAttribute( 'price', 'class', 'okthemes-price-table__price_integer' );

            view.addInlineEditingAttributes( 'price_sup', 'none' );
            view.addRenderAttribute( 'price_sup', 'class', 'okthemes-price-table__price_sup' );

            view.addInlineEditingAttributes( 'period', 'none' );
            view.addRenderAttribute( 'period', 'class', 'okthemes-price-table__period' );

            view.addInlineEditingAttributes( 'price_note', 'none' );
            view.addRenderAttribute( 'price_note', 'class', 'pricing-note' );

            view.addInlineEditingAttributes( 'button_text', 'none' );
            
            view.addRenderAttribute( 'button_url', 'class', 'pricing-table-btn elementor-button' );

            var iconsHTML = {},
				migrated = {};

        #>
        <div class="okthemes-price-table">
            <div class="okthemes-price-table__header">
            <# if ( settings.header_image && settings.header_image.url ) { #>
            <div class="okthemes-price-table__header-image">
                <img src="{{{ settings.header_image.url }}}" alt="{{{ settings.title }}}">
            </div>
            <# } #>
            <# if ( settings.title ) { #>
            <h4 {{{ view.getRenderAttributeString( 'title' ) }}}>
                {{{ settings.title }}}
            </h4>
            <# } #>
            <# if ( settings.subtitle ) { #>
            <p {{{ view.getRenderAttributeString( 'subtitle' ) }}}>
                {{{ settings.subtitle }}}
            </p>
            <# } #>
            </div>

            <div class="okthemes-price-table__price">
                
                <span {{{ view.getRenderAttributeString( 'currency_symbol' ) }}}>
                    {{{ settings.currency_symbol }}}
                </span>

                <span {{{ view.getRenderAttributeString( 'price' ) }}}>
                    {{{ settings.price }}}
                </span>

                <span {{{ view.getRenderAttributeString( 'price_sup' ) }}}>
                    {{{ settings.price_sup }}}
                </span>

                <span {{{ view.getRenderAttributeString( 'period' ) }}}>
                    {{{ settings.period }}}
                </span>

            </div>

            <# if ( settings.features_list ) { #>
            <div class="okthemes-price-table__features-list">
            <ul class="pricing-features-list">
                <# _.each( settings.features_list, function( feature, index ) {
                    var feature_key = view.getRepeaterSettingKey( 'text', 'features_list', index );
                    view.addInlineEditingAttributes( feature_key, 'none' );
                    view.addRenderAttribute( feature_key, 'class', 'pricing-feature-text' ); #>
                    <li>
                        <span class="feature-icon">
                            <#
								iconsHTML[ index ] = elementor.helpers.renderIcon( view, feature.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
								migrated[ index ] = elementor.helpers.isIconMigrated( feature, 'selected_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! feature.icon || migrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ feature.icon }}" aria-hidden="true"></i>
								<# }
							#>
                        </span>
                        <span {{{ view.getRenderAttributeString( feature_key ) }}}>
                            {{{ feature.text }}}
                        </span>
                    </li>
                <# } ); #>
            </ul>
            </div>
            <# } #>

            <div class="okthemes-price-table__footer">
                <# if ( settings.button_text ) { #>
                <a {{{ view.getRenderAttributeString( 'button_url' ) }}}>
                    <span {{{ view.getRenderAttributeString( 'button_text' ) }}}>
                        {{{ settings.button_text }}}
                    </span>
                    <#
                        var iconHTML = elementor.helpers.renderIcon( view, settings.selected_button_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				        migrated = elementor.helpers.isIconMigrated( settings, 'selected_button_icon' );

                        if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
                            {{{ iconHTML.value }}}
                        <# } else { #>
                            <i class="{{ settings.icon }}" aria-hidden="true"></i>
                        <# }
                    #>
                </a>
                <# } #>
            </div>

            <# if ( 'yes' === settings.show_badge ) { #>
            <div class="okthemes-price-table__ribbon">
                <div {{{ view.getRenderAttributeString( 'badge_text' ) }}}>
                    {{{ settings.badge_text }}}
                </div>
            </div>
            <# } #>

        </div>
        <?php
    }
}