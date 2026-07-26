<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class DualHeading extends Widget_Base {

    public function get_name() {
        return 'okthemes-dual-heading';
    }

    public function get_title() {
        return esc_html__( 'OKT - Dual Heading', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-heading';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_keywords() {
        return ['okthemes', 'dual', 'heading'];
    }

    public function get_style_depends() { 
        return [ 'okthemes-dual-heading' ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'heading_part_one',
            [
                'label' => esc_html__( 'Heading Part One', 'okthemes-toolkit' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Title 1', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'heading_part_two',
            [
                'label' => esc_html__( 'Heading Part Two', 'okthemes-toolkit' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Title 2', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'Title HTML Tag', 'okthemes-toolkit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'h1' => ['title' => 'H1', 'icon' => 'eicon-editor-h1'],
                    'h2' => ['title' => 'H2', 'icon' => 'eicon-editor-h2'],
                    'h3' => ['title' => 'H3', 'icon' => 'eicon-editor-h3'],
                    'h4' => ['title' => 'H4', 'icon' => 'eicon-editor-h4'],
                    'h5' => ['title' => 'H5', 'icon' => 'eicon-editor-h5'],
                    'h6' => ['title' => 'H6', 'icon' => 'eicon-editor-h6'],
                ],
                'default' => 'h2',
                'toggle' => false,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'okthemes-toolkit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .okthemes-dual-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Part One
        $this->start_controls_section(
            'section_style_heading_one',
            [
                'label' => esc_html__( 'Heading Part One', 'okthemes-toolkit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_one_color',
            [
                'label' => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-dual-heading .heading-part-one' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_one_typography',
                'selector' => '{{WRAPPER}} .okthemes-dual-heading .heading-part-one',
            ]
        );

        $this->end_controls_section();

        // Style Part Two
        $this->start_controls_section(
            'section_style_heading_two',
            [
                'label' => esc_html__( 'Heading Part Two', 'okthemes-toolkit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_two_color',
            [
                'label' => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-dual-heading .heading-part-two' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_two_typography',
                'selector' => '{{WRAPPER}} .okthemes-dual-heading .heading-part-two',
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['heading_part_one'] ) && empty( $settings['heading_part_two'] ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', 'okthemes-dual-heading' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <<?php echo esc_attr( $settings['title_tag'] ); ?> class="dual-heading">
                <?php if ( ! empty( $settings['heading_part_one'] ) ): ?>
                    <span class="heading-part-one"><?php echo esc_html( $settings['heading_part_one'] ); ?></span>
                <?php endif; ?>
                <?php if ( ! empty( $settings['heading_part_two'] ) ): ?>
                    <span class="heading-part-two"><?php echo esc_html( $settings['heading_part_two'] ); ?></span>
                <?php endif; ?>
            </<?php echo esc_attr( $settings['title_tag'] ); ?>>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        view.addRenderAttribute( 'wrapper', 'class', 'okthemes-dual-heading' );
        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <{{{ settings.title_tag }}} class="dual-heading">
                <# if ( settings.heading_part_one ) { #>
                    <span class="heading-part-one">{{{ settings.heading_part_one }}}</span>
                <# } #>
                <# if ( settings.heading_part_two ) { #>
                    <span class="heading-part-two">{{{ settings.heading_part_two }}}</span>
                <# } #>
            </{{{ settings.title_tag }}}>
        </div>
        <?php
    }
}
