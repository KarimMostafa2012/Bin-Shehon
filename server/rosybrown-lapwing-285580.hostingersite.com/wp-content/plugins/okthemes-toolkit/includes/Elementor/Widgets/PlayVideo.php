<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class PlayVideo extends Widget_Base {

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
        return 'okthemes-play-video';
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
        return esc_html__( 'OKT - Play Video', 'okthemes-toolkit' );
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
        return 'eicon-youtube';
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
        return ['okthemes', 'toolkit', 'video', 'youtube', 'play'];
    }

    public function get_script_depends() { 
        return [ 'okthemes-lightbox', 'okthemes-play-video' ];
    }

    public function get_style_depends() {
        return [ 'okthemes-lightbox', 'okthemes-play-video' ];
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
            'video_type',
            [
                'label'   => esc_html__( 'Source', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube'     => esc_html__( 'YouTube', 'okthemes-toolkit' ),
                    'vimeo'       => esc_html__( 'Vimeo', 'okthemes-toolkit' ),
                    'dailymotion' => esc_html__( 'Dailymotion', 'okthemes-toolkit' ),
                    'hosted'      => esc_html__( 'Self Hosted', 'okthemes-toolkit' ),
                ],
            ]
        );

        $this->add_control(
            'youtube_url',
            [
                'label'       => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your URL', 'okthemes-toolkit' ) . ' (YouTube)',
                'default'     => 'https://www.youtube.com/embed/XHOmBV4js_E',
                'label_block' => true,
                'condition'   => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'vimeo_url',
            [
                'label'       => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your URL', 'okthemes-toolkit' ) . ' (Vimeo)',
                'default'     => 'https://player.vimeo.com/video/235215203',
                'label_block' => true,
                'condition'   => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->add_control(
            'dailymotion_url',
            [
                'label'       => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your URL', 'okthemes-toolkit' ) . ' (Dailymotion)',
                'default'     => 'https://www.dailymotion.com/embed/video/x6tqhqb',
                'label_block' => true,
                'condition'   => [
                    'video_type' => 'dailymotion',
                ],
            ]
        );

        $this->add_control(
            'insert_url',
            [
                'label'     => esc_html__( 'External URL', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SWITCHER,
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->add_control(
            'hosted_url',
            [
                'label'      => esc_html__( 'Choose File', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::MEDIA,
                'media_type' => 'video',
                'condition'  => [
                    'video_type' => 'hosted',
                    'insert_url' => '',
                ],
            ]
        );

        $this->add_control(
            'external_url',
            [
                'label'       => esc_html__( 'URL', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your URL', 'okthemes-toolkit' ) . ' (External)',
                'label_block' => true,
                'condition'   => [
                    'video_type' => 'hosted',
                    'insert_url' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_label',
            [
                'label'       => esc_html__( 'Label', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Play overview video', 'okthemes-toolkit' ),
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'video_width',
            [
                'label' => esc_html__( 'Video Width', 'okthemes-toolkit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1920,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 560,
                ],
                'selectors' => [
                    '{{WRAPPER}} iframe' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'video_height',
            [
                'label' => esc_html__( 'Video Height', 'okthemes-toolkit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1080,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 315,
                ],
                'selectors' => [
                    '{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        

        

        $this->end_controls_section();

        $this->start_controls_section(
            'widget_style',
            [
                'label' => esc_html__( 'Video Area', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => esc_html__( 'Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-video-popup-video-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => esc_html__( 'Margin', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .okthemes-video-popup-video-wrapper' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'widget_btn_style',
            [
                'label' => esc_html__( 'Video Btn', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'video_icn_size',
			[
				'label' => esc_html__( 'Icon Size', 'okthemes-toolkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .okthemes-video-popup-video-wrapper .popup-video-trigger-btn .pv-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .okthemes-video-popup-video-wrapper .popup-video-trigger-btn .pv-btn-icon svg' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

        $this->add_control(
            'video_icon_color',
            [
                'label'     => esc_html__( 'Icon color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-video-popup-video-wrapper .popup-video-trigger-btn .pv-btn-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .okthemes-video-popup-video-wrapper .popup-video-trigger-btn .pv-btn-icon svg path' => 'fill: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'video_label_color',
            [
                'label'     => esc_html__( 'Label color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okthemes-video-popup-video-wrapper .popup-video-trigger-btn .pv-btn-text' => 'color: {{VALUE}};',
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
        $widget_id = 'okthemes-video-' . esc_attr( $this->get_id() );
        

        $video_url = '';

        if ( 'hosted' === $settings['video_type'] ) {
            if ( !empty( $settings['insert_url'] ) ) {
                $video_url = $settings['external_url'];
            } else {
                $video_url = $settings['hosted_url']['url'];
            }
        } elseif ( 'dailymotion' === $settings['video_type'] ) {
            $video_url = $settings['dailymotion_url'];
        } elseif ( 'vimeo' === $settings['video_type'] ) {
            $video_url = $settings['vimeo_url'];

        } elseif ( 'youtube' === $settings['video_type'] ) {
            $video_url = $settings['youtube_url'];
        }

        // Add data attributes for video width and height
        $video_width = isset($settings['video_width']['size']) ? $settings['video_width']['size'] : 560; // default value
        $video_height = isset($settings['video_height']['size']) ? $settings['video_height']['size'] : 315; // default value

        $this->add_render_attribute('video-wrapper', 'data-video-width', $video_width);
        $this->add_render_attribute('video-wrapper', 'data-video-height', $video_height);
        $this->add_render_attribute('video-wrapper', 'id', $widget_id);

        ?>
        <?php if( ! empty( $video_url ) ) : ?>
        <div class="okthemes-video-popup-video-wrapper" <?php echo $this->get_render_attribute_string('video-wrapper'); ?> >
            
            <a 
                href="<?php echo esc_url( $video_url ); ?>"
                class="popup-video-trigger-btn glightbox-video"
                data-type="video"
                data-width="<?php echo esc_attr( $video_width ); ?>"
                data-height="<?php echo esc_attr( $video_height ); ?>"
                role="button"
                aria-label="<?php echo esc_attr( $settings['button_label'] ); ?>"
                title="<?php echo esc_attr( $settings['button_label'] ); ?>"
            >
                <span class="pv-btn-icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-play" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg></span>
                <span class="pv-btn-text"><?php echo esc_html($settings['button_label']); ?></span>
            </a>       
        </div>
        <?php endif; ?>
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
            var video_url = '';

            if( 'hosted' === settings.video_type ) {
                if( settings.insert_url ) {
                    video_url = settings.external_url;
                } else {
                    video_url = settings.hosted_url.url;
                }
            } else if( 'dailymotion' === settings.video_type ) {
                video_url = settings.dailymotion_url;
            } else if( 'vimeo' === settings.video_type ) {
                video_url = settings.vimeo_url;
            } else if ( 'youtube' === settings.video_type ) {
                video_url = settings.youtube_url;
            }

        #>
        <div class="okthemes-video-popup-video-wrapper">
                <a href="{{video_url}}" class="popup-video-trigger-btn">
                    <span class="pv-btn-icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-play" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg></span>
                    <span class="pv-btn-text">{{settings.button_label}}</span>
                </a>
        </div>
        <?php
    }
}