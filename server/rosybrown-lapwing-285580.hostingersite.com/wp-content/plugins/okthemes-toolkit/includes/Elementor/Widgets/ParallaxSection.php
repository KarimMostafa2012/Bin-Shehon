<?php
namespace OKThemes\Toolkit\Elementor\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Color;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ParallaxSection extends Widget_Base {

    public function get_name() {
        return 'okthemes-parallax-section';
    }

    public function get_title() {
        return esc_html__( 'OKT - Parallax Section', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_script_depends() {
        return ['okthemes-parallax-section'];
    }

    public function get_style_depends() {
        return ['okthemes-parallax-section'];
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
        return ['okthemes', 'toolkit', 'parallax'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_images',
            [
                'label' => __('Images', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'images_max_width',
            [
                'label' => __('Overlay images max width', 'plugin-name'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 10,
                    ],
                ],
                'default' => ['size' => 360],
                'selectors' => [
                    '{{WRAPPER}} .parallax-img' => 'max-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'rotation',
            [
                'label' => __('Rotation', 'plugin-name'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => ['size' => 5],
            ]
        );

        $this->add_control(
            'image_1',
            [
                'label' => __('First Image', 'plugin-name'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'button_link1',
            [
                'label' => __('First Button Link', 'plugin-name'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'plugin-name'),
            ]
        );

        $this->add_control(
            'image_2',
            [
                'label' => __('Second Image', 'plugin-name'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'button_link2',
            [
                'label' => __('Second Button Link', 'plugin-name'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'plugin-name'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
			'section_padding',
			[
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .parallax-section .parallax-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'top_title',
            [
                'label' => __('Top Title', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Top Title', 'plugin-name'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'plugin-name'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Main Title', 'plugin-name'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'plugin-name'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Enter description here.', 'plugin-name'),
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __('Empty Height (px) between Description and Button', 'plugin-name'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                        'step' => 10,
                    ],
                ],
                'default' => ['size' => 300],
                'selectors' => [
                    '{{WRAPPER}} .parallax-section .empty-space' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Click Here', 'plugin-name'),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'plugin-name'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'note',
            [
                'label' => __('Note', 'plugin-name'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Additional Note', 'plugin-name'),
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__( 'Alignment', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .parallax-section .parallax-content' => 'text-align: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_background',
            [
                'label' => __('Background', 'plugin-name'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => __('Background', 'plugin-name'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .parallax-section .parallax-content',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_text_styles',
            [
                'label' => __('Text', 'plugin-name'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'toptitle_options',
			[
				'label' => esc_html__( 'Top Title', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'toptitle_typography',
                'selector' => '{{WRAPPER}} .parallax-content h6',
            ]
        );

        $this->add_control(
            'toptitle_text_color',
            [
                'label' => __('Top Title Text Color', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-content h6' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'title_options',
			[
				'label' => esc_html__( 'Title', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .parallax-content h2',
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label' => __('Title Text Color', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-content h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'description_options',
			[
				'label' => esc_html__( 'Description', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .parallax-content h3',
            ]
        );

        $this->add_control(
            'description_text_color',
            [
                'label' => __('Description Text Color', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-content h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'note_options',
			[
				'label' => esc_html__( 'Note', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'note_typography',
                'selector' => '{{WRAPPER}} .parallax-content .note',
            ]
        );

        $this->add_control(
            'note_text_color',
            [
                'label' => __('Note Text Color', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-content .note' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_styles',
            [
                'label' => __('Button', 'plugin-name'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => __('Button Background', 'plugin-name'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .parallax-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="parallax-section" data-rotation="<?php echo esc_attr($settings['rotation']['size']); ?>">
            <div class="parallax-content">

                <?php
                //TopTitle
                if ( ! empty( $settings['top_title'] ) ) {
                    $this->add_inline_editing_attributes( 'top_title', 'basic' );

                    printf( '<div class="top_title" %1$s><h6>%2$s</h6></div>',
                        $this->get_render_attribute_string( 'top_title' ),
                        esc_html( $settings['top_title'] )
                    );
                }
                ?>

                <?php
                //Title
                if ( ! empty( $settings['title'] ) ) {
                    $this->add_inline_editing_attributes( 'title', 'basic' );

                    printf( '<div class="title" %1$s><h2>%2$s</h2></div>',
                        $this->get_render_attribute_string( 'title' ),
                        wp_kses_post( $settings['title'] )
                    );
                }
                ?>
                
                <?php
                //Description
                if ( ! empty( $settings['description'] ) ) {
                    $this->add_inline_editing_attributes( 'description', 'basic' );

                    printf( '<div class="description" %1$s><h3>%2$s</h3></div>',
                        $this->get_render_attribute_string( 'description' ),
                        wp_kses_post( $settings['description'] )
                    );
                }
                ?>
                
                <div class="empty-space"></div>

                <?php
                //Button
                if ( ! empty( $settings['button_text'] ) ) {
                    $this->add_inline_editing_attributes( 'button_text', 'none' );
                    $this->add_link_attributes( 'button_url', $settings['button_link'] );
                    $this->add_render_attribute( 'button_url', 'class', 'pricing-table-btn elementor-button' );
                    
                    printf( '<a %1$s><span %2$s>%3$s</span></a>',
                        $this->get_render_attribute_string( 'button_url' ),
                        $this->get_render_attribute_string( 'button_text' ),
                        esc_html( $settings['button_text'] )
                    );
                }
                ?>               

                <?php
                //Note
                if ( ! empty( $settings['note'] ) ) {
                    $this->add_inline_editing_attributes( 'note', 'basic' );

                    printf( '<div class="note" %1$s>%2$s</div>',
                        $this->get_render_attribute_string( 'note' ),
                        wp_kses_post( $settings['note'] )
                    );
                }
                ?>
            </div>
            <div class="parallax-gallery">
                <ul class="parallax-gallery-list">
                    <li class="parallax-gallery-item">
                        <?php if (!empty($settings['button_link1']['url'])): ?>
                            <a href="<?php echo esc_url($settings['button_link1']['url']); ?>">
                        <?php endif; ?>
                        <?php
                            if ( !empty( $settings['image_1']['url'] ) ) {   
                                printf(
                                    '<img src="%1$s" title="%2$s" alt="%3$s" class="%4$s" loading="lazy" />',
                                    esc_url( $settings['image_1']['url'] ),
                                    esc_attr( \Elementor\Control_Media::get_image_title( $settings['image_1'] )),
                                    esc_attr( \Elementor\Control_Media::get_image_alt( $settings['image_1'] )),
                                    'parallax-img'
                                );
                            }
                        ?>
                        <?php if (!empty($settings['button_link1']['url'])): ?>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li class="parallax-gallery-item">
                        <?php if (!empty($settings['button_link2']['url'])): ?>
                            <a href="<?php echo esc_url($settings['button_link2']['url']); ?>">
                        <?php endif; ?>
                        <?php
                            if ( !empty( $settings['image_2']['url'] ) ) {   
                                printf(
                                    '<img src="%1$s" title="%2$s" alt="%3$s" class="%4$s" loading="lazy" />',
                                    esc_url( $settings['image_2']['url'] ),
                                    esc_attr( \Elementor\Control_Media::get_image_title( $settings['image_2'] )),
                                    esc_attr( \Elementor\Control_Media::get_image_alt( $settings['image_2'] )),
                                    'parallax-img'
                                );
                            }
                        ?>
                        <?php if (!empty($settings['button_link2']['url'])): ?>
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
        <?php
    }

    /* protected function content_template(): void {
		?>
		<# view.addInlineEditingAttributes( 'description', 'basic' ); #>
		<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</div>
		<?php
	} */
}