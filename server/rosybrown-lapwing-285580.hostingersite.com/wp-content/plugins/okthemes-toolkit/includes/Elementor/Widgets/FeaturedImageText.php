<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class FeaturedImageText extends Widget_Base {

    public function get_name() {
        return 'okthemes-featured-image-text';
    }

    public function get_title() {
        return esc_html__('OKT - Featured Image Text', 'okthemes-toolkit');
    }

    public function get_icon() {
        return 'eicon-image-bold';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_style_depends() {
        return [ 'okthemes-featured-image-text' ];
    }


    protected function register_controls() {

        // Image Section
        $this->start_controls_section('section_image', [
            'label' => __('Image', 'okthemes-toolkit'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('image', [
            'label' => __('Background Image', 'okthemes-toolkit'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_responsive_control('image_height', [
            'label' => __('Image Height', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh', '%', 'rem'],
            'range' => [
                'px' => ['min' => 100, 'max' => 1000],
                'vh' => ['min' => 10, 'max' => 100],
            ],
            'default' => ['unit' => 'px', 'size' => 500],
            'selectors' => [
                '{{WRAPPER}} .ok-featured-image' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'okthemes-toolkit'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('top_title', [
            'label' => __('Top Title', 'okthemes-toolkit'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => '',
            'placeholder' => __('e.g. Category label', 'okthemes-toolkit'),
        ]);

        $this->add_control('title', [
            'label' => __('Title', 'okthemes-toolkit'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __('Your Title Here', 'okthemes-toolkit'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'okthemes-toolkit'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => __('This is a short description about the section.', 'okthemes-toolkit'),
        ]);

        $this->add_control('link_url', [
            'label' => __('Link URL', 'okthemes-toolkit'),
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'show_external' => true,
            'default' => ['url' => '', 'is_external' => true],
        ]);

        $this->add_control('content_position', [
            'label' => __('Vertical Position', 'okthemes-toolkit'),
            'type' => Controls_Manager::SELECT,
            'default' => 'bottom',
            'options' => [
                'top' => __('Top', 'okthemes-toolkit'),
                'center' => __('Center', 'okthemes-toolkit'),
                'bottom' => __('Bottom', 'okthemes-toolkit'),
            ],
        ]);
        

        // Add Horizontal Position control
        $this->add_control('content_horizontal_position', [
            'label' => __('Horizontal Position', 'okthemes-toolkit'),
            'type' => Controls_Manager::SELECT,
            'default' => 'center',
            'options' => [
                'left' => __('Left', 'okthemes-toolkit'),
                'center' => __('Center', 'okthemes-toolkit'),
                'right' => __('Right', 'okthemes-toolkit'),
            ],
        ]);

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'okthemes-toolkit'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('overlay_color', [
            'label' => __('Overlay Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.3)',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-image .ok-featured-overlay' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_control('overlay_hover_color', [
            'label' => __('Overlay Hover Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.6)',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-image:hover .ok-featured-overlay' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // Style Section for Top Title
        $this->start_controls_section('section_style_top_title', [
            'label' => __('Top Title Style', 'okthemes-toolkit'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [ 'top_title!' => '' ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'top_title_typo',
                'selector' => '{{WRAPPER}} .ok-featured-top-title',
            ]
        );

        $this->add_control('top_title_color', [
            'label' => __('Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-top-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('top_title_spacing', [
            'label' => __('Bottom Spacing', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'selectors' => [
                '{{WRAPPER}} .ok-featured-top-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('top_title_text_align', [
            'label' => __('Text Align', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => [ 'title' => __('Left', 'okthemes-toolkit'),   'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __('Center', 'okthemes-toolkit'), 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => __('Right', 'okthemes-toolkit'),  'icon' => 'eicon-text-align-right' ],
            ],
            'selectors' => [
                '{{WRAPPER}} .ok-featured-top-title' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // Style Section for Title
        $this->start_controls_section('section_style_title', [
            'label' => __('Title Style', 'okthemes-toolkit'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .ok-featured-title',
            ]
        );

        $this->add_control('title_typo_color', [
            'label' => __('Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('title_text_align', [
            'label' => __('Text Align', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-title' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // Style Section for Description
        $this->start_controls_section('section_style_desc', [
            'label' => __('Description Style', 'okthemes-toolkit'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'selector' => '{{WRAPPER}} .ok-featured-desc',
            ]
        );

        $this->add_control('desc_typo_color', [
            'label' => __('Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('desc_text_align', [
            'label' => __('Text Align', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .ok-featured-desc' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $image_url = $settings['image']['url'] ?? '';
        $title = esc_html($settings['title']);
        $desc = esc_html($settings['description']);
        $position = $settings['content_position'] ?? 'bottom';
        $h_position = $settings['content_horizontal_position'] ?? 'center';
        $link = $settings['link_url']['url'] ?? '';
        $is_external = !empty($settings['link_url']['is_external']) ? ' target="_blank" rel="noopener"' : '';

        $link_html = $link ? "<a class=\"ok-featured-link\" href=\"".esc_url($link)."\"$is_external></a>" : '';

        ?>
        <div class="ok-featured-image ok-align-<?php echo esc_attr($position); ?> ok-align-x-<?php echo esc_attr($h_position); ?>" style="background-image: url('<?php echo esc_url($image_url); ?>');">
            <?php echo $link_html; ?>    
            <div class="ok-featured-overlay"></div>
            <div class="ok-featured-content">
                <?php if ( ! empty( $settings['top_title'] ) ) : ?>
                <p class="ok-featured-top-title"><?php echo esc_html( $settings['top_title'] ); ?></p>
                <?php endif; ?>
                <h4 class="ok-featured-title"><?php echo $title; ?></h4>
                <div class="ok-featured-desc"><?php echo $desc; ?></div>
            </div>
        </div>
        <?php
    }

}
