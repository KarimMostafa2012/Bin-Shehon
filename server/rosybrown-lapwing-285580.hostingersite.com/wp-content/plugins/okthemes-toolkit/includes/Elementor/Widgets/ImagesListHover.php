<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class ImagesListHover extends Widget_Base {

    public function get_name() {
        return 'okthemes-images-list-hover';
    }

    public function get_title() {
        return esc_html__( 'OKT - Images List Hover', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-image';
    }

    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_keywords() {
        return ['okthemes', 'toolkit', 'image', 'hover', 'list'];
    }

    public function get_script_depends() {
        return [ 'okthemes-images-list-hover' ];
    }
    public function get_style_depends() {
        return [ 'okthemes-images-list-hover' ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'okthemes-toolkit' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image_title',
            [
                'label'       => esc_html__( 'Title', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title', 'okthemes-toolkit' ),
                'default'     => esc_html__( 'Title', 'okthemes-toolkit' ),
            ]
        );

        $repeater->add_control(
            'image_link',
            [
                'label'       => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'Enter your link', 'okthemes-toolkit' ),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__( 'Choose Image', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'images',
            [
                'label'       => esc_html__( 'Images', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'image_title' => esc_html__( 'Title #1', 'okthemes-toolkit' ),
                        'image_link'  => '#',
                        'image'       => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
                'title_field' => '{{{ image_title }}}',
            ]
        );

        $this->add_control(
            'default_image',
            [
                'label'   => esc_html__( 'Default Image', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'desktop_height',
            [
                'label'       => esc_html__( 'Desktop Height', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'screen-height-full',
                'options'     => [
                    'screen-height-full'           => esc_html__( 'Full screen height', 'okthemes-toolkit' ),
                    'screen-height-three-quarters' => esc_html__( '3/4 of screen', 'okthemes-toolkit' ),
                    'screen-height-two-thirds'     => esc_html__( '2/3 of screen', 'okthemes-toolkit' ),
                    'screen-height-one-half'       => esc_html__( '1/2 of screen', 'okthemes-toolkit' ),
                    'screen-height-one-third'      => esc_html__( '1/3 of screen', 'okthemes-toolkit' ),
                    'seven-fifty-height-hero'      => esc_html__( '750px', 'okthemes-toolkit' ),
                    'sixty-fifty-height-hero'      => esc_html__( '650px', 'okthemes-toolkit' ),
                    'five-fifty-height-hero'       => esc_html__( '550px', 'okthemes-toolkit' ),
                    'four-fifty-height-hero'       => esc_html__( '450px', 'okthemes-toolkit' ),
                ],
            ]
        );

        $this->add_control(
            'mobile_height',
            [
                'label'       => esc_html__( 'Mobile Height', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'screen-height-three-quarters--mobile',
                'options'     => [
                    'screen-height-full--mobile'           => esc_html__( 'Full screen height', 'okthemes-toolkit' ),
                    'screen-height-three-quarters--mobile' => esc_html__( '3/4 of screen', 'okthemes-toolkit' ),
                    'screen-height-two-thirds--mobile'     => esc_html__( '2/3 of screen', 'okthemes-toolkit' ),
                    'screen-height-one-half--mobile'       => esc_html__( '1/2 of screen', 'okthemes-toolkit' ),
                    'screen-height-one-third--mobile'      => esc_html__( '1/3 of screen', 'okthemes-toolkit' ),
                    'seven-fifty-height-hero--mobile'      => esc_html__( '750px', 'okthemes-toolkit' ),
                    'sixty-fifty-height-hero--mobile'      => esc_html__( '650px', 'okthemes-toolkit' ),
                    'five-fifty-height-hero--mobile'       => esc_html__( '550px', 'okthemes-toolkit' ),
                    'four-fifty-height-hero--mobile'       => esc_html__( '450px', 'okthemes-toolkit' ),
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label'       => esc_html__( 'Opacity', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['%'],
                'range'       => [
                    '%' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 5,
                    ],
                ],
                'default'     => [
                    'unit' => '%',
                    'size' => 20,
                ],
                'description' => esc_html__( 'Increase contrast for legible text.', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => esc_html__( 'Overlay Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'titles_direction',
            [
                'label'     => esc_html__( 'Titles Direction', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'row'    => esc_html__( 'Row', 'okthemes-toolkit' ),
                    'column' => esc_html__( 'Column', 'okthemes-toolkit' ),
                ],
                'default'   => 'row',
                'selectors' => [
                    '{{WRAPPER}} .images-list-hover__actions' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'titles_gap',
            [
                'label'      => esc_html__( 'Gap', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range'      => [
                    'px'  => [ 'min' => 0, 'max' => 200 ],
                    'em'  => [ 'min' => 0, 'max' => 10 ],
                    'rem' => [ 'min' => 0, 'max' => 10 ],
                ],
                'default'    => [ 'size' => 60, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .images-list-hover__actions' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .collection-hover__button',
            ]
        );

        $this->start_controls_tabs( 'title_color_tabs' );

        $this->start_controls_tab(
            'title_color_normal',
            [ 'label' => esc_html__( 'Normal', 'okthemes-toolkit' ) ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .collection-hover__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            [ 'label' => esc_html__( 'Hover / Active', 'okthemes-toolkit' ) ]
        );

        $this->add_control(
            'title_color_active',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .collection-hover__button:hover'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .collection-hover__button.is-selected' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['images'] ) ) {
            return;
        }

        $overlay_color = $settings['overlay_color'];
        $overlay_opacity = $settings['overlay_opacity']['size'] * 0.01;

        $has_default_image = !empty( $settings['default_image']['url'] );

        ?>
        <div class="images-list-hover frame <?php echo esc_attr( $settings['desktop_height'] ); ?>">
            <div class="images-list-hover__inner frame__item">
                <div class="images-list-hover__content wrapper--full-padded">
                    <div class="images-list-hover__actions" style="--overlay-bg: <?php echo esc_attr( $overlay_color ); ?>; --overlay-opacity: <?php echo esc_attr( $overlay_opacity ); ?>;">
                        <?php foreach ( $settings['images'] as $index => $item ) : 
                            $image_id = 'hover-image-' . ($has_default_image ? $index + 1 : $index);
                            ?>
                            <div><h4>
                                <a href="<?php echo esc_url( $item['image_link']['url'] ); ?>" class="collection-hover__button<?php echo $index == 0 ? ' is-selected' : ''; ?>"
                                    data-hover-target="<?php echo $image_id; ?>" data-scroll-trigger-point="middle" data-scroll-spy="#<?php echo $image_id; ?>" data-scroll-spy-mobile>
                                    <?php echo wp_kses_post( $item['image_title'] ); ?>
                                </a>
                                </h4>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="images-list-hover__images frame__item">
                <?php if ( $has_default_image ) : ?>
                    <div class="images-list-hover__image is-visible" id="hover-image-0" data-collection-image>
                        <figure class="image-wrapper image-wrapper--cover">
                            <img src="<?php echo esc_url( $settings['default_image']['url'] ); ?>" alt="Default Image">
                        </figure>
                    </div>
                <?php endif; ?>

                <?php foreach ( $settings['images'] as $index => $item ) : 
                    $image_id = 'hover-image-' . ($has_default_image ? $index + 1 : $index);
                    ?>
                    <div class="images-list-hover__image" id="<?php echo $image_id; ?>" data-collection-image>
                        <figure class="image-wrapper image-wrapper--cover">
                            <img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['image_title'] ); ?>">
                        </figure>
                    </div>
                <?php endforeach; ?>

                <div class="image-overlay" style="--overlay-bg: <?php echo esc_attr( $overlay_color ); ?>; --overlay-opacity: <?php echo esc_attr( $overlay_opacity ); ?>;"></div>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        if ( ! _.isEmpty( settings.images ) ) {
            var overlayColor = settings.overlay_color;
            var overlayOpacity = settings.overlay_opacity.size * 0.01;
            var hasDefaultImage = ! _.isEmpty( settings.default_image.url );
        #>
        <div class="images-list-hover frame {{ settings.desktop_height }}">
            <div class="images-list-hover__inner frame__item">
                <div class="images-list-hover__content wrapper--full-padded">
                    <div class="images-list-hover__actions" style="--overlay-bg: {{{ overlayColor }}}; --overlay-opacity: {{{ overlayOpacity }}};">
                        <# _.each( settings.images, function( item, index ) {
                            var imageId = 'hover-image-' + ( hasDefaultImage ? index + 1 : index );
                        #>
                            <div>
                                <a href="{{{ item.image_link.url }}}" class="collection-hover__button{{ index == 0 ? ' is-selected' : '' }}"
                                    data-hover-target="{{ imageId }}" data-scroll-trigger-point="middle" data-scroll-spy="#{{ imageId }}" data-scroll-spy-mobile>
                                    {{{ item.image_title }}}
                                </a>
                            </div>
                        <# } ); #>
                    </div>
                </div>
            </div>
    
            <div class="images-list-hover__images frame__item">
                <# if ( hasDefaultImage ) { #>
                    <div class="images-list-hover__image is-visible" id="hover-image-0" data-collection-image>
                        <figure class="image-wrapper image-wrapper--cover">
                            <img src="{{{ settings.default_image.url }}}" alt="Default Image">
                        </figure>
                    </div>
                <# } #>
    
                <# _.each( settings.images, function( item, index ) {
                    var imageId = 'hover-image-' + ( hasDefaultImage ? index + 1 : index );
                #>
                    <div class="images-list-hover__image" id="{{ imageId }}" data-collection-image>
                        <figure class="image-wrapper image-wrapper--cover">
                            <img src="{{{ item.image.url }}}" alt="{{{ item.image_title }}}">
                        </figure>
                    </div>
                <# } ); #>
    
                <div class="image-overlay" style="--overlay-bg: {{{ overlayColor }}}; --overlay-opacity: {{{ overlayOpacity }}};"></div>
            </div>
        </div>
        <#
        }
        #>
        <?php
    }
    
}
