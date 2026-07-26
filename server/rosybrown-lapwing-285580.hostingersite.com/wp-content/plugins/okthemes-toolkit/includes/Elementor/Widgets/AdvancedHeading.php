<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class AdvancedHeading extends Widget_Base {

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
        return 'okthemes-advanced-heading';
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
        return esc_html__( 'OKT - Advanced Heading', 'okthemes-toolkit' );
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
        return 'eicon-heading';
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
        return ['okthemes', 'toolkit', 'heading'];
    }
    
    /**
     * Retrieve the list of stylesheets the widget depends on.
     *
     * Used to enqueue the widget styles in the frontend.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget stylesheets.
     */
    public function get_style_depends() { 
        return [ 'okthemes-advanced-heading' ];
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
            'section_content_heading',
            [
                'label' => esc_html__( 'Heading', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'top_heading',
            [
                'label'       => esc_html__( 'Top Heading', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXTAREA,
            ]
        );
        

        $this->add_control(
            'main_heading',
            [
                'label'       => esc_html__( 'Main Heading', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXTAREA,
            ]
        );
        

        $this->add_control(
            'sub_heading',
            [
                'label'       => esc_html__( 'Sub Heading', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXTAREA,
            ]
        );
        
        

        $this->add_control(
            'title_tag',
            [
                'label'       => esc_html__( 'Title HTML Tag', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'h1' => [
                        'title' => esc_html__( 'H1', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__( 'H2', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__( 'H3', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__( 'H4', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__( 'H5', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__( 'H6', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-editor-h6',
                    ],
                ],
                'default'     => 'h3',
                'toggle'      => false,
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
                    '{{WRAPPER}} .okthemes-advanced-heading' => 'text-align: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_top_heading',
            [
                'label'     => esc_html__( 'Top Heading', 'okthemes-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'top_heading!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'top_heading_margin',
            [
                'label'      => esc_html__( 'Top Heading Margin', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-advanced-heading .top-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'top_heading_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-advanced-heading .top-heading-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'top_heading_typography',
                'selector' => '{{WRAPPER}} .okthemes-advanced-heading .top-heading-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'top_heading_text_shadow',
                'selector' => '{{WRAPPER}} .okthemes-advanced-heading .top-heading-text',
            ]
        );

        $this->add_control(
            'top_heading_add_background',
            [
                'label'     => esc_html__( 'Add background?', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'separator' => 'before',
                'default'   => 'no',
                'options'   => [
                    'no'      => esc_html__( 'No', 'okthemes-toolkit' ),
                    'normal'       => esc_html__( 'Normal', 'okthemes-toolkit' ),
                    'clipped' => esc_html__( 'Clipped', 'okthemes-toolkit' ),
                ],
                'description' => 'Choose "Normal" style to put a background behind the text. Choose "Clipped" style so the background will be clipped on the text.'
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'top_heading_background',
				'selector'  => '{{WRAPPER}} .okthemes-advanced-heading .top-heading .top-heading-text',
                'condition' => [
                    'top_heading_add_background!' => 'no',
                ],
			]
		);

        $this->add_control(
			'top_heading_background_animation',
			[
				'label' => esc_html__( 'Add sliding background animation', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'yes' => esc_html__( 'Yes', 'textdomain' ),
				'no' => esc_html__( 'No', 'textdomain' ),
				'return_value' => 'sliding-animation',
				'default' => 'no',
                'condition' => [
                    'top_heading_add_background!' => 'no',
                ],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_sub_heading',
            [
                'label'     => esc_html__( 'Sub Heading', 'okthemes-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'sub_heading!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_heading_margin',
            [
                'label'      => esc_html__( 'Sub Heading Margin', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-advanced-heading .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sub_heading_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-advanced-heading .sub-heading-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_heading_typography',
                'selector' => '{{WRAPPER}} .okthemes-advanced-heading .sub-heading-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'sub_heading_text_shadow',
                'selector' => '{{WRAPPER}} .okthemes-advanced-heading .sub-heading-text',
            ]
        );

        $this->add_control(
            'sub_heading_add_background',
            [
                'label'     => esc_html__( 'Add background?', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'separator' => 'before',
                'default'   => 'no',
                'options'   => [
                    'no'      => esc_html__( 'No', 'okthemes-toolkit' ),
                    'normal'       => esc_html__( 'Normal', 'okthemes-toolkit' ),
                    'clipped' => esc_html__( 'Clipped', 'okthemes-toolkit' ),
                ],
                'description' => 'Choose "Normal" style to put a background behind the text. Choose "Clipped" style so the background will be clipped on the text.'
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'sub_heading_background',
				'selector'  => '{{WRAPPER}} .okthemes-advanced-heading .sub-heading .sub-heading-text',
                'condition' => [
                    'sub_heading_add_background!' => 'no',
                ],
			]
		);

        $this->add_control(
			'sub_heading_background_animation',
			[
				'label' => esc_html__( 'Add sliding background animation', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'yes' => esc_html__( 'Yes', 'textdomain' ),
				'no' => esc_html__( 'No', 'textdomain' ),
				'return_value' => 'sliding-animation',
				'default' => 'no',
                'condition' => [
                    'sub_heading_add_background!' => 'no',
                ],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_main_heading',
            [
                'label'     => esc_html__( 'Main Heading', 'okthemes-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'main_heading!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'main_heading_margin',
            [
                'label'      => esc_html__( 'Margin', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-advanced-heading .main-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'main_heading_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-advanced-heading .main-heading' => 'color: {{VALUE}}; -webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'main_heading_text_shadow',
                'selector' => '{{WRAPPER}} .okthemes-advanced-heading .main-heading',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'main_heading_typography',
                'selector' => '{{WRAPPER}} .okthemes-advanced-heading .main-heading',
            ]
        );

        $this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'main_text_stroke',
				'selector' => '{{WRAPPER}} .okthemes-advanced-heading .main-heading',
			]
		);

        $this->add_control(
            'main_heading_add_background',
            [
                'label'     => esc_html__( 'Add background?', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'separator' => 'before',
                'default'   => 'no',
                'options'   => [
                    'no'      => esc_html__( 'No', 'okthemes-toolkit' ),
                    'normal'       => esc_html__( 'Normal', 'okthemes-toolkit' ),
                    'clipped' => esc_html__( 'Clipped', 'okthemes-toolkit' ),
                ],
                'description' => 'Choose "Normal" style to put a background behind the text. Choose "Clipped" style so the background will be clipped on the text.'
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'main_heading_background',
				'selector'  => '{{WRAPPER}} .okthemes-advanced-heading .main-heading .main-text',
                'condition' => [
                    'main_heading_add_background!' => 'no',
                ],
			]
		);

        $this->add_control(
			'main_heading_background_animation',
			[
				'label' => esc_html__( 'Add sliding background animation', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'yes' => esc_html__( 'Yes', 'textdomain' ),
				'no' => esc_html__( 'No', 'textdomain' ),
				'return_value' => 'sliding-animation',
				'default' => 'no',
                'condition' => [
                    'main_heading_add_background!' => 'no',
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
    public function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['top_heading'] ) && empty( $settings['main_heading'] ) && empty( $settings['sub_heading'] ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', 'okthemes-advanced-heading' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ) ?>>

            <?php if ( ! empty( $settings['top_heading'] ) ): ?>
                <div class="top-heading">
                    <?php
                        $this->add_render_attribute( 'top_heading', 'class', 'top-heading-text' );
                        $this->add_render_attribute( 
                            'top_heading',
                            [
                                'class' => [ $settings['top_heading_add_background'], $settings['top_heading_background_animation']]
                            ] 
                        );
                        $this->add_inline_editing_attributes( 'top_heading', 'none' );

                        printf( '<h6 %1$s>%2$s</h6>',
                            $this->get_render_attribute_string( 'top_heading' ),
                            esc_html( $settings['top_heading'] )
                        );
                    ?>

                </div>
            <?php endif;?>
            <?php
                if ( ! empty( $settings['main_heading'] ) ) {
                    $this->add_render_attribute( 
                        'main_heading',
                        [
                            'class' => ['main-text', $settings['main_heading_add_background'], $settings['main_heading_background_animation']]
                        ] 
                    );
                    $this->add_inline_editing_attributes( 'main_heading', 'none' );

                    printf( '<%1$s class="main-heading"><span %2$s>%3$s</span></%1$s>',
                        tag_escape( $settings['title_tag'] ),
                        $this->get_render_attribute_string( 'main_heading' ),
                        wp_kses_post( $settings['main_heading'] )
                    );
                }
            ?>
            <?php if ( ! empty( $settings['sub_heading'] ) ): ?>
                <div class="sub-heading">
                    <?php
                        $this->add_render_attribute( 'sub_heading', 'class', 'sub-heading-text' );
                        $this->add_render_attribute( 
                            'sub_heading',
                            [
                                'class' => [$settings['sub_heading_add_background'], $settings['sub_heading_background_animation']]
                            ] 
                        );
                        $this->add_inline_editing_attributes( 'sub_heading', 'none' );

                        printf( '<h6 %1$s>%2$s</h6>',
                            $this->get_render_attribute_string( 'sub_heading' ),
                            esc_html( $settings['sub_heading'] )
                        );
                    ?>

                </div>
            <?php endif;?>
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
            view.addRenderAttribute( 'wrapper', 'class', 'okthemes-advanced-heading');
        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <# if ( settings.top_heading ) { #>
                <#
                    view.addRenderAttribute( 'top_heading', 'class', 'top-heading-text' );
                    view.addRenderAttribute( 'top_heading', 'class', settings.top_heading_add_background );
                    view.addRenderAttribute( 'top_heading', 'class', settings.top_heading_background_animation );
                    
                    view.addInlineEditingAttributes( 'top_heading', 'none' );
                #>
                <div class="top-heading">
                    <span {{{ view.getRenderAttributeString( 'top_heading' ) }}}>{{{settings.top_heading}}}</span>
                </div>
            <# } #>
            <# if ( settings.main_heading ) {
                view.addRenderAttribute( 'main_heading', 'class', 'main-text' );
                view.addRenderAttribute( 'main_heading', 'class', settings.main_heading_add_background );
                view.addRenderAttribute( 'main_heading', 'class', settings.main_heading_background_animation );
                view.addInlineEditingAttributes( 'main_heading', 'none' ); #>

                <{{{settings.title_tag}}} class="main-heading">
                        <span {{{ view.getRenderAttributeString( 'main_heading' ) }}}>{{{ settings.main_heading }}}</span>
                </{{{settings.title_tag}}}>
            <# } #>
            <# if ( settings.sub_heading ) { #>
                <#
                    view.addRenderAttribute( 'sub_heading', 'class', 'sub-heading-text' );
                    view.addRenderAttribute( 'sub_heading', 'class', settings.sub_heading_add_background );
                    view.addRenderAttribute( 'sub_heading', 'class', settings.sub_heading_background_animation );
                    
                    view.addInlineEditingAttributes( 'sub_heading', 'none' );
                #>
                <div class="sub-heading">
                    <span {{{ view.getRenderAttributeString( 'sub_heading' ) }}}>{{{settings.sub_heading}}}</span>
                </div>
            <# } #>
        </div>
        <?php
    }
}