<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Villenoir Minicart Widget
 *
 * Renders the WooCommerce mini-cart icon used in Villenoir headers.
 * Clicking the icon opens the side cart panel (#side-cart).
 * Requires WooCommerce. Only registered when the Villenoir theme is active.
 */
class VillenoirMinicart extends Widget_Base {

	public function get_name() {
		return 'villenoir-minicart';
	}

	public function get_title() {
		return esc_html__( 'Villenoir - Minicart', 'okthemes-toolkit' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'okthemes_elements' ];
	}

	public function get_keywords() {
		return [ 'villenoir', 'cart', 'minicart', 'mini cart', 'woocommerce', 'basket' ];
	}

	// -------------------------------------------------------------------------
	// Controls
	// -------------------------------------------------------------------------

	protected function register_controls() {

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Icon', 'okthemes-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_icon' );

		$this->start_controls_tab( 'tab_icon_normal', [ 'label' => esc_html__( 'Normal', 'okthemes-toolkit' ) ] );
		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .gg-woo-mini-cart a' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_icon_hover', [ 'label' => esc_html__( 'Hover', 'okthemes-toolkit' ) ] );
		$this->add_control( 'icon_hover_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .gg-woo-mini-cart a:hover' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'icon_size', [
			'label'      => esc_html__( 'Icon Size', 'okthemes-toolkit' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 12, 'max' => 64 ] ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .gg-woo-mini-cart svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'badge_heading', [
			'label'     => esc_html__( 'Count Badge', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'badge_bg_color', [
			'label'     => esc_html__( 'Background', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .quick_cart_count' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'badge_text_color', [
			'label'     => esc_html__( 'Text Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .quick_cart_count' => 'color: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Render
	// -------------------------------------------------------------------------

	protected function render() {
		if ( ! function_exists( 'villenoir_wc_minicart' ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p style="color:#c00">' . esc_html__( 'WooCommerce is required for the Minicart widget.', 'okthemes-toolkit' ) . '</p>';
			}
			return;
		}
		echo '<li class="gg-woo-mini-cart dropdown">' . villenoir_wc_minicart() . '</li>';
	}
}
