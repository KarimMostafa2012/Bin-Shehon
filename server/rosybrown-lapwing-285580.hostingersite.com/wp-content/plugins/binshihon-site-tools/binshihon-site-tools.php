<?php
/**
 * Plugin Name: Bin Shihon Site Tools
 * Description: Site-specific Elementor widgets, brand styling, and quote request email notifications.
 * Version: 1.0.0
 * Author: Bin Shihon
 * Text Domain: binshihon-site-tools
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BST_PATH', plugin_dir_path( __FILE__ ) );
define( 'BST_URL', plugin_dir_url( __FILE__ ) );

add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style(
			'binshihon-site-tools',
			BST_URL . 'assets/css/binshihon-site-tools.css',
			array(),
			'1.0.2'
		);
	}
);

add_action(
	'elementor/widgets/register',
	function( $widgets_manager ) {
		if ( ! class_exists( '\Elementor\Widget_Base' ) || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		require_once BST_PATH . 'includes/Elementor/Widgets/Product_Categories.php';
		$widgets_manager->register( new \BinShihon\SiteTools\Elementor\Widgets\Product_Categories() );
	}
);

add_action(
	'elementor/elements/categories_registered',
	function( $elements_manager ) {
		$elements_manager->add_category(
			'binshihon',
			array(
				'title' => esc_html__( 'Bin Shihon', 'binshihon-site-tools' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}
);

add_action( 'wcqr_quote_created', 'bst_send_quote_admin_email', 10, 2 );

add_action( 'pre_get_posts', 'bst_apply_shop_category_query' );
add_action( 'woocommerce_before_shop_loop', 'bst_render_shop_filter_widget', 8 );
add_action( 'wp_footer', 'bst_render_shop_filter_script', 30 );

if ( ! function_exists( 'bst_apply_shop_category_query' ) ) {
	/**
	 * Support /shop/?cat=category-slug links from custom category cards.
	 */
	function bst_apply_shop_category_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! function_exists( 'is_shop' ) || ! is_shop() ) {
			return;
		}

		if ( empty( $_GET['cat'] ) ) {
			return;
		}

		$category_slug = sanitize_title( wp_unslash( $_GET['cat'] ) );

		if ( ! $category_slug ) {
			return;
		}

		$tax_query   = (array) $query->get( 'tax_query' );
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( $category_slug ),
		);

		$query->set( 'tax_query', $tax_query );
	}
}

if ( ! function_exists( 'bst_render_shop_filter_widget' ) ) {
	/**
	 * Render the free Filter Everything controls on product archive screens.
	 */
	function bst_render_shop_filter_widget() {
		if ( ! shortcode_exists( 'fe_widget' ) || ! function_exists( 'is_shop' ) || ( ! is_shop() && ! is_product_taxonomy() ) ) {
			return;
		}

		$filter_sets = get_posts(
			array(
				'post_type'      => defined( 'FLRT_FILTERS_SET_POST_TYPE' ) ? FLRT_FILTERS_SET_POST_TYPE : 'filter-set',
				'title'          => 'Bin Shihon Product Filters',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		if ( empty( $filter_sets ) ) {
			return;
		}

		echo '<div class="bst-shop-filters is-collapsed" data-bst-shop-filters>';
		echo '<button class="bst-shop-filters__toggle" type="button" aria-expanded="false" data-bst-filter-toggle>';
		echo '<span class="bst-shop-filters__toggle-icon" aria-hidden="true"></span>';
		echo '<span>' . esc_html__( 'Filters', 'binshihon-site-tools' ) . '</span>';
		echo '</button>';
		echo '<div class="bst-shop-filters__panel" data-bst-filter-panel>';
		echo do_shortcode( '[fe_widget id="' . absint( $filter_sets[0] ) . '" show_chips="1"]' );
		echo '</div>';
		echo '</div>';
	}
}

if ( ! function_exists( 'bst_render_shop_filter_script' ) ) {
	/**
	 * Toggle the mobile shop filter panel.
	 */
	function bst_render_shop_filter_script() {
		if ( ! function_exists( 'is_shop' ) || ( ! is_shop() && ! is_product_taxonomy() ) ) {
			return;
		}
		?>
		<script>
			document.addEventListener('click', function(event) {
				var button = event.target.closest('[data-bst-filter-toggle]');

				if (!button) {
					return;
				}

				var wrapper = button.closest('[data-bst-shop-filters]');
				var expanded = button.getAttribute('aria-expanded') === 'true';

				if (!wrapper) {
					return;
				}

				wrapper.classList.toggle('is-collapsed', expanded);
				button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
			});
		</script>
		<?php
	}
}

if ( ! function_exists( 'bst_send_quote_admin_email' ) ) {
	/**
	 * Send a brand-styled quote email to the site admin.
	 */
	function bst_send_quote_admin_email( $order_id, $quote_data ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}

		$admin_email = get_option( 'admin_email' );
		$product     = isset( $quote_data['product'] ) && $quote_data['product'] instanceof WC_Product ? $quote_data['product'] : null;
		$product_url = $product ? get_permalink( $product->get_id() ) : '';

		$rows = array(
			__( 'Quote Number', 'binshihon-site-tools' ) => '#' . $order->get_id(),
			__( 'Product', 'binshihon-site-tools' )      => $product ? $product->get_name() : $order->get_meta( '_quote_product_name' ),
			__( 'Quantity', 'binshihon-site-tools' )     => isset( $quote_data['quantity'] ) ? absint( $quote_data['quantity'] ) : 1,
			__( 'Name', 'binshihon-site-tools' )         => $order->get_billing_first_name(),
			__( 'Email', 'binshihon-site-tools' )        => $order->get_billing_email(),
			__( 'Phone', 'binshihon-site-tools' )        => $order->get_billing_phone(),
			__( 'Address', 'binshihon-site-tools' )      => $order->get_billing_address_1(),
			__( 'Notes', 'binshihon-site-tools' )        => $order->get_customer_note(),
		);

		$message  = '<!doctype html><html><body style="margin:0;background:#f4f1ed;padding:24px;font-family:DG Baysan, Arial, sans-serif;color:#242424;">';
		$message .= '<div style="max-width:680px;margin:auto;background:#ffffff;border-top:6px solid #c41230;">';
		$message .= '<div style="padding:28px 32px;border-bottom:1px solid #ebe6df;">';
		$message .= '<p style="margin:0 0 8px;color:#c41230;font-weight:700;letter-spacing:.04em;text-transform:uppercase;">Bin Shihon</p>';
		$message .= '<h1 style="margin:0;font-size:28px;line-height:1.25;color:#222;">New quote request</h1>';
		$message .= '<p style="margin:10px 0 0;color:#666;">A customer submitted a quote request from the website.</p>';
		$message .= '</div>';
		$message .= '<table style="width:100%;border-collapse:collapse;font-size:15px;">';

		foreach ( $rows as $label => $value ) {
			if ( '' === (string) $value ) {
				continue;
			}

			$message .= '<tr>';
			$message .= '<th style="width:34%;padding:14px 32px;text-align:left;background:#faf8f5;border-bottom:1px solid #ebe6df;color:#222;">' . esc_html( $label ) . '</th>';
			$message .= '<td style="padding:14px 32px;border-bottom:1px solid #ebe6df;color:#333;">' . nl2br( esc_html( $value ) ) . '</td>';
			$message .= '</tr>';
		}

		$message .= '</table>';
		$message .= '<div style="padding:24px 32px;">';

		if ( $product_url ) {
			$message .= '<a href="' . esc_url( $product_url ) . '" style="display:inline-block;background:#c41230;color:#fff;text-decoration:none;padding:12px 18px;font-weight:700;">View product</a> ';
		}

		$message .= '<a href="' . esc_url( admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) ) . '" style="display:inline-block;background:#242424;color:#fff;text-decoration:none;padding:12px 18px;font-weight:700;">Open quote order</a>';
		$message .= '</div></div></body></html>';

		wp_mail(
			$admin_email,
			sprintf( __( 'New quote request #%d', 'binshihon-site-tools' ), $order->get_id() ),
			$message,
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}
}
