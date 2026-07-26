<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Villenoir Navigation Widget
 *
 * Outputs the Villenoir theme primary navigation inside an Elementor template.
 * Matches the exact markup from lib/headers/part-default-menu.php so that
 * theme CSS and JS (primary-navigation.js) work without modification.
 *
 * Only registered when the Villenoir theme is active (see Manager.php).
 */
class VillenoirNavigation extends Widget_Base {

	public function get_name() {
		return 'villenoir-navigation';
	}

	public function get_title() {
		return esc_html__( 'Villenoir - Navigation', 'okthemes-toolkit' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'okthemes_elements' ];
	}

	public function get_keywords() {
		return [ 'villenoir', 'navigation', 'menu', 'nav', 'header' ];
	}

	// -------------------------------------------------------------------------
	// Controls
	// -------------------------------------------------------------------------

	protected function register_controls() {

		// Content: Menu selection ---------------------------------------------
		$this->start_controls_section(
			'section_menu',
			[ 'label' => esc_html__( 'Menu', 'okthemes-toolkit' ) ]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control( 'nav_menu', [
				'label'       => esc_html__( 'Main Menu', 'okthemes-toolkit' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array_merge(
					[ '' => esc_html__( 'Theme Default (main-menu location)', 'okthemes-toolkit' ) ],
					$menus
				),
				'default'     => '',
				'description' => sprintf(
					__( 'Manage menus in the <a href="%s" target="_blank">Menus screen</a>.', 'okthemes-toolkit' ),
					admin_url( 'nav-menus.php' )
				),
			] );
		} else {
			$this->add_control( 'nav_menu_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					__( '<strong>No menus found.</strong> <a href="%s" target="_blank">Create one</a>.', 'okthemes-toolkit' ),
					admin_url( 'nav-menus.php?action=edit&menu=0' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			] );
		}

		$this->end_controls_section();

		// Style: Menu items ---------------------------------------------------
		$this->start_controls_section(
			'section_style_menu',
			[
				'label' => esc_html__( 'Menu Items', 'okthemes-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_typography',
				'selector' => '{{WRAPPER}} .nav.navbar-nav.navbar-middle > li > a',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item' );

		$this->start_controls_tab( 'tab_menu_normal', [ 'label' => esc_html__( 'Normal', 'okthemes-toolkit' ) ] );
		$this->add_control( 'menu_item_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .nav.navbar-nav.navbar-middle > li > a' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_menu_hover', [ 'label' => esc_html__( 'Hover / Active', 'okthemes-toolkit' ) ] );
		$this->add_control( 'menu_item_hover_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .nav.navbar-nav.navbar-middle > li > a:hover'                 => 'color: {{VALUE}};',
				'{{WRAPPER}} .nav.navbar-nav.navbar-middle > li.current_page_item > a'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .nav.navbar-nav.navbar-middle > li.current-menu-item > a'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .nav.navbar-nav.navbar-middle > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
			],
		] );
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'menu_item_padding', [
			'label'      => esc_html__( 'Item Padding', 'okthemes-toolkit' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .nav.navbar-nav.navbar-middle > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// Style: Dropdown -----------------------------------------------------
		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => esc_html__( 'Dropdown', 'okthemes-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control( 'dropdown_bg', [
			'label'     => esc_html__( 'Background', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .nav.navbar-nav.navbar-middle li ul.sub-menu' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .nav.navbar-nav.navbar-middle li ul.sub-menu li a',
			]
		);

		$this->start_controls_tabs( 'tabs_dropdown_item' );

		$this->start_controls_tab( 'tab_dropdown_normal', [ 'label' => esc_html__( 'Normal', 'okthemes-toolkit' ) ] );
		$this->add_control( 'dropdown_item_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .nav.navbar-nav.navbar-middle li ul.sub-menu li a' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_dropdown_hover', [ 'label' => esc_html__( 'Hover', 'okthemes-toolkit' ) ] );
		$this->add_control( 'dropdown_item_hover_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .nav.navbar-nav.navbar-middle li ul.sub-menu li a:hover' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'dropdown_item_padding', [
			'label'      => esc_html__( 'Item Padding', 'okthemes-toolkit' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'separator'  => 'before',
			'selectors'  => [
				'{{WRAPPER}} .nav.navbar-nav.navbar-middle li ul.sub-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// Style: Mobile toggle ------------------------------------------------
		$this->start_controls_section(
			'section_style_mobile',
			[
				'label' => esc_html__( 'Mobile Toggle', 'okthemes-toolkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control( 'mobile_toggle_color', [
			'label'     => esc_html__( 'Icon / Label Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #primary-mobile-menu' => 'color: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Render
	// -------------------------------------------------------------------------

	protected function render() {
		$settings      = $this->get_settings_for_display();
		$selected_menu = ! empty( $settings['nav_menu'] ) ? $settings['nav_menu'] : '';

		$nav_args = [
			'container'   => false,
			'menu_class'  => 'nav navbar-nav navbar-middle',
			'menu_id'     => 'main-menu',
			'fallback_cb' => false,
			'depth'       => 0,
		];

		if ( $selected_menu ) {
			$nav_args['menu'] = $selected_menu;
		} else {
			$nav_args['theme_location'] = 'main-menu';
		}
		?>
<nav class="navbar navbar-default navbar-expand-lg villenoir-nav-widget">
	<div class="container navbar-header-wrapper">
		<div class="navbar-grid" id="main-navbar">

			<div class="menu-button-container">
				<button id="primary-mobile-menu" class="button" aria-controls="primary-menu-list" aria-expanded="false">
					<span class="dropdown-icon open">
						<span><?php esc_html_e( 'Menu', 'villenoir' ); ?></span>
						<svg class="svg-icon" width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.5 6H19.5V7.5H4.5V6ZM4.5 12H19.5V13.5H4.5V12ZM19.5 18H4.5V19.5H19.5V18Z" fill="currentColor"/></svg>
					</span>
					<span class="dropdown-icon close">
						<span><?php esc_html_e( 'Close', 'villenoir' ); ?></span>
						<svg class="svg-icon" width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 10.9394L5.53033 4.46973L4.46967 5.53039L10.9393 12.0001L4.46967 18.4697L5.53033 19.5304L12 13.0607L18.4697 19.5304L19.5303 18.4697L13.0607 12.0001L19.5303 5.53039L18.4697 4.46973L12 10.9394Z" fill="currentColor"/></svg>
					</span>
				</button>
			</div>

			<?php wp_nav_menu( $nav_args ); ?>

		</div><!-- #main-navbar -->
	</div><!-- .container -->
</nav>
		<?php
	}

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	private function get_available_menus() {
		$menus   = wp_get_nav_menus();
		$options = [];
		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}
		return $options;
	}
}
