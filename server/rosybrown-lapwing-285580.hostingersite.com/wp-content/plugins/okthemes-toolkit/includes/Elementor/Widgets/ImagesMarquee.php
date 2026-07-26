<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ImagesMarquee extends Widget_Base {

    public function get_name() {
        return 'okthemes-images-marquee';
    }

    public function get_title() {
        return esc_html__('OKT - Images Marquee', 'okthemes-toolkit');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_style_depends() {
        return ['okthemes-images-marquee'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Marquee Images', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => esc_html__('Images', 'okthemes-toolkit'),
                'type' => Controls_Manager::GALLERY,
                'default' => [],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Animation Speed (s)', 'okthemes-toolkit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 80,
            ]
        );

        $this->add_control(
            'gap',
            [
                'label' => esc_html__('Gap Between Images (px)', 'okthemes-toolkit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 0,
                'max' => 200,
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Scroll Direction', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => esc_html__('Left', 'okthemes-toolkit'),
                    'right' => esc_html__('Right', 'okthemes-toolkit'),
                ],
                'default' => 'left',
            ]
        );


        $this->add_control(
            'image_height',
            [
                'label' => esc_html__('Image Height (px)', 'okthemes-toolkit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 140,
                'min' => 50,
                'max' => 500,
                'selectors' => [
                    '{{WRAPPER}} .marquee-item img' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['images'])) return;
        $direction = $settings['direction'];
        ?>
        <div class="okthemes-marquee-carousel">
            <div class="marquee-content marquee-content--1 <?php echo esc_attr($direction); ?>" style="--speed:<?php echo esc_attr($settings['speed']); ?>s; --gap:<?php echo esc_attr($settings['gap']); ?>px;">
                <?php for ($i = 0; $i < 3; $i++): // Multiply images to ensure seamless effect ?>
                    <?php foreach ($settings['images'] as $index => $image): ?>
                        <div class="marquee-item" data-cascade style="--animation-order: <?php echo esc_attr($index + 1); ?>;">
                            <a href="#" class="wt-brands__link" aria-label="Open brand link" tabindex="-1">
                                <div class="wt-brands__image">
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="">
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
            <div class="marquee-content marquee-content--2 <?php echo esc_attr($direction); ?>" style="--speed:<?php echo esc_attr($settings['speed']); ?>s; --gap:<?php echo esc_attr($settings['gap']); ?>px;">
                <?php for ($i = 0; $i < 3; $i++): // Second set of images for staggered effect ?>
                    <?php foreach ($settings['images'] as $index => $image): ?>
                        <div class="marquee-item" data-cascade style="--animation-order: <?php echo esc_attr($index + 1); ?>;">
                            <a href="#" class="wt-brands__link" aria-label="Open brand link" tabindex="-1">
                                <div class="wt-brands__image">
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="">
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
        <?php
    }
}
