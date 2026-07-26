<?php

namespace BinShihon\SiteTools\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Product_Categories extends Widget_Base {
	public function get_name() {
		return 'binshihon-product-categories';
	}

	public function get_title() {
		return esc_html__( 'Bin Shihon Product Categories', 'binshihon-site-tools' );
	}

	public function get_icon() {
		return 'eicon-product-categories';
	}

	public function get_categories() {
		return array( 'binshihon', 'woocommerce-elements' );
	}

	public function get_keywords() {
		return array( 'product', 'category', 'woocommerce', 'shop', 'bin shihon' );
	}

	public function get_style_depends() {
		return array( 'binshihon-site-tools' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Categories', 'binshihon-site-tools' ),
			)
		);

		$this->add_control(
			'parent',
			array(
				'label'       => esc_html__( 'Parent category ID', 'binshihon-site-tools' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'description' => esc_html__( 'Use 0 for top-level categories.', 'binshihon-site-tools' ),
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide empty categories', 'binshihon-site-tools' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'binshihon-site-tools' ),
				'label_off'    => esc_html__( 'No', 'binshihon-site-tools' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Limit', 'binshihon-site-tools' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 50,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order by', 'binshihon-site-tools' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'menu_order',
				'options' => array(
					'menu_order' => esc_html__( 'Menu order', 'binshihon-site-tools' ),
					'name'       => esc_html__( 'Name', 'binshihon-site-tools' ),
					'id'         => esc_html__( 'ID', 'binshihon-site-tools' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'binshihon-site-tools' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'binshihon-site-tools' ),
					'DESC' => esc_html__( 'Descending', 'binshihon-site-tools' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'binshihon-site-tools' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .bst-product-categories' => '--bst-category-columns: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'woocommerce_thumbnail',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$terms    = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => 'yes' === $settings['hide_empty'],
				'parent'     => absint( $settings['parent'] ),
				'number'     => absint( $settings['limit'] ),
				'orderby'    => sanitize_key( $settings['orderby'] ),
				'order'      => 'DESC' === $settings['order'] ? 'DESC' : 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return;
		}

		$shop_url = wc_get_page_permalink( 'shop' );

		echo '<div class="bst-product-categories products">';

		foreach ( $terms as $term ) {
			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			$link         = add_query_arg( 'cat', $term->slug, $shop_url );

			echo '<article class="bst-category-card product product-category">';
			echo '<a class="woocommerce-LoopProduct-link woocommerce-loop-product__link bst-category-card__link" href="' . esc_url( $link ) . '">';
			echo '<span class="product-image-wrap bst-category-card__image">';

			if ( $thumbnail_id ) {
				echo wp_get_attachment_image( $thumbnail_id, $settings['image_size'] );
			} else {
				echo wc_placeholder_img( $settings['image_size'] );
			}

			echo '</span>';
			echo '<span class="product-meta-wrap bst-category-card__body">';
			echo '<h2 class="woocommerce-loop-product__title bst-category-card__title">' . esc_html( $term->name ) . '</h2>';
			echo '</span>';
			echo '</a>';
			echo '</article>';
		}

		echo '</div>';
	}
}
