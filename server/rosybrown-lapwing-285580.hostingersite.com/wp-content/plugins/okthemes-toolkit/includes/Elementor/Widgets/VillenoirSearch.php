<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Villenoir Search Widget
 *
 * Renders the header search icon used in Villenoir headers.
 * Clicking the icon opens the full-screen search overlay (parts/part-searchform-toolbar.php).
 * Only registered when the Villenoir theme is active.
 */
class VillenoirSearch extends Widget_Base {

	public function get_name() {
		return 'villenoir-search';
	}

	public function get_title() {
		return esc_html__( 'Villenoir - Search', 'okthemes-toolkit' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'okthemes_elements' ];
	}

	public function get_keywords() {
		return [ 'villenoir', 'search', 'header', 'fullscreen' ];
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
			'selectors' => [ '{{WRAPPER}} .gg-header-search a' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_icon_hover', [ 'label' => esc_html__( 'Hover', 'okthemes-toolkit' ) ] );
		$this->add_control( 'icon_hover_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .gg-header-search a:hover' => 'color: {{VALUE}};' ],
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
				'{{WRAPPER}} .gg-header-search svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Render
	// -------------------------------------------------------------------------

	protected function render() {
		if ( ! function_exists( 'villenoir_header_search' ) ) {
			return;
		}
		echo '<li class="gg-header-search">' . villenoir_header_search() . '</li>';
	}
}
