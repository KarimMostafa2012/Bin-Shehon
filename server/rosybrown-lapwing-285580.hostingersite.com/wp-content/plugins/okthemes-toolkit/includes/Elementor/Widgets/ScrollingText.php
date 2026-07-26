<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class ScrollingText extends Widget_Base {

    public function get_name() {
        return 'okthemes-scrolling-text';
    }

    public function get_title() {
        return esc_html__( 'OKT - Scrolling Text', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-animated-headline';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_keywords() {
        return ['okthemes', 'toolkit', 'scrolling', 'text'];
    }

    public function get_script_depends() {
        return [ 'okthemes-scrolling-text' ];
    }
    
    public function get_style_depends() {
        return [ 'okthemes-scrolling-text' ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_content_ticker',
            [
                'label' => esc_html__( 'Scrolling Text', 'okthemes-toolkit' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'ticker_text',
            [
                'label'       => esc_html__( 'Text', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your text', 'okthemes-toolkit' ),
                'default'     => esc_html__( 'Experience the wines', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'ticker_items',
            [
                'label'       => esc_html__( 'Texts', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'ticker_text' => esc_html__( 'Experience the wines', 'okthemes-toolkit' ),
                    ],
                ],
                'title_field' => '{{{ ticker_text }}}',
            ]
        );

        $this->add_control(
            'ticker_animation_time',
            [
                'label'       => esc_html__( 'Animation Time (seconds)', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 3,
                'description' => esc_html__( 'Duration of the ticker animation in seconds.', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'ticker_direction',
            [
                'label'       => esc_html__( 'Text Direction', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'left',
                'options'     => [
                    'left'  => esc_html__( 'Left to right', 'okthemes-toolkit' ),
                    'right' => esc_html__( 'Right to left', 'okthemes-toolkit' ),
                ],
            ]
        );

        $this->add_control(
            'ticker_space',
            [
                'label'       => esc_html__( 'Space Between Messages (px)', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 30,
                'description' => esc_html__( 'Space between each message in pixels.', 'okthemes-toolkit' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_ticker',
            [
                'label' => esc_html__( 'Ticker Style', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ticker_background',
            [
                'label'     => esc_html__( 'Background Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-scrolling-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ticker_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-scrolling-text .ticker-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'ticker_typography',
                'selector' => '{{WRAPPER}} .okthemes-scrolling-text .ticker-text',
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'okthemes-toolkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .okthemes-scrolling-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['ticker_items'] ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', 'okthemes-scrolling-text' );
        $this->add_render_attribute( 'ticker_inner', 'class', 'ticker-inner' );
        $this->add_render_attribute( 'ticker_inner', 'data-animation-time', $settings['ticker_animation_time'] );
        $this->add_render_attribute( 'ticker_inner', 'data-direction', $settings['ticker_direction'] );
        $this->add_render_attribute( 'ticker_inner', 'data-space', $settings['ticker_space'] );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="ticker-container">
                <ticker-bar autoplay data-direction="<?php echo $settings['ticker_direction']; ?>" speed="<?php echo $settings['ticker_animation_time']; ?>" space="<?php echo $settings['ticker_space']; ?>">
                    <div data-ticker-frame class="ticker-frame">
                        <div data-ticker-scale class="ticker-scale ticker--unloaded">
                            <div data-ticker-text class="ticker-text">
                                <?php foreach ( $settings['ticker_items'] as $item ): ?>
                                    <div class="ticker-item">
                                        <span><?php echo wp_kses_post( $item['ticker_text'] ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </ticker-bar>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        if ( settings.ticker_items.length ) {
            view.addRenderAttribute( 'wrapper', 'class', 'okthemes-scrolling-text' );
            view.addRenderAttribute( 'ticker_inner', 'class', 'ticker-inner' );
            view.addRenderAttribute( 'ticker_inner', 'data-animation-time', settings.ticker_animation_time );
            view.addRenderAttribute( 'ticker_inner', 'data-direction', settings.ticker_direction );
            view.addRenderAttribute( 'ticker_inner', 'data-space', settings.ticker_space );
        }
        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <div class="ticker-container">
                <ticker-bar autoplay data-direction="{{{ settings.ticker_direction }}}" speed="{{{ settings.ticker_animation_time }}}">
                    <div data-ticker-frame class="ticker-frame">
                        <div data-ticker-scale class="ticker-scale ticker--unloaded">
                            <div data-ticker-text class="ticker-text">
                                <# _.each( settings.ticker_items, function( item ) { #>
                                    <div class="ticker-item">
                                        <span>{{{ item.ticker_text }}}</span>
                                    </div>
                                <# }); #>
                            </div>
                        </div>
                    </div>
                </ticker-bar>
            </div>
        </div>
        <?php
    }
}
