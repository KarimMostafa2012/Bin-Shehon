<?php
/**
 * Products Class
 * 
 * Extends the class Posts
 * 
 * @since 1.0.0
 */

namespace OKThemes\Toolkit\Elementor\Helpers;

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use OKThemes\Toolkit\Elementor\Helpers\Posts_Helper as Posts;
use OKThemes\Toolkit\Elementor\Helpers\Utils;

class Products extends Posts_Helper {

    public function __construct( $thisElement ) {
        // Content
        $this->register_section( $thisElement, 'layout' );
        $this->register_section( $thisElement, 'query' );
        $this->register_section( $thisElement, 'query_metro', [ 'layout' => 'metro' ] );
        $this->register_section( $thisElement, 'pagination', ['carousel!' => 'yes'] );
        $this->register_section( $thisElement, 'filters' );

        // Style
        $this->register_section( $thisElement, 'layout_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'box_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'image_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'content_style', '', 'TAB_STYLE' );
        $this->register_section( $thisElement, 'pagination_style', ['pagination!' => 'none', 'carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'navigation_style', ['carousel' => 'yes', 'navigation!' => 'none'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'filters_style', ['carousel!' => 'yes', 'filters' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'sale_flash_style', ['carousel!' => 'yes'], 'TAB_STYLE' );
        $this->register_section( $thisElement, 'add_to_cart_style', ['add_to_cart' => 'yes'], 'TAB_STYLE' );

    }

    public function get_woo_source() {
        $fields = [
            'latest-products' => __( 'Latest Products', 'okthemes-toolkit' ),
            'sale' => __( 'Sale', 'okthemes-toolkit' ),
            'featured' => __( 'Featured', 'okthemes-toolkit' ),
            'manual-selection' => __( 'Manual Selection', 'okthemes-toolkit' ),
            'current_query' => __( 'Current Query', 'okthemes-toolkit' ),
        ];

        return $fields;
    }

    public function layout_controls() {
        $fields = parent::layout_controls();

        $remove = [
            'skin',
            'excerpt',
            'excerpt_length',
            'image_position',
            'image_width',
            'show_read_more',
            'bottom_divider_title',
            'meta_data',
            'separator_between',
            'bottom_divider_meta_tag',
            'badge',
            'badge_taxonomy',
            'avatar',
        ];

        foreach ( $remove as $key ) {
            unset($fields[$key]);
        }

        $fields['rating'] = [
            'label' => __( 'Rating', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes'
        ];

        $fields['price'] = [
            'label' => __( 'Price', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'separator' => 'before'
        ];

        // Add to cart Enable
        $fields['add_to_cart'] = [
            'label' => __( 'Add to Cart', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'separator' => 'before'
        ];

        $fields['add_to_cart_text'] = [
            'label' => __( 'Text', 'okthemes-toolkit' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Add to Cart', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'condition' => [
                'add_to_cart' => 'yes'
            ]
        ];


        // Conditions
        $fields['layout']['condition'] = [
            'carousel!' => 'yes'
        ];

        $fields['image_ratio']['condition'] = [
            'layout!' => ['masonry', 'metro'],
        ];

        $fields['image_size']['condition'] = '';

        return $fields;
    }

    public function query_controls() {
        $fields = [];

        $fields['source'] = [
            'label' => __( 'Source', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_woo_source(),
            'default' => 'latest-products'
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
            'options' => [
                'term' => __( 'Term', 'okthemes-toolkit' ),
                'author' => __( 'Author', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'source!' => [ 'manual-selection', 'current_query' ]
            ]
        ];

        $fields['include_term'] = [
            'label' => __( 'Term', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_terms( 'product' ),
            'label_block' => true,
            'default' => [],
            'select2options' => [
                'placeholder' => __( 'All', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'include_by' => 'term'
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
            'options' => Utils::get_the_posts( ['product'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'exclude_by' => 'manual-selection'
            ],
        ];

        $fields['exclude_term'] = [
            'label' => __( 'Term', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_terms( 'product' ),
            'label_block' => true,
            'default' => [],
            'select2options' => [
                'placeholder' => __( 'All', 'okthemes-toolkit' ),
            ],
            'condition' => [
                'exclude_by' => 'term'
            ],
        ];

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

        $fields['search_select'] = [
            'label' => __( 'Search & Select', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => Utils::get_the_posts( ['product'] ),
            'label_block' => true,
            'default' => [],
            'condition' => [
                'source' => 'manual-selection',
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
                'date' => __( 'Date', 'okthemes-toolkit' ),
                'title' => __( 'Title', 'okthemes-toolkit' ),
                'menu-order' => __( 'Menu Order', 'okthemes-toolkit' ),
                'rand' => __( 'Random', 'okthemes-toolkit' ),
            ],
            'default' => 'date',
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

		$repeater->add_control(
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

        $fields['allow_order'] = [
            'label' => __( 'Allow Order', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'okthemes-toolkit' ),
            'label_off' => __( 'No', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no'
        ];

        $fields['results_count'] = [
            'label' => __( 'Results Count', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no',
            'separator' => 'after'
        ];

        $fields = array_merge( $fields, parent::pagination_controls() );

        return $fields;
    }

    public function filters_controls() {
        $fields = Posts::filters_controls();

        $fields['filters_tax']['options'] = [
            'product_cat' => __( 'Product Category', 'okthemes-toolkit' ),
            'product_tag' => __( 'Product Tags', 'okthemes-toolkit' ),
        ];

        $fields['filters_tax']['default'] = 'product_cat';

        return $fields;
    }

    public function layout_style_controls() {
        $fields = Posts::layout_style_controls();

        return $fields;
    }

    public function box_style_controls() {
        $fields = Posts::box_style_controls();

        return $fields;
    }

    public function image_style_controls() {
        $fields = Posts::image_style_controls();

        // Conditions
        $fields['image_spacing_classic']['condition'] = '';

        $fields['image_hover_secondary']['condition'] = '';

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
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__title a' => 'color: {{VALUE}}'
            ],
        ];

        $fields['content_title_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'content_title_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
            'selector' => '{{WRAPPER}} .m-okthemes-product__title',
        ];

        $fields['content_title_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Price
        $fields['content_price_heading'] = [
            'label' => __('Price', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before'
        ];

        $fields['content_price_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__price' => 'color: {{VALUE}}'
            ],
        ];

        $fields['content_price_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'content_price_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
            'selector' => '{{WRAPPER}} .m-okthemes-product__price',
        ];

        $fields['content_price_alignment'] = [
            'label' => __( 'Alignment', 'okthemes-toolkit' ),
            'type' => Controls_Manager::CHOOSE,
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
            'prefix_class' => 'm-okthemes-product__price--alignment-'
        ];

        $fields['content_price_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}}*[class^="m-okthemes-product__price--alignment-"] .m-okthemes-product__price' => 'margin: {{SIZE}}{{UNIT}}'
            ]
        ];

        // Star Rating
        $fields['star_rating_heading'] = [
            'label' => __('Star Rating', 'okthemes-toolkit'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['rating_color'] = [
            'label' => __( 'Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .woocommerce .star-rating::before' => 'color: {{VALUE}}',
                '{{WRAPPER}} .woocommerce .star-rating' => 'color: {{VALUE}}'
            ],
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['rating_size'] = [
            'label' => __( 'Size', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .woocommerce .star-rating' => 'font-size: {{SIZE}}{{UNIT}}'
            ],
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['rating_spacing'] = [
            'label' => __( 'Spacing', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__rating .woocommerce-product-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}'
            ],
            'condition' => [
                'rating' => 'yes'
            ]
        ];

        $fields['content_border'] = [
            'label' => __( 'Border', 'okthemes-toolkit' ),
            'name' => 'content_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-product__content',
            'default' => 'none',
            'separator' => 'before'
        ];

        $fields['content_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['content_products_padding'] = [
            'label' => __( 'Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'rem' ],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        return $fields;
    }

    public function pagination_style_controls() {
        $fields = Posts::pagination_style_controls();

        return $fields;
    }
    
    public function navigation_style_controls() {
        $fields = Posts::navigation_style_controls();

        return $fields;
    }
    
    public function sale_flash_style_controls() {
        $fields = [];

        $fields['sale_flash'] = [
            'label' => __( 'Sale Flash', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Show', 'okthemes-toolkit' ),
            'label_off' => __( 'Hide', 'okthemes-toolkit' ),
            'return_value' => 'yes',
            'default' => 'no'
        ];

        $fields['sale_flash_text_color'] = [
			'label' => __( 'Text Color', 'okthemes-toolkit' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => 'color: {{VALUE}};',
			],
			'condition' => [
                'sale_flash' => 'yes',
			],
        ];
        
        $fields['sale_flash_background_color'] = [
			'label' => __( 'Background Color', 'okthemes-toolkit' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => 'background-color: {{VALUE}};',
			],
			'condition' => [
                'sale_flash' => 'yes',
			],
        ];
        
        $fields['sale_flash_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'sale_flash_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-portfolio__sale-flash',
            'condition' => [
                'sale_flash' => 'yes',
			]
        ];

        $fields['sale_flash_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'condition' => [
                'sale_flash' => 'yes',
			],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['sale_flash_width'] = [
            'label' => __('Width', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'condition' => [
                'sale_flash' => 'yes',
			],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => 'width: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['sale_flash_height'] = [
            'label' => __('Height', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'condition' => [
                'sale_flash' => 'yes',
			],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => 'height: {{SIZE}}{{UNIT}}',
            ],
        ];

        $fields['sale_flash_position'] = [
			'label' => __( 'Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::CHOOSE,
            'condition' => [
                'sale_flash' => 'yes',
			],
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
            'selectors_dictionary' => [
                'left' => 'left: 0; right: auto;',
                'right' => 'right: 0; left: auto;'
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => '{{VALUE}}',
            ],
			'label_block' => false,
        ];
        
        $fields['sale_flash_distance'] = [
            'label' => __('Distance', 'okthemes-toolkit'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'condition' => [
                'sale_flash' => 'yes',
            ],
            'range' => [
                'px' => [
                    'min' => -20,
                    'max' => 20,
                    'step' => 1,
                ],
                'em' => [
                    'min' => -2,
                    'max' => 2,
                    'step' => 0.1
                ]
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-portfolio__sale-flash' => 'margin: {{SIZE}}{{UNIT}}',
            ],
        ];

        return $fields;
    }
    
    public function add_to_cart_style_controls() {
        $fields = [];

        $fields['add_to_cart_text_color'] = [
			'label' => __( 'Text Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
			'selectors' => [
				'{{WRAPPER}} .m-okthemes-product__add-to-cart a' => 'color: {{VALUE}};',
			],
        ];

        $fields['add_to_cart_loading_color'] = [
			'label' => __( 'Loading Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
			'selectors' => [
				'{{WRAPPER}} .m-okthemes-product__add-to-cart .loading:before' => 'border-color: {{VALUE}}; border-bottom-color: transparent;',
			],
        ];
        
        $fields['add_to_cart_bg_color'] = [
			'label' => __( 'Background Color', 'okthemes-toolkit' ),
            'type' => Controls_Manager::COLOR,
            'default' => '#000',
			'selectors' => [
				'{{WRAPPER}} .m-okthemes-product__add-to-cart' => 'background-color: {{VALUE}};',
			],
        ];
        
        $fields['add_to_cart_typography'] = [
            'label' => __( 'Typography', 'okthemes-toolkit' ),
            'name' => 'add_to_cart_typography',
            'custom_key' => Group_Control_Typography::get_type(),
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
            ],
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-product__add-to-cart a',
        ];

        $fields['add_to_cart_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_border'] = [
            'label' => __( 'Border', 'okthemes-toolkit' ),
            'name' => 'add_to_cart_border',
            'custom_key' => Group_Control_Border::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-product__add-to-cart',
            'default' => 'none',
        ];

        $fields['add_to_cart_border_radius'] = [
            'label' => __( 'Border Radius', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'custom_control' => 'add_responsive_control',
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__add-to-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['add_to_cart_shadow'] = [
            'label' => __( 'Box Shadow', 'okthemes-toolkit' ),
            'name' => 'add_to_cart_shadow',
            'custom_key' => Group_Control_Box_Shadow::get_type(),
            'custom_control' => 'add_group_control',
            'selector' => '{{WRAPPER}} .m-okthemes-product__add-to-cart'
        ];

        $fields['add_to_cart_second_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_padding'] = [
            'label' => __( 'Padding', 'okthemes-toolkit' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'rem'],
            'custom_control' => 'add_responsive_control',
            'default' => [
                'left' => 6,
                'right' => 6,
                'top' => 6,
                'bottom' => 6,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .m-okthemes-product__add-to-cart a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ];

        $fields['add_to_cart_third_divider'] = [
            'type' => Controls_Manager::DIVIDER
        ];

        $fields['add_to_cart_position'] = [
            'label' => __( 'Position', 'okthemes-toolkit' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'inside' => __( 'Inside', 'okthemes-toolkit' ),
                'outside' => __( 'Outside', 'okthemes-toolkit' ),
            ],
            'prefix_class' => 'm-okthemes-product__add-to-cart--position-',
            'render_type' => 'template',
            'default' => 'inside',
        ];

        $fields['add_to_cart_h_position'] = [
            'label' => __('Horizontal Position', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'okthemes-toolkit'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'okthemes-toolkit'),
                    'icon' => 'eicon-h-align-right',
                ],
                'justified' => [
                    'title' => __('Justified', 'okthemes-toolkit'),
                    'icon' => 'eicon-h-align-stretch',
                ],
            ],
            'default' => 'justified',
            'prefix_class' => 'm-okthemes-product__add-to-cart--h-'
        ];

        $fields['add_to_cart_v_position'] = [
            'label' => __('Vertical Position', 'okthemes-toolkit'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                 'top' => [
                    'title' => __('Top', 'okthemes-toolkit'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => __('Center', 'okthemes-toolkit'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'bottom' => [
                    'title' => __('Bottom', 'okthemes-toolkit'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'prefix_class' => 'm-okthemes-product__add-to-cart--v-'
        ];

        return $fields;
    }

}
