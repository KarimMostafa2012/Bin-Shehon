<?php
/**
 * Posts Class
 * 
 * Creates all the conditions
 * for Posts and all required
 * elements that are used.
 * 
 * @since 1.0.0
 */

namespace OKThemes\Toolkit\Elementor\Helpers;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use OKThemes\Toolkit\Elementor\Helpers\Utils;

class Posts_Helper {

    public function __construct( $thisElement ) {
        // Content
        $this->register_section( $thisElement, 'layout' );
        $this->register_section( $thisElement, 'query' );
        $this->register_section( $thisElement, 'query_metro', [ 'layout' => 'metro' ] );
        $this->register_section( $thisElement, 'pagination', [ 'carousel!' => 'yes' ] );
        $this->register_section( $thisElement, 'filters', [ 'carousel!' => 'yes' ] );
        
        // Style
        $this->register_section( $thisElement, 'layout_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'box_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'image_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'cards_style', ['skin' => 'cards'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'content_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'pagination_style', ['pagination!' => 'none', 'carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'navigation_style', ['carousel' => 'yes', 'navigation!' => 'none'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'filters_style', ['carousel!' => 'yes', 'filters' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'content_order', '', 'TAB_STYLE' );
    }

    public function get_taxonomies() {
        $taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
    }

    public function get_source() {

        $output = Utils::get_public_post_types();

        $output = array_merge( $output, [
            'manual-selection' => __( 'Manual Selection', 'okthemes-toolkit' ),
            'related' => __( 'Related', 'okthemes-toolkit' ),
            'current_query' => __( 'Current Query', 'okthemes-toolkit' ),
        ]);

        return $output;
    }

    public function layout_controls() {
        $fields = [];

        $fields['skin'] = [
            'label' => __( 'Skin', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'classic' => __( 'Classic', 'okthemes-toolkit' ),
                'cards' => __( 'Cards', 'okthemes-toolkit' ),
            ],
            'default' => 'classic'
        ];
        
        $fields['columns'] = [
            'label' => __( 'Columns', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ],
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'condition' => [
                'carousel!' => 'yes',
                'layout!' => 'metro' 
            ],
            'prefix_class' => 'l-okthemes-grid-wrapper%s--columns__',
            'frontend_available' => true,
            'custom_control' => 'add_responsive_control',
        ];

        $fields['posts_per_page'] = [
            'label' => __( 'Posts Per Page', 'okthemes-toolkit' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 100,
            'step' => 1,
            'default' => 6,
        ];

        $fields['slides_per_view'] = [
            'label' => __( 'Slides Per View', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                'auto' => __( 'Auto', 'okthemes-toolkit' ),
            ],
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'condition' => [
                'carousel' => 'yes'
            ],
            'custom_control' => 'add_responsive_control',
            'frontend_available' => true
        ];

        $fields['slide_custom_width'] = [
            'label' => __( 'Custom Width', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '%', 'px' ],
            'default' => [
                'unit' => '%',
                'size' => 50,
            ],
            'condition' => [
                'carousel' => 'yes',
                'slides_per_view' => 'auto',
            ],
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'custom_control' => 'add_responsive_control',
        ];

        $fields['slides_to_scroll'] = [
            'label' => __( 'Slides To Scroll', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
            ],
            'default' => '1',
            'tablet_default' => '1',
            'mobile_default' => '1',
            'condition' => [
                'carousel' => 'yes'
            ],
            'custom_control' => 'add_responsive_control',
            'frontend_available' => true
        ];

        $fields['image_position'] = [
            'label' => __( 'Image Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'top' => __( 'Top', 'okthemes-toolkit' ),
                'left' => __( 'Left', 'okthemes-toolkit' ),
                'right' => __( 'Right', 'okthemes-toolkit' ),
                'none' => __( 'None', 'okthemes-toolkit' ),
            ],
            'default' => 'top',
            'custom_control' => 'add_responsive_control',
            'prefix_class' => 'm-okthemes-posts%s--thumbnail-',
            'condition' => [
                'skin' => 'classic',
                'layout' => 'grid'
            ]
        ];

        $fields['show_image'] = [
            'label' => __( 'Show Image', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => [
                'skin' => 'cards'
            ]
        ];

        $fields['carousel'] = [
            'label' => __( 'Carousel', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'frontend_available' => true
        ];

        $fields['carousel_settings'] = [
            'label' => __( 'Carousel Settings', 'okthemes-toolkit' ),
            'type' => Controls_Manager::POPOVER_TOGGLE,
            'label_off' => __( 'Default', 'okthemes-toolkit' ),
            'label_on' => __( 'Custom', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'condition' => [
                'carousel' => 'yes'
            ]
        ];

        $fields['carousel_popover_start'] = [
            'custom_control' => 'start_popover'
        ];

        $fields['navigation'] = [
            'label' => __( 'Navigation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => __( 'None', 'okthemes-toolkit' ),
                'arrows-dots' => __( 'Arrows & Dots', 'okthemes-toolkit' ),
                'arrows' => __( 'Arrows', 'okthemes-toolkit' ),
                'dots' => __( 'Dots', 'okthemes-toolkit' ),
            ],
			'default' => 'arrows-dots',
            'frontend_available' => true,
            'condition' => [
                'carousel' => 'yes'
            ]
        ];
		
		$fields['infinite'] = [
            'label' => __( 'Infinite Loop', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => [
                'carousel' => 'yes'
            ]
		];
		
		$fields['pause_on_hover'] = [
            'label' => __( 'Pause on Hover', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'default' => 'yes',
            'condition' => [
                'carousel' => 'yes'
            ]
        ];

        $fields['centered_slides'] = [
            'label' => __( 'Centered Slides', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'default' => 'no',
            'condition' => [
                'carousel' => 'yes'
            ]
        ];

        $fields['keyboard_navigation'] = [
            'label' => __( 'Keyboard Navigation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'default' => 'yes',
            'condition' => [
                'carousel' => 'yes'
            ]
        ];
		
		$fields['autoplay'] = [
            'label' => __( 'Autoplay', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'frontend_available' => true,
            'default' => 'no',
            'condition' => [
                'carousel' => 'yes'
            ]
        ];

        $fields['autoplay_speed'] = [
            'label' => __( 'Auto Play Speed', 'okthemes-toolkit' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 5000,
            'step' => 15,
			'default' => 3000,
			'frontend_available' => true,
			'condition' => [
				'autoplay' => 'yes'
			]
        ];

		$fields['transition_speed'] = [
            'label' => __( 'Transition Speed', 'okthemes-toolkit' ) . ' (ms)',
            'type' => Controls_Manager::NUMBER,
            'default' => 500,
            'frontend_available' => true,
            'condition' => [
                'carousel' => 'yes'
            ]
        ];

        $fields['carousel_overflow'] = [
            'label' => __( 'Overflow', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'custom_control' => 'add_responsive_control',
            'options' => [
                'hidden' => __( 'Hidden', 'okthemes-toolkit' ),
                'visible' => __( 'Visible', 'okthemes-toolkit' ),
                'visible-right' => __( 'Visible Right', 'okthemes-toolkit' ),
                'visible-left' => __( 'Visible Left', 'okthemes-toolkit' ),
            ],
            'default' => 'hidden',
            'tablet_default' => 'hidden',
            'mobile_default' => 'hidden',
            'prefix_class' => 'okthemes-swiper-%s-overflow-',
            'condition' => [
                'carousel' => 'yes'
            ],
        ];

        $fields['carousel_popover_end'] = [
            'custom_control' => 'end_popover'
        ];

        $fields['layout'] = [
            'label' => __( 'Layout', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'grid' => __( 'Grid', 'okthemes-toolkit' ),
                'masonry' => __( 'Masonry', 'okthemes-toolkit' ),
                'metro' => __( 'Metro', 'okthemes-toolkit' ),
            ],
            'default' => 'grid',
            'condition' => [
                'columns!' => '0',
                'carousel!' => 'yes',
                'show_image!' => 'no',
                'image_position' => 'top'
            ],
            'frontend_available' => true,
            'render_type' => 'template',
            'prefix_class' => 'm-okthemes-posts--layout-'
        ];

        $fields['metro_columns_responsive'] = [
            'label' => __( 'Responsive Columns', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '1' => __( '1', 'okthemes-toolkit' ),
                '2' => __( '2', 'okthemes-toolkit' ),
                '3' => __( '3', 'okthemes-toolkit' ),
                '4' => __( '4', 'okthemes-toolkit' ),
                '5' => __( '5', 'okthemes-toolkit' ),
                '6' => __( '6', 'okthemes-toolkit' ),
            ],
            'default' => '2',
            'selectors_dictionary' => [
                '1' => '100%',
                '2' => '50%',
                '3' => '33.3333%',
                '4' => '25%',
                '5' => '50%',
                '6' => '16.666%',
            ],
            'selectors' => [
                '(mobile){{WRAPPER}} .l-okthemes-grid--metro .l-okthemes-grid__item' => 'max-width: {{VALUE}} !important; flex: 0 0 {{VALUE}}!important'
            ],
            'condition' => [
                'layout' => 'metro'
            ],
        ];

        $fields['image_size'] = [
            'label' => __( 'Image Size', 'okthemes-toolkit' ),
            'name' => 'image_size',
            'custom_key' => Group_Control_Image_Size::get_type(),
            'default' => 'large',
            'custom_control' => 'add_group_control',
            'condition' => [
                'show_image!' => 'no',
                'image_position!' => 'none'
            ],
        ];

        $fields['image_ratio'] = [
            'label' => __( 'Image Ratio', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0.1,
                    'max' => 2,
                    'step' => 0.01,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 0.66,
            ],
            'condition' => [
                'image_position!' => 'none',
                'layout!' => ['masonry', 'metro'],
                'show_image!' => 'no' 
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post .m-okthemes-post__thumbnail--link' => 'padding-bottom: calc( {{SIZE}} * 100% );',
            ],
            'custom_control' => 'add_responsive_control',
        ];

        $fields['image_width'] = [
            'label' => __( 'Image Width', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ '%', 'px', 'rem' ],
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 600,
                    'step' => 3,
                ],
            ],
            'default' => [
                'unit' => '%',
                'size' => 100,
            ],
            'condition' => [
                'image_position!' => 'none',
                'show_image!' => 'no'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post .m-okthemes-post__thumbnail--link' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'custom_control' => 'add_responsive_control',
        ];

        $fields['top_divider_title'] = [
            'type' => Controls_Manager::DIVIDER,
        ];

        // Title & Excerpt
        $fields['title'] = [
            'label' => __( 'Title', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes'
        ];

        $fields['title_html_tag'] = [
            'label' => __( 'HTML Tag', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'p' => 'p',
            ],
            'default' => 'h3',
            'condition' => [
                'title' => 'yes'
            ]
        ];

        $fields['excerpt'] = [
            'label' => __( 'Excerpt', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes'
        ];

        $fields['excerpt_length'] = [
            'label' => __( 'Excerpt Length', 'okthemes-toolkit' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 100,
            'step' => 1,
            'default' => 25,
            'condition' => [
                'excerpt' => 'yes'
            ]
        ];

        // Meta Data 
        $fields['bottom_divider_title'] = [
            'type' => Controls_Manager::DIVIDER,
        ];

        $meta_data_options = [
            'author' => __( 'Author', 'okthemes-toolkit' ),
            'date' => __( 'Date', 'okthemes-toolkit' ),
            'time' => __( 'Time', 'okthemes-toolkit' ),
            'comments' => __( 'Comments', 'okthemes-toolkit' ),
            'terms' => __( 'Terms', 'okthemes-toolkit' ),
        ];

        $meta_data_options = apply_filters( 'okthemes/posts/meta_data/options', $meta_data_options );

        $fields['meta_data'] = [
            'label' => __( 'Meta Data', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'options' => $meta_data_options,
            'default' => [ 'date', 'comments' ],
        ];

        $fields['separator_between'] = [
            'label' => __( 'Separator Between', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => '/',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
            ],
            'condition' => [
                'meta_data!' => []
            ]
        ];

        $fields['meta_data_taxonomy'] = [
            'label' => __('Taxonomy', 'okthemes-toolkit'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'default' => [],
            'options' => $this->get_taxonomies(),
            'condition' => [
                'meta_data' => 'terms',
            ],
        ];

        $fields['taxonomy_count'] = [
            'label' => __( 'Taxonomy Count', 'okthemes-toolkit' ),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 1,
            'condition' => [
                'meta_data' => 'terms'
            ]
        ];

        $fields['show_read_more'] = [
            'label' => __( 'Read More', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'default' => 'no',
            'separator' => 'before',
        ];

        $fields['read_more_text'] = [
            'label' => __( 'Read More Text', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Read More', 'okthemes-toolkit' ),
            'condition' => [
                'show_read_more' => 'yes',
            ],
        ];

        $fields['read_more_icon'] = [
           'label' => __( 'Read More Icon', 'okthemes-toolkit' ),
            'type' => Controls_Manager::ICONS,
            'default' => [],
            'condition' => [
                'show_read_more' => 'yes',
            ],
        ];

        $fields['read_more_icon_position'] = [
            'label' => __( 'Icon Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'before' => __('Before', 'okthemes-toolkit'),
                'after' => __('After', 'okthemes-toolkit'),
            ],
            'default' => 'after',
            'condition' => [
                'show_read_more' => 'yes',
                'read_more_icon[value]!' => '',
            ],
            'prefix_class' => 'm-okthemes-post__read-more--icon-'
        ];

        $fields['read_more_icon_spacing'] = [
            'label' => __('Icon Spacing', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'show_read_more' => 'yes',
                'read_more_icon[value]!' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10
            ],
            'selectors' => [
                '{{WRAPPER}}.m-okthemes-post__read-more--icon-before .m-okthemes-post__read-more--icon' => 'margin-right: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}}.m-okthemes-post__read-more--icon-after .m-okthemes-post__read-more--icon' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['bottom_divider_meta_tag'] = [
            'type' => Controls_Manager::DIVIDER,
            'condition' => [
                'skin' => 'cards'
            ]
        ];

        // Badge
        $fields['badge'] = [
            'label' => __( 'Badge', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => [
                'skin' => 'cards'
            ],
        ];

        if ( class_exists( 'WooCommerce' ) ) {
            $fields['badge_taxonomy'] = [
                'label' => __( 'Badge Taxonomy', 'okthemes-toolkit' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_taxonomies(),
                'default' => 'category',
                'condition' => [
                    'skin' => 'cards',
                    'badge' => 'yes',
                ]
            ];
        }


        // Avatar
        $fields['avatar'] = [
            'label' => __( 'Avatar', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => [
                'skin' => 'cards',
                'show_image!' => 'no',
            ],
            'separator' => 'before'
        ];

        return $fields;
    }

    public function query_controls() {
        $fields = [];

        $fields['source'] = [
            'label' => __( 'Source', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_source(),
            'default' => 'post'
        ];

        $fields['source_tabs'] = [
            'custom_control' => 'start_controls_tabs',
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        // Include
        $fields['include_tab'] = [
            'label' => __( 'Include', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab',
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        $fields['include_by'] = [
            'label' => __( 'Include By', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'default' => [],
            'options' => [
                'term' => __( 'Term', 'okthemes-toolkit' ),
                'author' => __( 'Author', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        // Post Types
        $post_types = Utils::get_public_post_types();

        if ( $post_types ) {
            foreach ( $post_types as $id => $value ) {

                $fields['include_term_' . $id ] = [
                    'label' => __( 'Term', 'okthemes-toolkit' ),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => Utils::get_the_terms( $id ),
                    'label_block' => true,
                    'default' => [],
                    'select2options' => [
                        'placeholder' => __( 'All', 'okthemes-toolkit' ),
                    ],
                    'condition' => [
                        'include_by' => 'term',
                        'source' => $id
                    ],
                ];

            }
        }

        $fields['include_term_related'] = [
            'label' => __( 'Term', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_supported_taxonomies(),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'include_by' => 'term',
                'source' => 'related'
            ],
        ];

        $fields['include_author'] = [
            'label' => __( 'Author', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_authors(),
            'label_block' => true,
            'default' => [''],
            'select2options' => [
                'placeholder' => __( 'All', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'include_by' => 'author'
            ],
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Exclude
        $fields['exclude_tab'] = [
            'label' => __( 'Exclude', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab',
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        $fields['exclude_by'] = [
            'label' => __( 'Exclude By', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'default' => [],
            'options' => [
                'current-post' => __( 'Current Post', 'okthemes-toolkit' ),
                'manual-selection' => __( 'Manual Selection', 'okthemes-toolkit' ),
                'term' => __( 'Term', 'okthemes-toolkit' ),
                'author' => __( 'Author', 'okthemes-toolkit' ),
            ],
        ];

        $fields['exclude_manual'] = [
            'label' => __( 'Manual Selection', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_posts( ['all'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'exclude_by' => 'manual-selection'
            ],
        ];

        if ( $post_types ) {
            foreach ( $post_types as $id => $value ) {

                $fields['exclude_term_' . $id ] = [
                    'label' => __( 'Term', 'okthemes-toolkit' ),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => Utils::get_the_terms( $id ),
                    'label_block' => true,
                    'default' => [],
                    'select2options' => [
                        'placeholder' => __( 'All', 'okthemes-toolkit' ),
                    ],
                    'condition' => [
                        'exclude_by' => 'term',
                        'source' => $id
                    ],
                ];
            }
        }

        $fields['exclude_author'] = [
            'label' => __( 'Author', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_authors(),
            'label_block' => true,
            'default' => [''],
            'select2options' => [
                'placeholder' => __( 'All', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'exclude_by' => 'author'
            ],
        ];

        $fields['query_offset'] = [
            'label' => __( 'Offset (Skip any Post)', 'okthemes-toolkit' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'default' => '',
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tabs'
        ];

        $fields['fallback'] = [
            'label' => __( 'Fallback', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'description' => __( 'Displayed if no relevant results are found. Manual selection display order is random.', 'okthemes-toolkit' ),
            'options' => [
                'none' => __( 'None', 'okthemes-toolkit' ),
                'manual-selection' => __( 'Manual Selection', 'okthemes-toolkit' ),
                'recent-posts' => __( 'Recent Posts', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'source' => 'related'
            ],
            'default' => 'none',
            'separator' => 'before'
        ];

        $fields['search_select'] = [
            'label' => __( 'Search & Select', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_posts( ['all'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'source' => 'manual-selection',
            ],
        ];

        $fields['search_select_fallback'] = [
            'label' => __( 'Search & Select', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_posts( ['all'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'source' => 'related',
                'fallback' => 'manual-selection',
            ],
        ];

        $fields['hr_date'] = [
            'type' => Controls_Manager::DIVIDER,
        ];

        $fields['date_order'] = [
            'label' => __( 'Date', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'all' => __( 'All', 'okthemes-toolkit' ),
                'past-day' => __( 'Past Day', 'okthemes-toolkit' ),
                'past-week' => __( 'Past Week', 'okthemes-toolkit' ),
                'past-month' => __( 'Past Month', 'okthemes-toolkit' ),
                'past-year' => __( 'Past Year', 'okthemes-toolkit' ),
                'custom' => __( 'Custom', 'okthemes-toolkit' ),
            ],
            'default' => 'all',
        ];

        $fields['date_before'] = [
            'label' => __( 'Before', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => false,
            'condition' => [
                'date_order' => 'custom',
            ]
        ];

        $fields['date_after'] = [
            'label' => __( 'After', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DATE_TIME,
            'label_block' => false,
            'condition' => [
                'date_order' => 'custom',
            ]
        ];

        $fields['orderby'] = [
            'label' => __( 'Order By', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'post_date' => __( 'Date', 'okthemes-toolkit' ),
                'post_title' => __( 'Title', 'okthemes-toolkit' ),
                'menu_order' => __( 'Menu Order', 'okthemes-toolkit' ),
                'rand' => __( 'Random', 'okthemes-toolkit' ),
            ],
            'default' => 'post_date',
        ];

        $fields['order'] = [
            'label' => __( 'Order', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => __( 'Ascending', 'okthemes-toolkit' ),
                'desc' => __( 'Descending', 'okthemes-toolkit' ),
            ],
            'default' => 'desc',
        ];

        $fields['ignore_sticky'] = [
            'label' => __( 'Ignore Sticky Posts', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => [
                'source' => 'post'
            ]
        ];

        return $fields;
    }

    public function query_metro_controls() {

        $columns = [
            '1' => '1' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '2' => '2' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '3' => '3' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '4' => '4' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '5' => '5' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '6' => '6' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '7' => '7' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '8' => '8' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '9' => '9' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '10' => '10' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '11' => '11' . ' ' . __( 'Column', 'okthemes-toolkit' ),
            '12' => '12' . ' ' . __( 'Column', 'okthemes-toolkit' ),
        ];

        $selectors_dictionary = [
            '1' => '8.33',
            '2' => '16.66',
            '3' => '25',
            '4' => '33.33',
            '5' => '41.66',
            '6' => '50',
            '7' => '58.33',
            '8' => '66.67',
            '9' => '75',
            '10' => '83.33',
            '11' => '91.66',
            '12' => '100',
        ];

        $repeater = new Repeater();

		$repeater->add_control(
			'post', [
                'type' => Controls_Manager::TEXT,
                'show_label' => false,
                'label_block' => true,
			]
		);

		$repeater->add_responsive_control(
			'column', [
                'show_label' => false,
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
                'options' => $columns,
                'default' => '3',
                'selectors_dictionary' => $selectors_dictionary,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:not(.swiper-slide)' => 'flex: 0 0 {{VALUE}}%; max-width: {{VALUE}}%;',
                ],
                'render_type' => 'template',
 			]
        );

         $fields['okthemes_metro_reset'] = [
            'label' => __( 'Reset Metro', 'okthemes-toolkit' ),
            'type' => Controls_Manager::BUTTON,
            'button_type' => 'success okthemes-reset-metro',
            'text' => __( 'Reset', 'okthemes-toolkit' ),
            'event' => 'okthemes:editor:metro:reset',
            'separator' => 'after'
        ];

        $fields['okthemes_metro'] = [
            'label' => __( 'Metro', 'okthemes-toolkit' ),
            'show_label' => false,
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [],
            'title_field' => '{{{ post }}}',
            'item_actions' => [
				'add' => false,
				'duplicate' => false,
				'remove' => false,
				'sort' => false,
			],
        ];

        return $fields;
    }

    public function pagination_controls() {
        $fields = [];

        $fields['pagination'] = [
            'label' => __( 'Pagination', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => __( 'None', 'okthemes-toolkit' ),
                'numbers' => __( 'Numbers', 'okthemes-toolkit' ),
                'show-more' => __( 'Show More', 'okthemes-toolkit' ),
                'previous-next' => __( 'Previous/Next', 'okthemes-toolkit' ),
                'numbers-previous-next' => __( 'Numbers + Previous/Next', 'okthemes-toolkit' ),
            ],
            'frontend_available' => true,
            'default' => 'none'
        ];

        $fields['page_limit'] = [
            'label' => __( 'Page Limit', 'okthemes-toolkit' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 40,
            'step' => 1,
            'default' => 5,
            'condition' => [
                'pagination' => ['numbers', 'numbers-previous-next']
            ],
        ];

        $fields['show_more_text'] = [
            'label' => __( 'Show More Text', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Show More', 'okthemes-toolkit' ),
            'condition' => [
                'pagination' => 'show-more'
            ]
        ];
        
        $fields['show_more_loading_text'] = [
            'label' => __( 'Loading Text', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Loading...', 'okthemes-toolkit' ),
            'condition' => [
                'pagination' => 'show-more'
            ]
        ];

        $fields['shorten'] = [
            'label' => __( 'Shorten', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => [
                'pagination' => ['numbers', 'numbers-previous-next']
            ]
        ];

        $fields['previous_label'] = [
            'label' => __( 'Previous Label', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Previous', 'okthemes-toolkit' ),
            'condition' => [
                'pagination' => ['previous-next', 'numbers-previous-next']
            ]
        ];

        $fields['next_label'] = [
            'label' => __( 'Next Label', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Next', 'okthemes-toolkit' ),
            'condition' => [
                'pagination' => ['previous-next', 'numbers-previous-next']
            ],
        ];

        $fields['label_icon'] = [
            'label' => __( 'Label Icon', 'okthemes-toolkit' ),
            'type' => Controls_Manager::ICONS,
            'default' => [],
            'condition' => [
               'pagination' => ['previous-next', 'numbers-previous-next']
            ],
        ];

        $fields['label_icon_spacing'] = [
            'label' => __('Icon Spacing', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'condition' => [
                'pagination' => ['previous-next', 'numbers-previous-next'],
                'label_icon[value]!' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .prev .m-okthemes-pagination--icon' => 'margin-right: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .m-okthemes-pagination .next .m-okthemes-pagination--icon' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['label_icon_size'] = [
            'label' => __('Icon Size', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'condition' => [
                'pagination' => ['previous-next', 'numbers-previous-next'],
                'label_icon[value]!' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .m-okthemes-pagination--icon' => 'font-size: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['pagination_alignment'] = [
            'label' => __('Alignment', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-right',
                ],
            ],
            'condition' => [
                'pagination!' => 'none'
            ],
            'selectors_dictionary' => [
                'left' => 'flex-start',
                'center' => 'center',
                'right' => 'flex-end'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination' => 'justify-content: {{VALUE}}',
            ],
        ];

        $fields['pagination_spacing'] = [
            'label' => __('Spacing', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
            'default' => [
                'size' => 30,
                'unit' => 'px'
            ],
            'condition' => [
                'pagination!' => 'none'
            ],
        ];

        return $fields;
    }

    public function filters_controls() {

        $fields['filters'] = [
            'label' => __( 'Filters', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
        ];

        $fields['filters_tax'] = [
            'label' => __( 'Taxonomy', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_taxonomies(),
            'default' => 'category',
            'condition' => [
                'filters' => 'yes',
                'source!' => 'manual-selection',
            ]
        ];

        $fields['filter_all'] = [
            'label' => __( 'Filter All', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => [
                'filters' => 'yes',
            ]
        ];

        $fields['filter_all_string'] = [
            'label' => __( 'String', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'All', 'okthemes-toolkit' ),
            'condition' => [
                'filters' => 'yes',
                'filter_all' => 'yes',
            ]
        ];

        return $fields;
    }

    public function layout_style_controls() {
        $fields = [];

        $fields['columns_gap'] = [
            'label' => __( 'Columns Gap', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'rem', '%'],
            'custom_control' => 'add_responsive_control',
            'default' => [
                'unit' => 'px',
                'size' => 30,
            ],
            'condition' => [
                'carousel!' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}} .l-okthemes-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
                '{{WRAPPER}} .l-okthemes-grid__item' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2)',
            ]
        ];

        $fields['space_between'] = [
            'label' => __( 'Space Between', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',            
            'range' => [
                'px' => [
                    'max' => 50,
                ],
            ],
            'desktop_default' => [
                'size' => 30,
            ],
            'tablet_default' => [
                'size' => 10,
            ],
            'mobile_default' => [
                'size' => 10,
            ],
            'frontend_available' => true,
            'render_type' => 'template',
            'condition' => [
                'carousel' => 'yes',
            ],
        ];

        $fields['row_gap'] = [
            'label' => __( 'Row Gap', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'rem', '%'],
            'custom_control' => 'add_responsive_control',
            'default' => [
                'unit' => 'px',
                'size' => 35,
            ],
            'condition' => [
                'carousel!' => 'yes',
            ],
            'frontend_available' => true,
            'selectors' => [
                '{{WRAPPER}} .l-okthemes-grid' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .l-okthemes-grid__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ];

        $fields['animation'] = [
            'label' => __( 'Initial Animation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::POPOVER_TOGGLE,
            'frontend_available' => true,
            'render_type' => 'none',
            'default' => 'yes'
        ];

        $fields['animation_popover_start'] = [
            'custom_control' => 'start_popover'
        ];

        $fields['okthemes_animations'] = [
            'label' => __( 'Entrance Animation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::ANIMATION,
            'custom_control' => 'add_responsive_control',
            'frontend_available' => true,
            'default' => 'h-okthemes-animation--slideUp',
            'render_type' => 'template'
        ];

        $fields['okthemes_animations_duration'] = [
            'label' => __( 'Animation Duration', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'animated',
            'options' => [
                'animated-slow' => __( 'Slow', 'okthemes-toolkit' ),
                'animated' => __( 'Normal', 'okthemes-toolkit' ),
                'animated-fast' => __( 'Fast', 'okthemes-toolkit' ),
            ],
            'frontend_available' => true,
            'custom_control' => 'add_responsive_control',
            'condition' => [
                'okthemes_animations!' => [
                    '',
                    'none',
                    'h-okthemes-animation--specialOne', 
                    'h-okthemes-animation--clipFromLeft', 
                    'h-okthemes-animation--clipFromRight', 
                    'h-okthemes-animation--clipUp', 
                    'h-okthemes-animation--clipBottom'
                ], 
            ],
        ];

        $fields['animation_delay'] = [
            'label' => __( 'Animation Delay', 'okthemes-toolkit' ) . ' ' . '(ms)',
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10000,
            'step' => 100,
            'default' => 0,
            'frontend_available' => true,
            'render_type' => 'none',
            'custom_control' => 'add_responsive_control',
            'condition' => [
                'okthemes_animations!' => '',
            ],
        ];

        $fields['animation_delay_reset'] = [
            'label' => __( 'Animation Delay Reset', 'okthemes-toolkit' ) . ' ' . '(ms)',
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 10000,
            'step' => 100,
            'default' => 1000,
            'custom_control' => 'add_responsive_control',
            'condition' => [
                'okthemes_animations!' => '',
                'animation_delay!' => 0,
            ],
            'frontend_available' => true,
            'render_type' => 'UI'
        ];

        $fields['animation_popover_end'] = [
            'custom_control' => 'end_popover'
        ];

        $fields['alignment'] = [
            'label' => __('Alignment', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post' => 'text-align: {{VALUE}}' 
            ],
        ];

        $fields['spacing_offset'] = [
            'label' => __( 'Offset', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'custom_control' => 'add_responsive_control',
            'prefix_class' => 'l-okthemes-grid-wrapper%s--offset-',
            'render_type' => 'template',
            'condition' => [
                'carousel!' => 'yes',
                'layout!' => 'grid'
            ]
        ];

        return $fields;
    }

    public function box_style_controls() {
        $fields = [];

        $fields['border_width'] = [
            'label' => __( 'Border Width', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
            ],
        ];

        $fields['border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner' => 'border-radius: {{SIZE}}{{UNIT}}',
            ]
        ];

        $fields['padding'] = [
            'label' => __( 'Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['content_inner_padding'] = [
            'label' => __( 'Content Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                'image_position' => 'none',
                'allow_content_order' => 'yes'
            ]
        ];

        $fields['content_padding'] = [
            'label' => __( 'Content Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                'image_position!' => 'none',
                'allow_content_order!' => 'yes'
            ]
        ];
        
        $fields['content_margin'] = [
            'label' => __( 'Content Margin', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                'image_position!' => 'none',
                'allow_content_order!' => 'yes'
            ]
        ];

        $fields['hr_box'] = [
            'type' => Controls_Manager::DIVIDER,
        ];
        
        $fields['box_tabs'] = [
            'custom_control' => 'start_controls_tabs'
        ];

        // Normal
        $fields['box_normal_tab'] = [
            'label' => __( 'Normal', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['box_shadow'] = [
            'label' => __( 'Box Shadow', 'okthemes-toolkit' ),
            'name' => 'box_shadow',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__inner'
        ];

        $fields['box_background_color'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['content_background_color'] = [
            'label' => __( 'Content Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__text' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['box_border_color'] = [
            'label' => __( 'Border Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner' => 'border-color: {{VALUE}}',
            ],
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Hover
        $fields['box_hover_tab'] = [
            'label' => __( 'Hover', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['box_shadow_hover'] = [
            'label' => __( 'Box Shadow', 'okthemes-toolkit' ),
            'name' => 'box_shadow_hover',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__inner:hover'
        ];

        $fields['box_background_color_hover'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner:hover' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['box_border_color_hover'] = [
            'label' => __( 'Border Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner:hover' => 'border-color: {{VALUE}}',
            ],
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tabs'
        ];

        return $fields;
    }

    public function image_style_controls() {
        $fields = [];

        $fields['image_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                'skin!' => 'cards'
            ]
        ];

        $fields['image_spacing_classic'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__thumbnail--link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'skin!' => 'cards'
            ]
        ];

        $fields['image_spacing_card'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__text' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'skin' => 'cards'
            ]
        ];

        $fields['image_tabs'] = [
            'custom_control' => 'start_controls_tabs'
        ];

        // Normal
        $fields['image_normal_tab'] = [
            'label' => __( 'Normal', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['image_css_filters'] = [
            'label' => __( 'CSS Filters', 'okthemes-toolkit' ),
            'name' => 'box_shadow_hover',
            'custom_key' => Group_Control_Css_Filter::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__thumbnail img',
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Hover
        $fields['image_hover_tab'] = [
            'label' => __( 'Hover', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['image_hover_secondary'] = [
			'label' => __( 'Hover Gallery Image', 'okthemes-toolkit' ),
            'description' => __( 'Show the first image of product gallery.', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'condition' => [
                'source' => 'woocommerce'
            ]
		];

        $fields['image_css_filters_hover'] = [
            'label' => __( 'CSS Filters', 'okthemes-toolkit' ),
            'name' => 'image_css_filters_hover',
            'custom_key' => Group_Control_Css_Filter::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__thumbnail:hover img',
            'condition' => [
                'image_hover_secondary!' => 'yes'
            ]
        ];

        $fields['image_animation_hover'] = [
            'label' => __( 'Animation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => Utils::get_image_hover_animations(),
            'default' => '',
            'frontend_available' => true
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tabs'
        ];

        $fields['badge_heading'] = [
            'label' => __( 'Badge', 'okthemes-toolkit' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ]
        ];

        $fields['badge_position'] = [
            'label' => __('Position', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'right',
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'eicon-h-align-left',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__badge' => '{{VALUE}}: 0',
            ],
        ];

        $fields['badge_bg_color'] = [
            'label' => __('Background Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__badge' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['badge_text_color'] = [
            'label' => __('Text Color', 'okthemes-toolkit'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__badge' => 'color: {{VALUE}}',
            ],
        ];

        $fields['badge_border_radius'] = [
            'label' => __('Border Radius', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__badge' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['badge_size'] = [
            'label' => __('Size', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__badge' => 'font-size: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['badge_margin'] = [
            'label' => __('Margin', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__badge' => 'margin: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['badge_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'badge_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'exclude' => [ 'font_size', 'line-height' ],
            'selector' => '{{WRAPPER}} .m-okthemes-post__badge',
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
        ];

        $fields['heading_avatar_style'] = [
            'label' => __( 'Avatar', 'okthemes-toolkit' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'show_image' => 'yes',
                'avatar' => 'yes'
            ],
        ];

        $fields['avatar_size'] = [
            'label' => __('Size', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'condition' => [
                'skin' => 'cards',
                'badge' => 'yes'
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 80,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__avatar' => 'top: calc(-{{SIZE}}{{UNIT}} / 2);',
                '{{WRAPPER}} .m-okthemes-post__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .m-okthemes-post__thumbnail--link' => 'margin-bottom: calc({{SIZE}}{{UNIT}} / 2)',
            ],
            'condition' => [
                'avatar' => 'yes'
            ],
        ];

        return $fields;
    }

    public function cards_style_controls() {

        $fields['cards_background_color'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner--card' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['cards_border_color'] = [
            'label' => __( 'Border Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner--card' => 'border-color: {{VALUE}}',
            ],
        ];

        $fields['cards_border_width'] = [
            'label' => __( 'Border Width', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner--card' => 'border-width: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['cards_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'size_units' => ['px', '%'],
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner--card' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['cards_horizontal_padding'] = [
            'label' => __( 'Horizontal Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__text' => 'padding: 0 {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .m-okthemes-post__meta-data' => 'padding: 10px {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .m-okthemes-post__avatar' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}}',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
        ];

        $fields['cards_vertical_padding'] = [
            'label' => __( 'Vertical Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__inner--card' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
        ];

        $fields['cards_box_shadow'] = [
            'label' => __( 'Box Shadow', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'prefix_class' => 'okthemes-card-shadow-',
            'default' => 'yes',
        ];

        $fields['cards_hover_effect'] = [
            'label' => __( 'Hover Effect', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => __( 'None', 'okthemes-toolkit' ),
                'gradient' => __( 'Gradient', 'okthemes-toolkit' ),
            ],
            'default' => 'gradient',
            'separator' => 'before'
        ];

        $fields['cards_meta_border_color'] = [
            'label' => __( 'Meta Border Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__meta-data' => 'border-top-color: {{VALUE}}',
            ],
            'separator' => 'before'
        ];
        
        return $fields;
    }
    
    public function content_style_controls() {
        $fields = [];
        
        // Title
        $fields['content_title_heading'] = [
            'label' => __('Title', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
        ];

        $fields['content_title_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__title a' => 'color: {{VALUE}}'
            ],
            'global' => [
                'default' => Global_Colors::COLOR_SECONDARY,
            ],
        ];

        $fields['content_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'content_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__title',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
        ];

        $fields['content_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Meta
        $fields['meta_title_heading'] = [
            'label' => __('Meta', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['meta_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__meta-data span' => 'color: {{VALUE}}',
                '{{WRAPPER}} .m-okthemes-post__meta-data span a' => 'color: {{VALUE}}'
            ],
        ];

        $fields['meta_separator_color'] = [
            'label' => __( 'Separator Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__meta-data span:before' => 'color: {{VALUE}}'
            ],
        ];

        $fields['meta_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'meta_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__meta-data span, {{WRAPPER}} .m-okthemes-post__meta-data a',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
            ],
        ];

        $fields['meta_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Excerpt
        $fields['excerpt_title_heading'] = [
            'label' => __('Excerpt', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['excerpt_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__excerpt' => 'color: {{VALUE}}'
            ],
            'global' => [
                'default' => Global_Colors::COLOR_TEXT,
            ],
        ];

        $fields['excerpt_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'excerpt_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__excerpt',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_TEXT,
            ],
        ];

        $fields['excerpt_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Read More
        $fields['read_more_title_heading'] = [
            'label' => __('Read More', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['read_more_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__read-more' => 'color: {{VALUE}}'
            ],
            'global' => [
                'default' => Global_Colors::COLOR_ACCENT,
            ],
        ];

        $fields['read_more_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'read_more_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-post__read-more',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
            ],
        ];

        $fields['read_more_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-post__read-more' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ]
        ];

        return $fields;
    }

    public function content_order_controls() {
        $fields = [];

        $fields['allow_content_order'] = [
            'label' => __( 'Allow Ordering', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => [
                'skin' => 'classic'
            ]
        ];

        $repeater = new Repeater();

		$repeater->add_control(
			'type', [
                'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'title' => __( 'Title', 'okthemes-toolkit' ),
                    'meta-data' => __( 'Meta Data', 'okthemes-toolkit' ),
                    'excerpt' => __( 'Excerpt', 'okthemes-toolkit' ),
                    'thumbnail' => __( 'Thumbnail', 'okthemes-toolkit' ),
                    'read-more' => __( 'Read More', 'okthemes-toolkit' ),
                ]
			]
        );

        $fields['content_order'] = [
            'type' => Controls_Manager::REPEATER,
            'show_label' => false,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'type' => 'thumbnail' ],
                [ 'type' => 'title' ],
                [ 'type' => 'meta-data' ],
                [ 'type' => 'excerpt' ],
                [ 'type' => 'read-more' ],
            ],
            'condition' => [
                'allow_content_order' => 'yes',
                'image_position!' => [ 'left', 'right' ]
            ],
            'title_field' => '<span> {{{ okthemes.helpers.marionetteTitle(type) }}} </span>',
            'item_actions' => [
				'add' => false,
				'duplicate' => false,
				'remove' => false,
				'sort' => true,
			],
        ];

        $fields['content_order_image'] = [
            'type' => Controls_Manager::REPEATER,
            'show_label' => false,
            'fields' => $repeater->get_controls(),
            'default' => [
                [ 'type' => 'title' ],
                [ 'type' => 'meta-data' ],
                [ 'type' => 'excerpt' ],
                [ 'type' => 'read-more' ],
            ],
            'condition' => [
                'allow_content_order' => 'yes',
                'image_position' => [ 'left', 'right' ]
            ],
            'title_field' => '<span> {{{ okthemes.helpers.marionetteTitle(type) }}} </span>',
            'item_actions' => [
				'add' => false,
				'duplicate' => false,
				'remove' => false,
				'sort' => true,
			],
        ];

        return $fields;
    }

    public function pagination_style_controls() {
        $fields = [];

        $fields['pagination_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'pagination_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
            ],
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-pagination .page-numbers, {{WRAPPER}} .m-okthemes-pagination',
            'separator' => 'after'
        ];

        $fields['pagination_pointer'] = [
            'label' => __( 'Pointer', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __( 'None', 'okthemes-toolkit' ),
                'underline' => __( 'Underline', 'okthemes-toolkit' ),
                'strikethrough' => __( 'Strikethrough', 'okthemes-toolkit' ),
                'diagonal' => __( 'Diagonal', 'okthemes-toolkit' ),
			],
			'prefix_class' => 'm-okthemes-pagination--',
            'default' => '',
            'condition' => [
                'pagination!' => 'show-more'
            ]  
        ];

        $fields['pagination_color_tabs'] = [
            'custom_control' => 'start_controls_tabs'
        ];

        // Normal
        $fields['pagination_normal_tab'] = [
            'label' => __( 'Normal', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['pagination_text_color'] = [
            'label' => __( 'Text Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers' => 'color: {{VALUE}}'
            ],
        ];

        $fields['pagination_background_color'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:not(.page-label)' => 'background-color: {{VALUE}}'
            ],
        ];

        $fields['pagination_label_color'] = [
            'label' => __( 'Label Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-label' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'pagination!' => 'show-more'
            ]
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Hover
        $fields['pagination_hover_tab'] = [
            'label' => __( 'Hover', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['pagination_text_color_hover'] = [
            'label' => __( 'Text Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:hover' => 'color: {{VALUE}}'
            ],
        ];

        $fields['pagination_background_color_hover'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:not(.page-label):hover' => 'background-color: {{VALUE}}'
            ],
        ];

        $fields['pagination_label_color_hover'] = [
            'label' => __( 'Label Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-label:hover' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'pagination!' => 'show-more'
            ]
        ];

        $fields['pagination_pointer_color_hover'] = [
            'label' => __( 'Pointer Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:not(.page-label):hover:after' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                'pagination!' => 'show-more',
                'pagination_pointer!' => ''
            ]
        ];

        $fields['pagination_hover_animation'] = [
            'label' => __( 'Hover Animation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::HOVER_ANIMATION,
            'condition' => [
                'pagination' => 'show-more'
            ]
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        // Active
        $fields['pagination_active_tab'] = [
            'label' => __( 'Active', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab',
            'condition' => [
                'pagination!' => 'show-more'
            ]
        ];

        $fields['pagination_text_color_active'] = [
            'label' => __( 'Text Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .current' => 'color: {{VALUE}}'
            ],
        ];

        $fields['pagination_background_color_active'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .current:not(.page-label)' => 'background-color: {{VALUE}}'
            ],
            'condition' => [
                'pagination!' => 'show-more'
            ]
        ];

        $fields['pagination_pointer_color_active'] = [
            'label' => __( 'Pointer Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .current:not(.page-label):after' => 'background-color: {{VALUE}}'
            ],
            'condition' => [
                'pagination!' => 'show-more',
                'pagination_pointer!' => ''
            ]
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tab'
        ];

        $fields[] = [
            'custom_control' => 'end_controls_tabs'
        ];

        $fields['pagination_border'] = [
            'label' => __( 'Border', 'okthemes-toolkit' ),
            'name' => 'pagination_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-pagination button',
            'separator' => 'before',
            'condition' => [
                'pagination' => 'show-more'
            ]
        ];

        $fields['pagination_top_hr'] = [
            'type' => Controls_Manager::DIVIDER,
            'condition' => [
                'pagination' => 'show-more',
            ]
        ];

        $fields['pagination_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'size_units' => ['px', '%'],
            'type' => Controls_Manager::DIMENSIONS,
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:not(.page-label)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['pagination_box_shadow'] = [
            'label' => __( 'Box Shadow', 'okthemes-toolkit' ),
            'name' => 'pagination_box_shadow',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'selector' => '{{WRAPPER}} .m-okthemes-pagination button',
            'custom_control' => 'add_group_control',
            'condition' => [
                'pagination' => 'show-more',
            ]
        ];

        $fields['pagination_bottom_hr'] = [
            'type' => Controls_Manager::DIVIDER,
        ];

        $fields['pagination_space_between'] = [
            'label' => __( 'Space Between', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'default' => [
                'unit' => 'px',
                'size' => 10,
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
            ],
            'condition' => [
                'pagination!' => 'show-more'
            ]
        ];

        $fields['pagination_padding'] = [
            'label' => __( 'Padding', 'okthemes-toolkit' ),
            'size_units' => ['px', 'em', '%'],
            'type' => Controls_Manager::DIMENSIONS,
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                '{{WRAPPER}} .m-okthemes-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        return $fields;
    }

    public function navigation_style_controls() {
        $fields = [];

        $fields['arrows_heading'] = [
            'label' => __('Arrows', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'navigation' => ['arrows', 'arrows-dots']
            ]
        ];

        $fields['arrows_icon'] = [
            'label' => __( 'Icon', 'okthemes-toolkit' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-chevron-left',
                'library' => 'fa-solid',
            ],
            'fa4compatibility' => 'icon',
            'recommended' => [
                'fa-solid' => [
                    'chevron-left',
                    'caret-left',
                    'arrow-left',
                    'angle-left',
                    'chevron-circle-left',
                    'caret-square-left',
                    'arrow-circle-left',
                    'angle-double-left',
                    'long-arrow-alt-left',
                    'arrow-alt-circle-left',
                    'hand-point-left',
                ]
            ],
            'condition' => [
                'navigation' => ['arrows', 'arrows-dots']
            ]
        ];

        $fields['arrows_size'] = [
            'custom_control' => 'add_responsive_control',
            'label' => __( 'Size', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .okthemes-icon' => 'font-size: {{SIZE}}{{UNIT}}'
            ],
            'default' => [
                'size' => 30,
                'unit' => 'px'
            ],
			'condition' => [
                'arrows_icon[value]!' => '',
                'navigation' => ['arrows', 'arrows-dots']
            ]
        ];

        $fields['arrows_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-button .okthemes-icon' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'navigation' => ['arrows', 'arrows-dots']
            ]
        ];

        $fields['arrows_position'] = [
           'label' => __( 'Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'arrows_icon[value]!' => '',
                'navigation' => ['arrows', 'arrows-dots']
            ]
        ];

        $fields[] = [
            'custom_control' => 'start_popover'
        ];

        $fields['arrows_left_heading'] = [
            'label' => __( 'Left Arrow', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'arrows_position' => 'yes'
            ]
        ];

        $fields['okthemes_left_arrow_alignment'] = [
            'label' => __( 'Alignment', 'okthemes-toolkit' ),
            'type' => Controls_Manager::CHOOSE,
            'custom_control' => 'add_responsive_control',
            'options' => [
                'top-left' => [
                    'title' => __( 'Top Left', 'okthemes-toolkit' ),
                ],
                'top-center' => [
                    'title' => __( 'Top Center', 'okthemes-toolkit' ),
                ],
                'top-right' => [
                    'title' => __( 'Top Right', 'okthemes-toolkit' ),
                ],
                'center-left' => [
                    'title' => __( 'Center Left', 'okthemes-toolkit' ),
                ],
                'center' => [
                    'title' => __( 'Center Center', 'okthemes-toolkit' ),
                ],
                'center-right' => [
                    'title' => __( 'Center Right', 'okthemes-toolkit' ),
                ],
                'bottom-left' => [
                    'title' => __( 'Bottom Left', 'okthemes-toolkit' ),
                ],
                'bottom-center' => [
                    'title' => __( 'Bottom Center', 'okthemes-toolkit' ),
                ],
                'bottom-right' => [
                    'title' => __( 'Bottom Right', 'okthemes-toolkit' ),
                ],
            ],
            'selectors_dictionary' => Utils::get_custom_position_selectors(),
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-button--prev' => '{{VALUE}}'
            ],
            'default' => 'center-left',
            'toggle' => false,
        ];

        $fields['left_arrow_x_position'] = [
            'label' => __( 'X Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 3,
                ],
            ],
            'selectors' => [
				'{{WRAPPER}} .okthemes-swiper-button--prev' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'arrows_position' => 'yes'
            ]
        ];

         $fields['left_arrow_y_position'] = [
            'label' => __( 'Y Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 3,
                ],
            ],
			'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-button--prev' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'arrows_position' => 'yes'
            ]
        ];

        $fields['arrows_right_heading_separator'] = [
            'type' => Controls_Manager::DIVIDER,
        ];

        $fields['arrows_right_heading'] = [
            'label' => __( 'Right Arrow', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'arrows_position' => 'yes'
            ]
        ];

        $fields['okthemes_right_arrow_alignment'] = [
            'label' => __( 'Alignment', 'okthemes-toolkit' ),
            'type' => Controls_Manager::CHOOSE,
            'custom_control' => 'add_responsive_control',
            'options' => [
                'top-left' => [
                    'title' => __( 'Top Left', 'okthemes-toolkit' ),
                ],
                'top-center' => [
                    'title' => __( 'Top Center', 'okthemes-toolkit' ),
                ],
                'top-right' => [
                    'title' => __( 'Top Right', 'okthemes-toolkit' ),
                ],
                'center-left' => [
                    'title' => __( 'Center Left', 'okthemes-toolkit' ),
                ],
                'center' => [
                    'title' => __( 'Center Center', 'okthemes-toolkit' ),
                ],
                'center-right' => [
                    'title' => __( 'Center Right', 'okthemes-toolkit' ),
                ],
                'bottom-left' => [
                    'title' => __( 'Bottom Left', 'okthemes-toolkit' ),
                ],
                'bottom-center' => [
                    'title' => __( 'Bottom Center', 'okthemes-toolkit' ),
                ],
                'bottom-right' => [
                    'title' => __( 'Bottom Right', 'okthemes-toolkit' ),
                ],
            ],
            'default' => 'center-right',
            'toggle' => false,
            'selectors_dictionary' => Utils::get_custom_position_selectors(),
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-button--next' => '{{VALUE}}'
            ],
        ];

        $fields['right_arrow_x_position'] = [
            'label' => __( 'X Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 3,
                ],
            ],
            'selectors' => [
				'{{WRAPPER}} .okthemes-swiper-button--next' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'arrows_position' => 'yes'
            ]
        ];

         $fields['right_arrow_y_position'] = [
            'label' => __( 'Y Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 3,
                ],
            ],
			'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-button--next' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'arrows_position' => 'yes'
            ]
        ];

		$fields['arrow_end_popover'] = [
            'custom_control' => 'end_popover'
        ];

        $fields['arrows_hover_animation'] = [
            'label' => __( 'Hover Animation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => __( 'None', 'okthemes-toolkit' ),
                'iconDuplicate' => __( 'Icon Duplicate', 'okthemes-toolkit' ),
            ],
			'default' => 'none',
            'frontend_available' => true,
            'prefix_class' => 'okthemes-arrows-animation--', 
            'condition' => [
                'navigation' => [ 'arrows', 'arrows-dots' ]
            ]
        ];

        $fields['hr_dots_top'] = [
            'type' => Controls_Manager::DIVIDER,
            'condition' => [
                'navigation' => 'arrows-dots'
            ]
        ];

        $fields['dots_heading'] = [
            'label' => __('Dots', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ]
            ]
        ];

        $fields['dots_style'] = [
            'label' => __( 'Style', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'bullets' => __( 'Bullets', 'okthemes-toolkit' ),
                'numbers' => __( 'Numbers', 'okthemes-toolkit' ),
                'fraction' => __( 'Fraction', 'okthemes-toolkit' ),
                'scrollbar' => __( 'Scrollbar', 'okthemes-toolkit' ),
                'progressbar' => __( 'Progress', 'okthemes-toolkit' ),
            ],
			'default' => 'bullets',
            'frontend_available' => true,
            'render_type' => 'template',
            'prefix_class' => 'okthemes-dots--style__',
            'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ]
            ]
        ];

        $fields['dots_bar_position'] = [
            'label' => __( 'Bar Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'top' => __( 'Top', 'okthemes-toolkit' ),
                'bottom' => __( 'Bottom', 'okthemes-toolkit' ),
            ],
			'default' => 'bottom',
            'prefix_class' => 'okthemes-dots--bar-position__',
            'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ],
                'dots_style' => [ 'progressbar', 'scrollbar' ],
            ]
        ];

        $fields['dots_fraction_divider'] = [
            'label' => __( 'Fraction', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
			'default' => '/',
			'frontend_available' => true,
            'condition' => [
				'navigation' => [ 'dots', 'arrows-dots' ],
				'dots_style' => 'fraction'
            ]
        ];

        $fields['dots_orientation'] = [
            'label' => __( 'Orientation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'horizontal' => __( 'Horizontal', 'okthemes-toolkit' ),
                'vertical' => __( 'Vertical', 'okthemes-toolkit' ),
            ],
            'default' => 'horizontal',
            'prefix_class' => 'okthemes-dots--orientation__',
            'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ],
                'dots_style!' => [ 'scrollbar', 'progressbar' ]
            ]
        ];

            $fields['dots_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'dots_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .okthemes-swiper-dots',
            'exclude' => [ 'font-size' ],
            'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ],
                'dots_style' => [ 'numbers', 'fraction' ]
            ]
        ];

        $fields['dots_size'] = [
            'custom_control' => 'add_responsive_control',
            'label' => __( 'Size', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 40
                ]
            ],
			'selectors' => [
				'{{WRAPPER}} .okthemes-swiper-dots' => 'font-size: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .swiper-scrollbar' => 'height: {{SIZE}}{{UNIT}}'
			],
			'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ]
            ]
        ];

        $fields['dots_gap'] = [
            'custom_control' => 'add_responsive_control',
            'label' => __( 'Gap', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'rem', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100
                ]
            ],
            'default' => [
                'size' => 30,
                'unit' => 'px'
            ],
			'selectors' => [
				'{{WRAPPER}}.okthemes-dots--bar-position__bottom .swiper-scrollbar, {{WRAPPER}}.okthemes-dots--bar-position__bottom .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}}.okthemes-dots--bar-position__top .swiper-scrollbar, {{WRAPPER}}.okthemes-dots--bar-position__top .swiper-pagination' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			],
			'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ],
                'dots_style' => [ 'scrollbar', 'progressbar' ]
            ]
        ];

        $fields['dots_space_between'] = [
            'custom_control' => 'add_responsive_control',
            'label' => __( 'Space Between', 'okthemes-toolkit' ),
			'type' => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}}.okthemes-dots--orientation__horizontal .okthemes-swiper-dots .swiper-pagination-bullet:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}}.okthemes-dots--orientation__vertical .okthemes-swiper-dots .swiper-pagination-bullet:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ],
            'default' => [
                'size' => 10,
                'unit' => 'px'
            ],
			'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ],
                'dots_style!' => [ 'scrollbar', 'progressbar' ]
            ]
        ];

        $fields['dots_position'] = [
           'label' => __( 'Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::POPOVER_TOGGLE,
            'return_value' => 'yes',
            'condition' => [
                'dots_style!' => [ 'scrollbar', 'progressbar' ],
                'navigation' => [ 'dots', 'arrows-dots' ]
            ]
        ];

        $fields['dots_alignment_start_popover'] = [
            'custom_control' => 'start_popover'
        ];

        $fields['okthemes_dots_alignment'] = [
            'label' => __( 'Alignment', 'okthemes-toolkit' ),
            'type' => Controls_Manager::CHOOSE,
            'custom_control' => 'add_responsive_control',
            'options' => [
                'top-left' => [
                    'title' => __( 'Top Left', 'okthemes-toolkit' ),
                ],
                'top-center' => [
                    'title' => __( 'Top Center', 'okthemes-toolkit' ),
                ],
                'top-right' => [
                    'title' => __( 'Top Right', 'okthemes-toolkit' ),
                ],
                'center-left' => [
                    'title' => __( 'Center Left', 'okthemes-toolkit' ),
                ],
                'center' => [
                    'title' => __( 'Center Center', 'okthemes-toolkit' ),
                ],
                'center-right' => [
                    'title' => __( 'Center Right', 'okthemes-toolkit' ),
                ],
                'bottom-left' => [
                    'title' => __( 'Bottom Left', 'okthemes-toolkit' ),
                ],
                'bottom-center' => [
                    'title' => __( 'Bottom Center', 'okthemes-toolkit' ),
                ],
                'bottom-right' => [
                    'title' => __( 'Bottom Right', 'okthemes-toolkit' ),
                ],
            ],
            'default' => 'bottom-center',
            'toggle' => false,
            'selectors_dictionary' => Utils::get_custom_position_selectors(),
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots' => '{{VALUE}}'
            ],
        ];

        $fields['dots_x_position'] = [
            'label' => __( 'X Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 3,
                ],
            ],
            'selectors' => [
				'{{WRAPPER}} .okthemes-swiper-dots' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['dots_y_position'] = [
            'label' => __( 'Y Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', '%', 'vw', 'rem' ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 3,
                ],
            ],
            'default' => [
                'size' => 30,
                'unit' => 'px'
            ],
			'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
        ];

		$fields['dots_end_popover'] = [
            'custom_control' => 'end_popover'
        ];

        $fields['dots_tabs'] = [
            'custom_control' => 'start_controls_tabs',
            'condition' => [
                'navigation' => [ 'dots', 'arrows-dots' ],
            ]
        ];

        $fields['dots_tabs_normal'] = [
            'label' => __( 'Normal', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['dots_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'condition' => [ 
                'dots_style' => [ 'numbers', 'fraction' ] 
            ],
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet' => 'color: {{VALUE}}',
                '{{WRAPPER}} .okthemes-swiper-dots' => 'color: {{VALUE}}',
            ],
        ];

        $fields['dots_bg_color'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'condition' => [ 'dots_style!' => [ 'numbers', 'fraction' ] ],
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
                '{{WRAPPER}}.okthemes-dots--style__scrollbar .swiper-scrollbar' => 'background-color: {{VALUE}}',
                '{{WRAPPER}}.okthemes-dots--style__progressbar .swiper-scrollbar' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['dots_border'] = [
            'label' => __( 'Border', 'okthemes-toolkit' ),
            'name' => 'dots_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet',
            'condition' => [
                'dots_style' => [ 'bullets' ]
            ]
        ];

        $fields['dots_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 20
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'dots_style' => [ 'bullets' ]
            ]
        ];
        
        $fields[] = [ 'custom_control' => 'end_controls_tab' ];

        $fields['dots_tabs_hover'] = [
            'label' => __( 'Hover & Active', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['dots_color_hover'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'condition' => [ 
                'dots_style' => [ 'numbers', 'fraction' ] 
            ],
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet:hover' => 'color: {{VALUE}}',
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet-active' => 'color: {{VALUE}}',
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-current' => 'color: {{VALUE}}',
            ],
        ];

        $fields['dots_bg_color_hover'] = [
            'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'condition' => [ 'dots_style!' => [ 'numbers', 'fraction' ] ],
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}}',
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
                '{{WRAPPER}}.okthemes-dots--style__scrollbar .swiper-scrollbar-drag' => 'background-color: {{VALUE}}',
                '{{WRAPPER}}.okthemes-dots--style__progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
            ],
        ];

        $fields['dots_border_hover'] = [
            'label' => __( 'Border', 'okthemes-toolkit' ),
            'name' => 'dots_border_hover',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet:hover, {{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet-active',
            'condition' => [
                'dots_style' => [ 'bullets' ]
            ]
        ];

        $fields['dots_border_radius_hover'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 20
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .okthemes-swiper-dots .swiper-pagination-bullet-active' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'dots_style' => [ 'bullets' ]
            ]
        ];

        $fields['dots_hover_animation'] = [
            'label' => __( 'Animation', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => __( 'None', 'okthemes-toolkit' ),
                'scale' => __( 'Scale', 'okthemes-toolkit' ),
            ],
            'default' => 'scale',
            'prefix_class' => 'okthemes-dots--animation__',
            'condition' => [
                'dots_style' => [ 'bullets' ]
            ]
        ];

        $fields['scrollbar_drag_height'] = [
            'custom_control' => 'add_responsive_control',
            'label' => __( 'Drag Height', 'okthemes-toolkit' ),
			'type' => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .swiper-scrollbar-drag' => 'height: {{SIZE}}{{UNIT}}',
			],
			'condition' => [
                'dots_style' => [ 'scrollbar' ]
            ]
        ];
        
        $fields[] = [ 'custom_control' => 'end_controls_tab' ];

        $fields[] = [ 'custom_control' => 'end_controls_tabs' ];

        return $fields;
    }

    public function filters_style_controls() {
        $fields = [];

        $fields['filters_space_between'] = [
            'label' => __( 'Space Between', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-filters ul li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
            ],
        ];

        $fields['filters_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'custom_control' => 'add_responsive_control',
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ];

        $fields['filters_alignment'] = [
            'custom_control' => 'add_responsive_control',
            'label' => __('Alignment', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'fa fa-align-right',
                ],
            ],
            'condition' => [
                'filters' => 'yes'
            ],
            'selectors_dictionary' => [
                'left' => 'flex-start',
                'right' => 'flex-end'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-filters ul' => 'justify-content: {{VALUE}}',
            ],
        ];

        $fields['filters_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'filters_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-filters',
        ];

        $fields['filters_tabs'] = [
            'custom_control' => 'start_controls_tabs',
        ];

        $fields['filters_tabs_normal'] = [
            'label' => __( 'Normal', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['filters_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-filters__item' => 'color: {{VALUE}}',
            ],
        ];

        $fields['filters_active_color'] = [
            'label' => __( 'Active Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-filters--dropdown__active span' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'filters_style' => 'dropdown'
            ]
        ];

        $fields[] = [ 'custom_control' => 'end_controls_tab' ];

        $fields['filters_tabs_hover_active'] = [
            'label' => __( 'Hover & Active', 'okthemes-toolkit' ),
            'custom_control' => 'start_controls_tab'
        ];

        $fields['filters_color_hover'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-filters__item:hover, {{WRAPPER}} .m-okthemes-filters__item.active' => 'color: {{VALUE}}',
            ],
        ];
        
        $fields[] = [ 'custom_control' => 'end_controls_tab' ];

        $fields[] = [ 'custom_control' => 'end_controls_tabs' ];

        return $fields;
    }

    public function register_section($thisElement, $section_name, $condition = '', $tab = '') {

        // Start Section
        $section_label = $section_name;

        // Replace Label
        $section_label = $section_label == 'query_metro' ? 'metro' : $section_label;

        if ( strpos( $section_label, '_style' ) ) {
            $section_label = str_replace( '_style', '', $section_label );
        }

        if ( strpos( $section_label, '_overlay' ) ) {
            $section_label = str_replace( '_overlay', ' Overlay', $section_label );
        }

        if ( strpos( $section_label, '_flash' ) ) {
            $section_label = str_replace( '_flash', ' Flash', $section_label );
        }

        if ( strpos( $section_label, '_rating' ) ) {
            $section_label = str_replace( '_rating', ' Rating', $section_label );
        }

        if ( $section_label == 'add_to_cart' ) {
            $section_label = __('Add To Cart', 'okthemes-toolkit');
        }

        if ( $section_label == 'content_order' ) {
            $section_label = __('Content Order', 'okthemes-toolkit');
        }

        if ( $section_label == 'quick_view' ) {
            $section_label = __('Quick View', 'okthemes-toolkit');
        }

        // Tab Content
        if ( $tab === 'TAB_STYLE' ) {
            $tabType = Controls_Manager::TAB_STYLE;
        } else {
            $tabType = Controls_Manager::TAB_CONTENT;
        }

        $thisElement->start_controls_section(
			$section_name . '_section', [
                'label' => __( ucfirst( $section_label ), 'okthemes-toolkit' ),
                'condition' => $condition,
                'tab' => $tabType
			]
        );

        $controls = $section_name . '_controls';

        $fixed_controls = [
            'add_responsive_control',
            'add_group_control',
            'start_controls_tabs',
            'start_controls_tab', 
        ];

        foreach ( $this->$controls() as $key => $control ) {
            if ( ! empty( $control['custom_control'] ) && $control['custom_control'] ) {
                $custom_control = $control['custom_control'];
                unset( $control['custom_control'] );

                // Custom Key
                if ( ! empty ( $control['custom_key'] ) ) {
                    $key = $control['custom_key'];
                }
 
                if ( in_array( $custom_control, $fixed_controls ) ) {
                    $thisElement->$custom_control($key, $control);
                } else {
                    $thisElement->$custom_control();
                }
            } else {
                $thisElement->add_control( $key, $control );
            }
        }

        // End Section
		$thisElement->end_controls_section();
    }
}