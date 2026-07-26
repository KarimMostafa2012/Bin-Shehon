<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use OKThemes\Toolkit\Functions\Helpers as OKT_Helpers;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ContentCarousel extends Widget_Base {

    public function get_name() {
        return 'okthemes-content-carousel';
    }

    public function get_title() {
        return esc_html__('OKT - Content Carousel', 'okthemes-toolkit');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_script_depends() {
        return ['okthemes-swiper', 'okthemes-content-carousel'];
    }

    public function get_style_depends() {
        return ['okthemes-content-carousel'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Carousel Slides', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => esc_html__('Slides', 'okthemes-toolkit'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'pagination_title',
                        'label' => esc_html__('Pagination Title', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXT,
                    ],
                    [
                        'name' => 'top_title',
                        'label' => esc_html__('Top Title', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Collection', 'okthemes-toolkit'),
                    ],
                    [
                        'name' => 'title',
                        'label' => esc_html__('Title', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Velocità', 'okthemes-toolkit'),
                    ],
                    [
                        'name' => 'description',
                        'label' => esc_html__('Description', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXTAREA,
                        'default' => esc_html__('Inspired by speed and precision...', 'okthemes-toolkit'),
                    ],
                    [
                        'name' => 'button_text',
                        'label' => esc_html__('Button Text', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Shop the watches', 'okthemes-toolkit'),
                    ],
                    [
                        'name' => 'button_link',
                        'label' => esc_html__('Button Link', 'okthemes-toolkit'),
                        'type' => Controls_Manager::URL,
                        'placeholder' => esc_url('#'),
                    ],
                    [
                        'name' => 'media_type',
                        'label' => esc_html__('Media Type', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'image',
                        'options' => [
                            'image' => esc_html__('Image', 'okthemes-toolkit'),
                            'video' => esc_html__('Video', 'okthemes-toolkit'),
                        ],
                    ],
                    [
                        'name' => 'image',
                        'label' => esc_html__('Image', 'okthemes-toolkit'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition' => [
                            'media_type' => 'image',
                        ],
                    ],
                    // Video fields - mimicking Elementor's Video widget controls
                    [
                        'name' => 'video_type',
                        'label' => esc_html__('Source', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'youtube',
                        'options' => [
                            'youtube' => esc_html__('YouTube', 'okthemes-toolkit'),
                            'vimeo' => esc_html__('Vimeo', 'okthemes-toolkit'),
                            'hosted' => esc_html__('Self Hosted', 'okthemes-toolkit'),
                        ],
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'youtube_url',
                        'label' => esc_html__('Link', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'placeholder' => esc_html__('Enter your URL', 'okthemes-toolkit'),
                        'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
                        'condition' => [
                            'media_type' => 'video',
                            'video_type' => 'youtube',
                        ],
                    ],
                    [
                        'name' => 'vimeo_url',
                        'label' => esc_html__('Link', 'okthemes-toolkit'),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'placeholder' => esc_html__('Enter your URL', 'okthemes-toolkit'),
                        'default' => 'https://vimeo.com/235215203',
                        'condition' => [
                            'media_type' => 'video',
                            'video_type' => 'vimeo',
                        ],
                    ],
                    [
                        'name' => 'hosted_url',
                        'label' => esc_html__('Video File', 'okthemes-toolkit'),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'media_type' => 'video',
                        'condition' => [
                            'media_type' => 'video',
                            'video_type' => 'hosted',
                        ],
                    ],
                    [
                        'name' => 'aspect_ratio',
                        'label' => esc_html__('Aspect Ratio', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '169' => '16:9',
                            '219' => '21:9',
                            '43' => '4:3',
                            '32' => '3:2',
                            '11' => '1:1',
                            '916' => '9:16',
                        ],
                        'default' => '169',
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'autoplay',
                        'label' => esc_html__('Autoplay', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SWITCHER,
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'mute',
                        'label' => esc_html__('Mute', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'yes',
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'loop',
                        'label' => esc_html__('Loop', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SWITCHER,
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'controls',
                        'label' => esc_html__('Player Controls', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'yes',
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'show_image_overlay',
                        'label' => esc_html__('Image Overlay', 'okthemes-toolkit'),
                        'type' => Controls_Manager::SWITCHER,
                        'condition' => [
                            'media_type' => 'video',
                        ],
                    ],
                    [
                        'name' => 'image_overlay',
                        'label' => esc_html__('Choose Image', 'okthemes-toolkit'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition' => [
                            'media_type' => 'video',
                            'show_image_overlay' => 'yes',
                        ],
                    ],
                ],
                'default' => [],
                'title_field' => '{{{ title }}}',
            ]
        );
        
        $this->end_controls_section();


        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout Settings', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label' => esc_html__('Layout style', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Left image + Right content', 'okthemes-toolkit'),
                    'top_bottom_layout' => esc_html__('Top image + Bottom content', 'okthemes-toolkit'),
                ],
                'default' => 'default',
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_carousel',
            [
                'label' => esc_html__('Carousel Settings', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Slider Direction', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'okthemes-toolkit'),
                    'vertical' => esc_html__('Vertical', 'okthemes-toolkit'),
                ],
                'default' => 'horizontal',
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
                    '4' => '4',
                    'auto' => esc_html__('Auto', 'okthemes-toolkit'),
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
                    '3' => '3',
                    'auto' => esc_html__('Auto', 'okthemes-toolkit'),
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
                    '2' => '2',
                    'auto' => esc_html__('Auto', 'okthemes-toolkit'),
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
            'centered_slides',
            [
                'label' => esc_html__('Center Slides', 'okthemes-toolkit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
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
            'transition',
            [
                'label' => esc_html__('Transition', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => esc_html__('Slide', 'okthemes-toolkit'),
                    'fade' => esc_html__('Fade', 'okthemes-toolkit'),
                ],
                'default' => 'slide',
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

        $this->add_control(
            'stop_on_video_play',
            [
                'label' => esc_html__('Stop Autoplay on Video Play', 'okthemes-toolkit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
    }

    /**
     * Get video parameters for URL
     */
    protected function get_video_parameters($slide) {
        $params = [];
        
        if ($slide['video_type'] === 'youtube') {
            $params['autoplay'] = $slide['autoplay'] ? '1' : '0';
            $params['mute'] = $slide['mute'] ? '1' : '0';
            $params['controls'] = $slide['controls'] ? '1' : '0';
            $params['loop'] = $slide['loop'] ? '1' : '0';
            $params['rel'] = '0';
            
            if ($slide['loop']) {
                // For loop to work, YouTube needs playlist parameter
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $slide['youtube_url'], $matches);
                if (!empty($matches[1])) {
                    $params['playlist'] = $matches[1];
                }
            }
        } 
        elseif ($slide['video_type'] === 'vimeo') {
            $params['autoplay'] = $slide['autoplay'] ? '1' : '0';
            $params['muted'] = $slide['mute'] ? '1' : '0';
            $params['loop'] = $slide['loop'] ? '1' : '0';
            $params['title'] = '0';
            $params['byline'] = '0';
            $params['portrait'] = '0';
        }
        
        return $params;
    }

    /**
     * Render YouTube video
     */
    protected function render_youtube_video($slide) {
        $params = $this->get_video_parameters($slide);
        
        // Extract video ID
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $slide['youtube_url'], $matches);
        $video_id = !empty($matches[1]) ? $matches[1] : '';
        
        if (!$video_id) {
            return;
        }
        
        // Create embed URL with parameters
        $embed_url = 'https://www.youtube.com/embed/' . $video_id . '?' . http_build_query($params);
        
        $aspect_ratio_class = 'elementor-aspect-ratio-' . $slide['aspect_ratio'];
        
        // Create a unique ID for this video
        $video_id = 'video-' . $this->get_id() . '-' . uniqid();
        ?>
        
        <div class="elementor-video-wrapper <?php echo esc_attr($aspect_ratio_class); ?>">
            <?php if ($slide['show_image_overlay'] && !empty($slide['image_overlay']['url'])) : ?>
                <div class="elementor-custom-embed-image-overlay" data-video-id="<?php echo esc_attr($video_id); ?>" style="background-image: url(<?php echo esc_url($slide['image_overlay']['url']); ?>);">
                    <div class="elementor-custom-embed-play" role="button">
                        <i class="eicon-play" aria-hidden="true"></i>
                        <span class="elementor-screen-only"><?php echo esc_html__('Play Video', 'okthemes-toolkit'); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <iframe 
                id="<?php echo esc_attr($video_id); ?>" 
                class="elementor-video-iframe" 
                src="<?php echo esc_url($embed_url); ?>" 
                title="<?php echo esc_attr($slide['title']); ?>" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
        
        <?php
    }

    /**
     * Render Vimeo video
     */
    protected function render_vimeo_video($slide) {
        $params = $this->get_video_parameters($slide);
        
        // Extract video ID
        preg_match('/(?:vimeo\.com\/(?:video\/|channels\/[^\/]+\/|groups\/[^\/]+\/videos\/|album\/[^\/]+\/video\/|)|\d+)(\d+)(?:$|\/|\?)/', $slide['vimeo_url'], $matches);
        $video_id = !empty($matches[1]) ? $matches[1] : '';
        
        if (!$video_id) {
            return;
        }
        
        // Create embed URL with parameters
        $embed_url = 'https://player.vimeo.com/video/' . $video_id . '?' . http_build_query($params);
        
        $aspect_ratio_class = 'elementor-aspect-ratio-' . $slide['aspect_ratio'];
        
        // Create a unique ID for this video
        $video_id = 'video-' . $this->get_id() . '-' . uniqid();
        ?>
        
        <div class="elementor-video-wrapper <?php echo esc_attr($aspect_ratio_class); ?>">
            <?php if ($slide['show_image_overlay'] && !empty($slide['image_overlay']['url'])) : ?>
                <div class="elementor-custom-embed-image-overlay" data-video-id="<?php echo esc_attr($video_id); ?>" style="background-image: url(<?php echo esc_url($slide['image_overlay']['url']); ?>);">
                    <div class="elementor-custom-embed-play" role="button">
                        <i class="eicon-play" aria-hidden="true"></i>
                        <span class="elementor-screen-only"><?php echo esc_html__('Play Video', 'okthemes-toolkit'); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <iframe 
                id="<?php echo esc_attr($video_id); ?>" 
                class="elementor-video-iframe" 
                src="<?php echo esc_url($embed_url); ?>" 
                title="<?php echo esc_attr($slide['title']); ?>" 
                frameborder="0" 
                allow="autoplay; fullscreen; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
        
        <?php
    }

    /**
     * Render self-hosted video
     */
    protected function render_hosted_video($slide) {
        if (empty($slide['hosted_url']['url'])) {
            return;
        }
        
        $video_url = $slide['hosted_url']['url'];
        $aspect_ratio_class = 'elementor-aspect-ratio-' . $slide['aspect_ratio'];
        
        // Set up video params for wp_video_shortcode
        $video_params = [
            'src' => $video_url,
            'autoplay' => $slide['autoplay'] ? 'on' : 'off',
            'loop' => $slide['loop'] ? 'on' : 'off',
            'controls' => $slide['controls'] ? 'on' : 'off',
            'muted' => $slide['mute'] ? 'on' : 'off',
            'preload' => 'metadata',
        ];
        
        // Generate a unique ID for the video
        $video_id = 'video-' . $this->get_id() . '-' . uniqid();
        ?>
        
        <div class="elementor-video-wrapper <?php echo esc_attr($aspect_ratio_class); ?>">
            <?php if ($slide['show_image_overlay'] && !empty($slide['image_overlay']['url'])) : ?>
                <div class="elementor-custom-embed-image-overlay" data-video-id="<?php echo esc_attr($video_id); ?>" style="background-image: url(<?php echo esc_url($slide['image_overlay']['url']); ?>);">
                    <div class="elementor-custom-embed-play" role="button">
                        <i class="eicon-play" aria-hidden="true"></i>
                        <span class="elementor-screen-only"><?php echo esc_html__('Play Video', 'okthemes-toolkit'); ?></span>
                    </div>
                </div>
                <div id="<?php echo esc_attr($video_id); ?>" class="video-container" style="display: none;">
                    <?php echo wp_video_shortcode($video_params); ?>
                </div>
            <?php else : ?>
                <div id="<?php echo esc_attr($video_id); ?>" class="video-container">
                    <?php echo wp_video_shortcode($video_params); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['slides'])) return;
        
        $pause_on_hover = isset($settings['pause_on_hover']) && $settings['pause_on_hover'] === 'yes' ? 'true' : 'false';
        $stop_on_video = isset($settings['stop_on_video_play']) && $settings['stop_on_video_play'] === 'yes' ? 'true' : 'false';
        $direction = $settings['direction'] ?? 'horizontal';
        $slides_per_view = $settings['slides_per_view'] ?? '1';
        $slides_per_view_tablet = $settings['slides_per_view_tablet'] ?? '';
        $slides_per_view_mobile = $settings['slides_per_view_mobile'] ?? '';
        $space_between = isset($settings['space_between']['size']) ? $settings['space_between']['size'] : 30;
        $centered_slides = isset($settings['centered_slides']) && $settings['centered_slides'] === 'yes' ? 'true' : 'false';
        
        // Additional classes based on settings
        $carousel_class = 'okthemes-content-carousel';
        if ($direction === 'vertical') {
            $carousel_class .= ' okthemes-carousel-vertical';
        }
        if ($slides_per_view !== '1') {
            $carousel_class .= ' okthemes-carousel-multi-slide';
        }

        if ($settings['layout_style'] == 'top_bottom_layout') {
            $carousel_class .= ' okthemes-carousel-top-bottom-layout';
        }

        ?>
        <div class="<?php echo esc_attr($carousel_class); ?>" 
            data-navigation="<?php echo esc_attr($settings['navigation']); ?>"
            data-autoplay="<?php echo esc_attr($settings['autoplay']); ?>"
            data-autoplay-speed="<?php echo esc_attr($settings['autoplay_speed']); ?>"
            data-pause-on-hover="<?php echo esc_attr($pause_on_hover); ?>"
            data-loop="<?php echo esc_attr($settings['loop']); ?>"
            data-transition="<?php echo esc_attr($settings['transition']); ?>"
            data-transition-speed="<?php echo esc_attr($settings['transition_speed']); ?>"
            data-stop-on-video="<?php echo esc_attr($stop_on_video); ?>"
            data-direction="<?php echo esc_attr($direction); ?>"
            data-slides-per-view="<?php echo esc_attr($slides_per_view); ?>"
            data-slides-per-view-tablet="<?php echo esc_attr($slides_per_view_tablet); ?>"
            data-slides-per-view-mobile="<?php echo esc_attr($slides_per_view_mobile); ?>"
            data-space-between="<?php echo esc_attr($space_between); ?>"
            data-centered-slides="<?php echo esc_attr($centered_slides); ?>">
            <div class="okt-swiper-wrapper">
                <?php foreach ($settings['slides'] as $index => $slide): ?>
                    <div class="okt-swiper-slide">
                        <div class="carousel-content ">
                            <div class="media-container">
                                <?php if ($slide['media_type'] === 'image'): ?>
                                    <div class="image">
                                        <img src="<?php echo esc_url($slide['image']['url']); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                                    </div>
                                <?php elseif ($slide['media_type'] === 'video'): ?>
                                    <div class="video">
                                        <?php 
                                        switch ($slide['video_type']) {
                                            case 'youtube':
                                                $this->render_youtube_video($slide);
                                                break;
                                            case 'vimeo':
                                                $this->render_vimeo_video($slide);
                                                break;
                                            case 'hosted':
                                                $this->render_hosted_video($slide);
                                                break;
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-content">
                                <?php if (!empty($slide['pagination_title'])): ?>
                                    <span class="pagination-title"><?php echo esc_html($slide['pagination_title']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($slide['top_title'])): ?>
                                    <span class="top-title"><?php echo esc_html($slide['top_title']); ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($slide['title'])): ?>
                                    <h3 class="title"><?php echo esc_html($slide['title']); ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($slide['description'])): ?>
                                    <p class="description"><?php echo esc_html($slide['description']); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($slide['button_text'])): ?>
                                <div class="button-wrapper">
                                    <a href="<?php echo esc_url($slide['button_link']['url'] ?? '#'); ?>" 
                                       class="button"
                                       <?php echo !empty($slide['button_link']['is_external']) ? 'target="_blank"' : ''; ?>
                                       <?php echo !empty($slide['button_link']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
                                        <?php echo esc_html($slide['button_text']); ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($settings['navigation'] !== 'none'): ?>
                <div class="swiper-navigation-wrapper">
                    <?php if (in_array($settings['navigation'], ['arrows', 'both'])): ?>
                        <div class="okt-swiper-button-prev swiper-nav-btn">
                            <?php
                            if ( ! empty( $settings['prev_arrow_icon']['value'] ) ) {
                                Icons_Manager::render_icon( $settings['prev_arrow_icon'], [ 'aria-hidden' => 'true' ] );
                            } else {
                                echo OKT_Helpers::get_theme_icon('theme-prev-arrow');
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (in_array($settings['navigation'], ['dots', 'both'])): ?>
                        <div class="okt-swiper-pagination"></div>
                    <?php endif; ?>
                    
                    <?php if (in_array($settings['navigation'], ['arrows', 'both'])): ?>
                        <div class="okt-swiper-button-next swiper-nav-btn">
                            <?php
                            if ( ! empty( $settings['next_arrow_icon']['value'] ) ) {
                                Icons_Manager::render_icon( $settings['next_arrow_icon'], [ 'aria-hidden' => 'true' ] );
                            } else {
                                echo OKT_Helpers::get_theme_icon('theme-next-arrow');
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}