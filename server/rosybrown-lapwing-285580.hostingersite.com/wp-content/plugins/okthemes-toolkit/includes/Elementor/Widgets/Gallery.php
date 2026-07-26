<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit;

class Gallery extends Widget_Base {

    public function get_name() {
        return 'okthemes-gallery';
    }

    public function get_title() {
        return esc_html__( 'OKT - Gallery', 'okthemes-toolkit' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'okthemes_elements' ];
    }

    public function get_keywords() {
        return [ 'gallery', 'grid', 'masonry', 'images', 'lightbox', 'okthemes' ];
    }

    public function get_script_depends() {
        return [ 'imagesloaded', 'okthemes-packery', 'okthemes-lightbox', 'okthemes-gallery' ];
    }

    public function get_style_depends() {
        return [ 'okthemes-lightbox', 'okthemes-gallery' ];
    }

    protected function register_controls() {

        // ==================== Images ====================
        $this->start_controls_section(
            'section_images',
            [
                'label' => esc_html__( 'Images', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'images',
            [
                'label'      => esc_html__( 'Add Images', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::GALLERY,
                'show_label' => false,
                'default'    => [],
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label'   => esc_html__( 'Image Size', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => esc_html__( 'Thumbnail', 'okthemes-toolkit' ),
                    'medium'    => esc_html__( 'Medium', 'okthemes-toolkit' ),
                    'large'     => esc_html__( 'Large', 'okthemes-toolkit' ),
                    'full'      => esc_html__( 'Full', 'okthemes-toolkit' ),
                ],
                'default' => 'large',
            ]
        );

        $this->end_controls_section();

        // ==================== Layout ====================
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'grid'    => esc_html__( 'Grid', 'okthemes-toolkit' ),
                    'masonry' => esc_html__( 'Masonry', 'okthemes-toolkit' ),
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__( 'Columns', 'okthemes-toolkit' ),
                'type'           => Controls_Manager::SELECT,
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'selectors'      => [
                    '{{WRAPPER}} .okt-gallery-grid' => '--okt-gallery-columns: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => esc_html__( 'Gap', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 80, 'step' => 1 ],
                ],
                'default'    => [ 'size' => 12, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-gallery-grid'           => '--okt-gallery-gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .okt-gallery-masonry-sizer'  => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'aspect_ratio',
            [
                'label'     => esc_html__( 'Aspect Ratio', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'  => esc_html__( 'Natural', 'okthemes-toolkit' ),
                    '1/1'   => esc_html__( '1:1 (Square)', 'okthemes-toolkit' ),
                    '4/3'   => esc_html__( '4:3', 'okthemes-toolkit' ),
                    '3/4'   => esc_html__( '3:4 (Portrait)', 'okthemes-toolkit' ),
                    '16/9'  => esc_html__( '16:9', 'okthemes-toolkit' ),
                    '3/2'   => esc_html__( '3:2', 'okthemes-toolkit' ),
                    '2/3'   => esc_html__( '2:3', 'okthemes-toolkit' ),
                ],
                'default'   => 'none',
                'condition' => [
                    'layout' => 'grid',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Settings ====================
        $this->start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label'   => esc_html__( 'Link', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'none'     => esc_html__( 'None', 'okthemes-toolkit' ),
                    'lightbox' => esc_html__( 'Lightbox', 'okthemes-toolkit' ),
                    'file'     => esc_html__( 'Media File', 'okthemes-toolkit' ),
                ],
                'default' => 'lightbox',
            ]
        );

        $this->add_control(
            'lightbox_gallery_id',
            [
                'label'     => esc_html__( 'Lightbox Gallery ID', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::HIDDEN,
                'default'   => 'okt-gallery-' . uniqid(),
                'condition' => [
                    'link_to' => 'lightbox',
                ],
            ]
        );

        $this->add_control(
            'caption_source',
            [
                'label'   => esc_html__( 'Caption', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'none'    => esc_html__( 'None', 'okthemes-toolkit' ),
                    'title'   => esc_html__( 'Title', 'okthemes-toolkit' ),
                    'caption' => esc_html__( 'Caption', 'okthemes-toolkit' ),
                    'alt'     => esc_html__( 'Alt Text', 'okthemes-toolkit' ),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'load_more',
            [
                'label'   => esc_html__( 'Load More Button', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'load_more_initial',
            [
                'label'     => esc_html__( 'Initially Visible', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 6,
                'min'       => 1,
                'condition' => [
                    'load_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'load_more_count',
            [
                'label'     => esc_html__( 'Load per Click', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3,
                'min'       => 1,
                'condition' => [
                    'load_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label'     => esc_html__( 'Button Text', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Load More', 'okthemes-toolkit' ),
                'condition' => [
                    'load_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Image ====================
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Image', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-gallery-item__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .okt-gallery-item__inner',
            ]
        );

        $this->start_controls_tabs( 'image_tabs' );

        $this->start_controls_tab(
            'image_tab_normal',
            [ 'label' => esc_html__( 'Normal', 'okthemes-toolkit' ) ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'css_filters',
                'selector' => '{{WRAPPER}} .okt-gallery-item__inner img',
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label'     => esc_html__( 'Opacity', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item__inner img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'okthemes-toolkit' ) ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name'     => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .okt-gallery-item:hover .okt-gallery-item__inner img',
            ]
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label'     => esc_html__( 'Opacity', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item:hover .okt-gallery-item__inner img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_transition',
            [
                'label'     => esc_html__( 'Transition Duration (s)', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ],
                ],
                'default'   => [ 'size' => 0.3, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item__inner img' => 'transition: all {{SIZE}}s ease;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // ==================== Style: Overlay ====================
        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => esc_html__( 'Overlay', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_show',
            [
                'label'   => esc_html__( 'Show Overlay', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'hover'  => esc_html__( 'On Hover', 'okthemes-toolkit' ),
                    'always' => esc_html__( 'Always', 'okthemes-toolkit' ),
                    'none'   => esc_html__( 'None', 'okthemes-toolkit' ),
                ],
                'default' => 'hover',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item__overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'overlay_show!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'overlay_icon',
            [
                'label'     => esc_html__( 'Show Icon', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => [
                    'overlay_show!' => 'none',
                    'link_to'       => 'lightbox',
                ],
            ]
        );

        $this->add_control(
            'overlay_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item__overlay-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'overlay_icon'  => 'yes',
                    'overlay_show!' => 'none',
                    'link_to'       => 'lightbox',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default'   => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item__overlay-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'overlay_icon'  => 'yes',
                    'overlay_show!' => 'none',
                    'link_to'       => 'lightbox',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Caption ====================
        $this->start_controls_section(
            'section_style_caption',
            [
                'label'     => esc_html__( 'Caption', 'okthemes-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'caption_source!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-item__caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'caption_typography',
                'selector' => '{{WRAPPER}} .okt-gallery-item__caption',
            ]
        );

        $this->add_responsive_control(
            'caption_padding',
            [
                'label'      => esc_html__( 'Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-gallery-item__caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== Style: Load More ====================
        $this->start_controls_section(
            'section_style_load_more',
            [
                'label'     => esc_html__( 'Load More Button', 'okthemes-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'load_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'load_more_typography',
                'selector' => '{{WRAPPER}} .okt-gallery-load-more',
            ]
        );

        $this->add_control(
            'load_more_color',
            [
                'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-load-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg',
            [
                'label'     => esc_html__( 'Background', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-load-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'load_more_border',
                'selector' => '{{WRAPPER}} .okt-gallery-load-more',
            ]
        );

        $this->add_responsive_control(
            'load_more_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-gallery-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_padding',
            [
                'label'      => esc_html__( 'Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .okt-gallery-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_align',
            [
                'label'     => esc_html__( 'Alignment', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__( 'Center', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__( 'Right', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .okt-gallery-load-more-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['images'] ) ) {
            echo '<p>' . esc_html__( 'Please add images to the gallery.', 'okthemes-toolkit' ) . '</p>';
            return;
        }

        $layout         = $settings['layout'];
        $link_to        = $settings['link_to'];
        $caption_src    = $settings['caption_source'];
        $gallery_id     = ! empty( $settings['lightbox_gallery_id'] )
                            ? sanitize_html_class( $settings['lightbox_gallery_id'] )
                            : 'okt-gallery-' . $this->get_id();
        $aspect_ratio   = ( $layout === 'grid' && ! empty( $settings['aspect_ratio'] ) ) ? $settings['aspect_ratio'] : 'none';
        $overlay_show   = $settings['overlay_show'];
        $overlay_icon   = $settings['overlay_icon'];
        $use_load_more  = $settings['load_more'] === 'yes';
        $initial_count  = $use_load_more ? intval( $settings['load_more_initial'] ) : 0;
        $load_per_click = $use_load_more ? intval( $settings['load_more_count'] ) : 0;
        $load_more_text = $use_load_more ? $settings['load_more_text'] : '';
        $total_images   = count( $settings['images'] );

        $aspect_style = ( $aspect_ratio !== 'none' )
            ? ' style="--okt-gallery-ratio: ' . esc_attr( $aspect_ratio ) . ';"'
            : '';

        ?>
        <div class="okt-gallery-wrapper"
             data-overlay-show="<?php echo esc_attr( $overlay_show ); ?>">

            <div class="okt-gallery-grid okt-gallery-layout--<?php echo esc_attr( $layout ); ?>"
                 data-layout="<?php echo esc_attr( $layout ); ?>"
                 data-load-more="<?php echo $use_load_more ? 'yes' : 'no'; ?>"
                 data-initial="<?php echo esc_attr( $initial_count ); ?>"
                 data-per-click="<?php echo esc_attr( $load_per_click ); ?>"
                 <?php echo $aspect_style; ?>>

                <?php if ( $layout === 'masonry' ) : ?>
                    <div class="okt-gallery-masonry-sizer"></div>
                    <div class="okt-gallery-masonry-gutter"></div>
                <?php endif; ?>

                <?php foreach ( $settings['images'] as $index => $image ) :
                    $img_id   = ! empty( $image['id'] ) ? $image['id'] : 0;
                    $img_url  = $img_id
                                    ? wp_get_attachment_image_url( $img_id, $settings['image_size'] )
                                    : esc_url( $image['url'] );
                    $full_url = $img_id
                                    ? wp_get_attachment_image_url( $img_id, 'full' )
                                    : esc_url( $image['url'] );

                    if ( empty( $img_url ) ) continue;

                    // Caption
                    $caption_text = '';
                    if ( $caption_src !== 'none' && $img_id ) {
                        if ( $caption_src === 'title' ) {
                            $caption_text = get_the_title( $img_id );
                        } elseif ( $caption_src === 'caption' ) {
                            $attachment   = get_post( $img_id );
                            $caption_text = $attachment ? $attachment->post_excerpt : '';
                        } elseif ( $caption_src === 'alt' ) {
                            $caption_text = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
                        }
                    }

                    $alt          = $img_id ? get_post_meta( $img_id, '_wp_attachment_image_alt', true ) : '';
                    $is_hidden    = $use_load_more && $index >= $initial_count;
                    $item_classes = 'okt-gallery-item' . ( $is_hidden ? ' okt-gallery-item--hidden' : '' );
                ?>
                    <div class="<?php echo esc_attr( $item_classes ); ?>">
                        <div class="okt-gallery-item__inner">

                            <?php if ( $link_to === 'lightbox' ) : ?>
                                <a href="<?php echo esc_url( $full_url ); ?>"
                                   data-glightbox="gallery: <?php echo esc_attr( $gallery_id ); ?>;<?php if ( $caption_text ) echo ' description: ' . esc_attr( $caption_text ); ?>"
                                   class="okt-gallery-item__link" aria-label="<?php echo esc_attr( $alt ); ?>">
                            <?php elseif ( $link_to === 'file' ) : ?>
                                <a href="<?php echo esc_url( $full_url ); ?>" class="okt-gallery-item__link" aria-label="<?php echo esc_attr( $alt ); ?>">
                            <?php endif; ?>

                            <img src="<?php echo esc_url( $img_url ); ?>"
                                 alt="<?php echo esc_attr( $alt ); ?>"
                                 loading="lazy">

                            <?php if ( $overlay_show !== 'none' ) : ?>
                                <div class="okt-gallery-item__overlay">
                                    <?php if ( $link_to === 'lightbox' && $overlay_icon === 'yes' ) : ?>
                                        <span class="okt-gallery-item__overlay-icon" aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="1em" height="1em"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( $caption_text ) : ?>
                                        <span class="okt-gallery-item__caption"><?php echo esc_html( $caption_text ); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ( $caption_text ) : ?>
                                <span class="okt-gallery-item__caption okt-gallery-item__caption--below"><?php echo esc_html( $caption_text ); ?></span>
                            <?php endif; ?>

                            <?php if ( $link_to !== 'none' ) : ?>
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div><!-- .okt-gallery-grid -->

            <?php if ( $use_load_more && $total_images > $initial_count ) : ?>
                <div class="okt-gallery-load-more-wrapper">
                    <button class="okt-gallery-load-more"
                            data-per-click="<?php echo esc_attr( $load_per_click ); ?>">
                        <?php echo esc_html( $load_more_text ); ?>
                    </button>
                </div>
            <?php endif; ?>

        </div><!-- .okt-gallery-wrapper -->
        <?php
    }

    protected function content_template() {
        ?>
        <#
        if ( ! settings.images || settings.images.length === 0 ) { return; }

        var layout      = settings.layout;
        var overlayShow = settings.overlay_show;
        var aspectStyle = '';
        if ( layout === 'grid' && settings.aspect_ratio && settings.aspect_ratio !== 'none' ) {
            aspectStyle = 'style="--okt-gallery-ratio: ' + settings.aspect_ratio + ';"';
        }
        var useLoadMore    = settings.load_more === 'yes';
        var initialCount   = useLoadMore ? parseInt( settings.load_more_initial ) : 0;
        #>
        <div class="okt-gallery-wrapper" data-overlay-show="{{ overlayShow }}">
            <div class="okt-gallery-grid okt-gallery-layout--{{ layout }}"
                 data-layout="{{ layout }}"
                 {{{ aspectStyle }}}>

                <# if ( layout === 'masonry' ) { #>
                    <div class="okt-gallery-masonry-sizer"></div>
                <# } #>

                <# _.each( settings.images, function( image, index ) {
                    var isHidden = useLoadMore && index >= initialCount;
                    var itemClass = 'okt-gallery-item' + ( isHidden ? ' okt-gallery-item--hidden' : '' );
                #>
                    <div class="{{ itemClass }}">
                        <div class="okt-gallery-item__inner">
                            <# if ( settings.link_to !== 'none' ) { #>
                                <a href="{{ image.url }}" class="okt-gallery-item__link">
                            <# } #>
                            <img src="{{ image.url }}" loading="lazy">
                            <# if ( overlayShow !== 'none' ) { #>
                                <div class="okt-gallery-item__overlay">
                                    <# if ( settings.link_to === 'lightbox' && settings.overlay_icon === 'yes' ) { #>
                                        <span class="okt-gallery-item__overlay-icon" aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="1em" height="1em"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                        </span>
                                    <# } #>
                                </div>
                            <# } #>
                            <# if ( settings.link_to !== 'none' ) { #>
                                </a>
                            <# } #>
                        </div>
                    </div>
                <# }); #>
            </div>

            <# if ( useLoadMore && settings.images.length > initialCount ) { #>
                <div class="okt-gallery-load-more-wrapper">
                    <button class="okt-gallery-load-more">{{ settings.load_more_text }}</button>
                </div>
            <# } #>
        </div>
        <?php
    }
}
