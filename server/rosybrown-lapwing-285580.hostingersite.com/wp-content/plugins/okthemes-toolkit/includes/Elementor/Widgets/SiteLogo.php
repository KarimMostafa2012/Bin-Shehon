<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class SiteLogo extends Widget_Base {

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
        return 'okthemes-site-logo';
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
        return esc_html__( 'OKT - Site Logo', 'okthemes-toolkit' );
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
        return 'eicon-site-logo';
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
        return ['okthemes', 'toolkit', 'header', 'footer', 'logo', 'site'];
    }

    public function get_style_depends() {
        return [ 'okthemes-site-logo' ];
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
            'widget_content',
            [
                'label' => esc_html__( 'General', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'logo_form',
            [
                'label'   => esc_html__( 'Logo', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'okthemes-toolkit' ),
                    'custom'  => esc_html__( 'Custom', 'okthemes-toolkit' ),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'logo_type',
            [
                'label'     => esc_html__( 'Type', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'text'  => esc_html__( 'Text', 'okthemes-toolkit' ),
                    'image' => esc_html__( 'Image', 'okthemes-toolkit' ),
                ],
                'default'   => 'text',
                'condition' => [
                    'logo_form' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'text_logo',
            [
                'label'      => esc_html__( 'Text logo', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::TEXT,
                'default'    => 'Okthemes',
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'logo_form',
                            'operator' => '==',
                            'value'    => 'custom',
                        ],
                        [
                            'name'     => 'logo_type',
                            'operator' => '==',
                            'value'    => 'text',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_logo',
            [
                'label'      => esc_html__( 'Image Logo', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::MEDIA,
                'default'    => [
                    'url' => OKT_ASSETS . '/img/options/logo.png',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'logo_form',
                            'operator' => '==',
                            'value'    => 'custom',
                        ],
                        [
                            'name'     => 'logo_type',
                            'operator' => '==',
                            'value'    => 'image',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_logo_mobile',
            [
                'label'       => esc_html__( 'Image Logo (Mobile)', 'okthemes-toolkit' ),
                'description' => esc_html__( 'Replaces the default logo on mobile screens.', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::MEDIA,
                'conditions'  => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'logo_form',
                            'operator' => '==',
                            'value'    => 'custom',
                        ],
                        [
                            'name'     => 'logo_type',
                            'operator' => '==',
                            'value'    => 'image',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_logo_sticky',
            [
                'label'       => esc_html__( 'Image Logo (Sticky)', 'okthemes-toolkit' ),
                'description' => esc_html__( 'Replaces the default logo when the header is sticky.', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::MEDIA,
                'conditions'  => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'logo_form',
                            'operator' => '==',
                            'value'    => 'custom',
                        ],
                        [
                            'name'     => 'logo_type',
                            'operator' => '==',
                            'value'    => 'image',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_alignment',
            [
                'label'       => esc_html__( 'Logo Alignment', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'     => 'left',
                'toggle'      => false,
                'selectors'   => [
                    '{{WRAPPER}} .okthemes-site-logo' => 'text-align: {{VALUE}};',
                ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'url_type',
            [
                'label'   => esc_html__( 'URL Type', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'okthemes-toolkit' ),
                    'custom'  => esc_html__( 'Custom', 'okthemes-toolkit' ),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'custom_url',
            [
                'label'       => esc_html__( 'Custom URL', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => home_url(),
                'condition'   => [
                    'url_type' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Style', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'logo_typography',
                'selector' => '{{WRAPPER}} .okthemes-site-logo',
            ]
        );

        $this->add_control(
            'logo_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-site-logo a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'logo_hover_color',
            [
                'label'     => esc_html__( 'Color(Hover)', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-site-logo a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label'          => esc_html__( 'Width', 'okthemes-toolkit' ),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .okthemes-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'max_width',
            [
                'label'          => esc_html__( 'Max Width', 'okthemes-toolkit' ),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .okthemes-site-logo' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
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
    protected function render() {
        $settings = $this->get_settings();

        if ( 'custom' === $settings['url_type'] && ! empty( $settings['custom_url']['url'] ) ) {
            $url = $settings['custom_url']['url'];
        } else {
            $url = home_url();
        }

        // Dynamically build the helper class name based on the theme name
        $theme = ucfirst(strtolower(get_template()));
        $helper_class = "\\{$theme}\\Helper";

        if (class_exists($helper_class)) {
            $site_logo_type  = $helper_class::get_option( 'site_logo_type', 'text' );
            $site_text_logo  = $helper_class::get_option( 'site_text_logo', 'Okthemes' );
            $site_image_logo = $helper_class::get_option( 'site_image_logo', ['url' => ''] );
        }
        ?>
        <?php
        $has_mobile = ( 'custom' === $settings['logo_form'] && 'image' === $settings['logo_type'] && ! empty( $settings['image_logo_mobile']['url'] ) );
        $has_sticky = ( 'custom' === $settings['logo_form'] && 'image' === $settings['logo_type'] && ! empty( $settings['image_logo_sticky']['url'] ) );
        $wrapper_classes = 'okthemes-site-logo';
        if ( $has_mobile ) $wrapper_classes .= ' has-mobile-logo';
        if ( $has_sticky ) $wrapper_classes .= ' has-sticky-logo';
        $alt = esc_attr( get_bloginfo() );
        ?>
        <div class="<?php echo esc_attr( $wrapper_classes ); ?>">
            <a href="<?php echo esc_url( $url ) ?>">
                <?php if ( 'custom' === $settings['logo_form'] ): ?>
                    <?php if ( 'text' === $settings['logo_type'] ): ?>
                        <?php echo esc_html( $settings['text_logo'] )?>
                    <?php elseif ( ! empty( $settings['image_logo']['url'] ) ): ?>
                        <img class="okt-logo-default" src="<?php echo esc_url( $settings['image_logo']['url'] ) ?>" alt="<?php echo $alt; ?>">
                        <?php if ( $has_mobile ): ?>
                        <img class="okt-logo-mobile" src="<?php echo esc_url( $settings['image_logo_mobile']['url'] ) ?>" alt="<?php echo $alt; ?>">
                        <?php endif; ?>
                        <?php if ( $has_sticky ): ?>
                        <img class="okt-logo-sticky" src="<?php echo esc_url( $settings['image_logo_sticky']['url'] ) ?>" alt="<?php echo $alt; ?>">
                        <?php endif; ?>
                    <?php endif;?>
                <?php else : ?>
                    <?php if ( 'text' === $site_logo_type && ! empty ( $site_text_logo ) ) : ?>
                        <?php echo esc_html( $site_text_logo )?>
                    <?php elseif ( 'image' === $site_logo_type && ! empty ( $site_image_logo['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $site_image_logo['url'] ) ?>" alt="<?php echo $alt; ?>">
                    <?php endif; ?>
                <?php endif;?>
            </a>
        </div>
        <?php
    }
}