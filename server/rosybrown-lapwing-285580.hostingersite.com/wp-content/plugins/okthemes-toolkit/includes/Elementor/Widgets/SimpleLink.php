<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class SimpleLink extends Widget_Base {

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
        return 'okthemes-simple-link';
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
        return esc_html__( 'OKT - Simple link', 'okthemes-toolkit' );
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
        return 'eicon-editor-link';
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
        return ['okthemes', 'toolkit', 'simple-link', 'link'];
    }

    public function get_style_depends() {
        return [ 'okthemes-simple-link' ];
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
            'text',
            [
                'label'       => esc_html__( 'Text', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Click here', 'okthemes-toolkit' ),
                'placeholder' => esc_html__( 'Click here', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'okthemes-toolkit' ),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__( 'Alignment', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start'   => [
                        'title' => esc_html__( 'Left', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__( 'Right', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .okthemes-simple-link-wrapper .okthemes-simple-link' => 'justify-content: {{Value}};',
                ],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'            => esc_html__( 'Icon', 'okthemes-toolkit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin'             => 'inline',
                'label_block'      => false,
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label'     => esc_html__( 'Icon Position', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'right',
                'options'   => [
                    'left'  => esc_html__( 'Before', 'okthemes-toolkit' ),
                    'right' => esc_html__( 'After', 'okthemes-toolkit' ),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_indent',
            [
                'label'     => esc_html__( 'Icon Spacing', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .okthemes-simple-link .icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .okthemes-simple-link .icon-align-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'okthemes-toolkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .okthemes-simple-link .simple-link-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .okthemes-simple-link .simple-link-icon svg' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
			]
		);

        $this->add_control(
            'simple_link_css_id',
            [
                'label'       => esc_html__( 'Button ID', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default'     => '',
                'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'okthemes-toolkit' ),
                'description' => sprintf(
                    /* translators: 1: Code open tag, 2: Code close tag. */
                    esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'okthemes-toolkit' ),
                    '<code>',
                    '</code>'
                ),
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Button', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .okthemes-simple-link',
            ]
        );


        $this->start_controls_tabs( 'tabs_simple_link_style' );

        $this->start_controls_tab(
            'tab_simple_link_normal',
            [
                'label' => esc_html__( 'Normal', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'simple_link_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .okthemes-simple-link'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .okthemes-simple-link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_simple_link_hover',
            [
                'label' => esc_html__( 'Hover', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'simple_link_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .okthemes-simple-link:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .okthemes-simple-link:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render simple-link text.
     *
     * Render simple-link widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( [
            'icon-align' => [
                'class' => [
                    'simple-link-icon',
                    'icon-align-' . $settings['icon_align'],
                ],
            ],
            'text'       => [
                'class' => 'simple-link-text',
            ],
        ] );

        $this->add_inline_editing_attributes( 'text', 'none' );

        $has_icon = ! empty( $settings['selected_icon'] ) && ! empty( $settings['selected_icon']['value'] );

        if ( $has_icon ):
        ?>
        <span <?php $this->print_render_attribute_string( 'icon-align' );?>>
            <?php
                Icons_Manager::render_icon( $settings['selected_icon'], ['aria-hidden' => 'true'] );
            ?>
        </span>
        <?php endif;?>

        <span <?php $this->print_render_attribute_string( 'text' );?>>
            <?php $this->print_unescaped_setting( 'text' );?>
        </span>

    <?php
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
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'okthemes-simple-link-wrapper' );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'simple-link', $settings['link'] );
        }

        $this->add_render_attribute( 'simple-link', 'class', 'okthemes-simple-link' );

        if ( ! empty( $settings['simple_link_css_id'] ) ) {
            $this->add_render_attribute( 'simple-link', 'id', $settings['simple-link_css_id'] );
        }

        ?>
		<div <?php $this->print_render_attribute_string( 'wrapper' );?>>
			<a <?php $this->print_render_attribute_string( 'simple-link' );?>>
				<?php $this->render_text();?>
			</a>
		</div>
		<?php
    }

    /**
     * Render simple-link widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template() {
        ?>
		<#
		view.addRenderAttribute( 'text', 'class', 'simple-link-text' );
		view.addInlineEditingAttributes( 'text', 'none' );
		var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' )
		#>
		<div class="okthemes-simple-link-wrapper">
			<a id="{{ settings.simple_link_css_id }}" class="okthemes-simple-link" href="{{ settings.link.url }}">
                <# if ( settings.icon || settings.selected_icon.value ) { #>
                <span class="simple-link-icon icon-align-{{ settings.icon_align }}">
                        {{{ iconHTML.value }}}
                </span>
                <# } #>
                <span {{{ view.getRenderAttributeString( 'text' ) }}}>
                    {{{ settings.text }}}
                </span>
			</a>
		</div>
		<?php
    }
}