<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Villenoir My Account Widget
 *
 * Renders the WooCommerce My Account icon link used in Villenoir headers.
 * Requires WooCommerce. Only registered when the Villenoir theme is active.
 */
class VillenoirMyAccount extends Widget_Base {

	public function get_name() {
		return 'villenoir-my-account';
	}

	public function get_title() {
		return esc_html__( 'Villenoir - My Account', 'okthemes-toolkit' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'okthemes_elements' ];
	}

	public function get_keywords() {
		return [ 'villenoir', 'my account', 'account', 'woocommerce', 'login' ];
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
			'selectors' => [ '{{WRAPPER}} .quick-my-account a' => 'color: {{VALUE}};' ],
		] );
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_icon_hover', [ 'label' => esc_html__( 'Hover', 'okthemes-toolkit' ) ] );
		$this->add_control( 'icon_hover_color', [
			'label'     => esc_html__( 'Color', 'okthemes-toolkit' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .quick-my-account a:hover' => 'color: {{VALUE}};' ],
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
				'{{WRAPPER}} .quick-my-account svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	// -------------------------------------------------------------------------
	// Render
	// -------------------------------------------------------------------------

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p style="color:#c00">' . esc_html__( 'WooCommerce is required for the My Account widget.', 'okthemes-toolkit' ) . '</p>';
			}
			return;
		}
		?>
		<li class="quick-my-account">
			<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="<?php esc_attr_e( 'My Account', 'villenoir' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
			</a>
		</li>
		<?php
	}
}
