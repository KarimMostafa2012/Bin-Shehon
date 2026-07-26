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

class LayeredImages extends Widget_Base {

    public function get_name() {
        return 'okthemes-layered-images';
    }

    public function get_title() {
        return esc_html__( 'OKT - Layered Images', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_script_depends() {
        return [ 'okthemes-layered-images' ];
    }

    public function get_style_depends() {
        return [ 'okthemes-layered-images' ];
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
        return ['okthemes', 'toolkit', 'images'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_images',
            [
                'label' => __('Images', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'aspect_ratio',
            [
                'label'   => esc_html__('Aspect Ratio', 'okthemes-toolkit'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default'   => esc_html__('Default', 'okthemes-toolkit'),
                    'landscape' => esc_html__('Landscape (75%)', 'okthemes-toolkit'),
                    'square'    => esc_html__('Square (100%)', 'okthemes-toolkit'),
                    'portrait'  => esc_html__('Portrait (125%)', 'okthemes-toolkit'),
                ],
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
            'image_2',
            [
                'label' => __('Second Image', 'plugin-name'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'image_3',
            [
                'label' => __('Third Image', 'plugin-name'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $aspect_ratio = $this->get_settings_for_display('aspect_ratio');
        $aspect_class = !empty($aspect_ratio) ? 'aspect-ratio--' . esc_attr($aspect_ratio) : 'aspect-ratio--default';
        ?>

        <div class="layered-images">
            
            <div class="layered-images-image-wrapper layered-image-1">
                <div class="layered-images-media <?php echo $aspect_class; ?>">
                    <?php
                        if ( !empty( $settings['image_1']['url'] ) ) {   
                            printf(
                                '<img src="%1$s" title="%2$s" alt="%3$s" class="%4$s" loading="lazy" />',
                                esc_url( $settings['image_1']['url'] ),
                                esc_attr( \Elementor\Control_Media::get_image_title( $settings['image_1'] )),
                                esc_attr( \Elementor\Control_Media::get_image_alt( $settings['image_1'] )),
                                ''
                            );
                        }
                    ?>
                </div>
            </div>

            <div class="layered-images-image-wrapper layered-image-2">
                <div class="layered-images-media <?php echo $aspect_class; ?>">
                    <?php
                        if ( !empty( $settings['image_2']['url'] ) ) {   
                            printf(
                                '<img src="%1$s" title="%2$s" alt="%3$s" class="%4$s" loading="lazy" />',
                                esc_url( $settings['image_2']['url'] ),
                                esc_attr( \Elementor\Control_Media::get_image_title( $settings['image_2'] )),
                                esc_attr( \Elementor\Control_Media::get_image_alt( $settings['image_2'] )),
                                ''
                            );
                        }
                    ?>
                </div>
            </div>

            <div class="layered-images-image-wrapper layered-image-3">
                <div class="layered-images-media <?php echo $aspect_class; ?>">
                    <?php
                        if ( !empty( $settings['image_3']['url'] ) ) {   
                            printf(
                                '<img src="%1$s" title="%2$s" alt="%3$s" class="%4$s" loading="lazy" />',
                                esc_url( $settings['image_3']['url'] ),
                                esc_attr( \Elementor\Control_Media::get_image_title( $settings['image_3'] )),
                                esc_attr( \Elementor\Control_Media::get_image_alt( $settings['image_3'] )),
                                ''
                            );
                        }
                    ?>
                </div>
            </div>

            
        </div>
        <?php
    }

}