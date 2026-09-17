<?php
/**
 * Plugin Name: Bin Shihon Site Tools
 * Description: Site-specific Elementor widgets, brand styling, and quote request email notifications.
 * Version: 1.1.0
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
			'1.1.0'
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
add_shortcode( 'binshihon_site_page', 'bst_render_site_content_page_shortcode' );
add_shortcode( 'bst_site_page', 'bst_render_site_content_page_shortcode' );

if ( ! function_exists( 'bst_get_site_content_pages' ) ) {
	/**
	 * Load bilingual page content generated from the approved website content document.
	 */
	function bst_get_site_content_pages() {
		static $pages = null;

		if ( null === $pages ) {
			$pages = require BST_PATH . 'includes/site-content-data.php';
		}

		return is_array( $pages ) ? $pages : array();
	}
}

if ( ! function_exists( 'bst_get_site_content_language' ) ) {
	/**
	 * Detect TranslatePress language, falling back to URL prefix and locale.
	 */
	function bst_get_site_content_language() {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( preg_match( '#/(en|en-us)(/|\\?|$)#i', $request_uri ) || false !== stripos( $request_uri, 'lang=en' ) ) {
			return 'en';
		}

		$locale = determine_locale();

		if ( 0 === stripos( $locale, 'en' ) ) {
			return 'en';
		}

		return 'ar';
	}
}

if ( ! function_exists( 'bst_render_site_content_page_shortcode' ) ) {
	/**
	 * Shortcode renderer for generated Bin Shihon content pages.
	 */
	function bst_render_site_content_page_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'page' => 'home',
			),
			$atts,
			'binshihon_site_page'
		);

		$page_key = sanitize_key( $atts['page'] );
		$pages    = bst_get_site_content_pages();

		if ( empty( $pages[ $page_key ] ) ) {
			return '';
		}

		$lang      = bst_get_site_content_language();
		$lang_data = ! empty( $pages[ $page_key ][ $lang ] ) ? $pages[ $page_key ][ $lang ] : $pages[ $page_key ]['ar'];
		$is_rtl    = 'ar' === $lang;
		$blocks    = isset( $lang_data['blocks'] ) && is_array( $lang_data['blocks'] ) ? array_values( $lang_data['blocks'] ) : array();
		$title     = isset( $lang_data['title'] ) ? $lang_data['title'] : '';
		$intro     = '';

		$blocks = bst_site_content_strip_navigation_blocks( $blocks );

		if ( 'home' === $page_key ) {
			$home_hero = bst_site_content_take_home_hero( $blocks, $title );
			$title     = $home_hero['title'];
			$intro     = $home_hero['intro'];
			$blocks    = bst_site_content_strip_section_from_label( $home_hero['blocks'], array( 'الفوتر', 'Footer' ) );
		} elseif ( ! empty( $blocks ) && ! bst_site_content_is_structural_heading( $blocks[0] ) ) {
			$intro = array_shift( $blocks );
		}

		ob_start();
		?>
		<div class="bst-content-page bst-content-page--<?php echo esc_attr( $page_key ); ?>" lang="<?php echo esc_attr( $lang ); ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">
			<section class="bst-content-hero">
				<div class="bst-content-container">
					<p class="bst-content-kicker"><?php echo esc_html( 'en' === $lang ? 'Bin Shihon Tires' : 'شركة بن شيهون للإطارات' ); ?></p>
					<h1><?php echo esc_html( $title ); ?></h1>
					<?php if ( $intro ) : ?>
						<p class="bst-content-lead"><?php echo esc_html( $intro ); ?></p>
					<?php endif; ?>
				</div>
			</section>
			<section class="bst-content-body">
				<div class="bst-content-container">
					<?php echo bst_site_content_render_blocks( $blocks ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'bst_site_content_strip_section_from_label' ) ) {
	/**
	 * Remove a trailing document section from rendered page content.
	 */
	function bst_site_content_strip_section_from_label( $blocks, $labels ) {
		foreach ( $blocks as $index => $block ) {
			if ( in_array( $block, $labels, true ) ) {
				return array_slice( $blocks, 0, $index );
			}
		}

		return $blocks;
	}
}

if ( ! function_exists( 'bst_site_content_strip_navigation_blocks' ) ) {
	/**
	 * Remove document notes that describe the website navigation itself.
	 */
	function bst_site_content_strip_navigation_blocks( $blocks ) {
		$skip_labels = array(
			'شريط التنقل',
			'Navigation',
		);

		$result = array();
		$count  = count( $blocks );

		for ( $index = 0; $index < $count; $index++ ) {
			if ( in_array( $blocks[ $index ], $skip_labels, true ) ) {
				$index++;
				continue;
			}

			$result[] = $blocks[ $index ];
		}

		return $result;
	}
}

if ( ! function_exists( 'bst_site_content_take_home_hero' ) ) {
	/**
	 * The home section includes document labels before the real hero copy.
	 */
	function bst_site_content_take_home_hero( $blocks, $fallback_title ) {
		$hero_labels = array( 'الواجهة الرئيسية', 'Hero', 'اطلب عرض سعر', 'Request a Quote' );

		while ( ! empty( $blocks ) && in_array( $blocks[0], $hero_labels, true ) ) {
			array_shift( $blocks );
		}

		$title = ! empty( $blocks ) ? array_shift( $blocks ) : $fallback_title;
		$lead  = ! empty( $blocks ) ? array_shift( $blocks ) : '';
		$text  = ! empty( $blocks ) ? array_shift( $blocks ) : '';
		$intro = trim( $lead . ( $text ? ' ' . $text : '' ) );

		return array(
			'title'  => $title,
			'intro'  => $intro,
			'blocks' => $blocks,
		);
	}
}

if ( ! function_exists( 'bst_site_content_render_blocks' ) ) {
	/**
	 * Render structured content blocks.
	 */
	function bst_site_content_render_blocks( $blocks ) {
		$output = '';
		$list   = array();
		$count  = count( $blocks );

		for ( $index = 0; $index < $count; $index++ ) {
			$block = trim( (string) $blocks[ $index ] );

			if ( '' === $block ) {
				continue;
			}

			if ( bst_site_content_is_list_item( $block ) ) {
				$list[] = preg_replace( '/^[▪○]\\s*/u', '', $block );
				continue;
			}

			if ( ! empty( $list ) ) {
				$output .= bst_site_content_render_list( $list );
				$list    = array();
			}

			if ( bst_site_content_is_table( $block ) ) {
				$output .= bst_site_content_render_table( $block );
				continue;
			}

			if ( bst_site_content_is_cta( $block ) ) {
				$output .= '<div class="bst-content-actions">' . bst_site_content_render_actions( $block ) . '</div>';
				continue;
			}

			if ( bst_site_content_is_heading( $block, isset( $blocks[ $index + 1 ] ) ? $blocks[ $index + 1 ] : '' ) ) {
				$output .= '<h2>' . esc_html( $block ) . '</h2>';
				continue;
			}

			$output .= '<p>' . esc_html( $block ) . '</p>';
		}

		if ( ! empty( $list ) ) {
			$output .= bst_site_content_render_list( $list );
		}

		return $output;
	}
}

if ( ! function_exists( 'bst_site_content_is_list_item' ) ) {
	function bst_site_content_is_list_item( $block ) {
		return (bool) preg_match( '/^[▪○]\\s*/u', $block );
	}
}

if ( ! function_exists( 'bst_site_content_is_table' ) ) {
	function bst_site_content_is_table( $block ) {
		return false !== strpos( $block, "\n" ) && false !== strpos( $block, '|' );
	}
}

if ( ! function_exists( 'bst_site_content_is_cta' ) ) {
	function bst_site_content_is_cta( $block ) {
		return false !== strpos( $block, ' · ' ) && mb_strlen( $block ) < 120;
	}
}

if ( ! function_exists( 'bst_site_content_is_structural_heading' ) ) {
	function bst_site_content_is_structural_heading( $block ) {
		return mb_strlen( $block ) < 48 && ! preg_match( '/[.؟?،,]/u', $block );
	}
}

if ( ! function_exists( 'bst_site_content_is_heading' ) ) {
	function bst_site_content_is_heading( $block, $next_block = '' ) {
		if ( bst_site_content_is_list_item( $block ) || bst_site_content_is_cta( $block ) || bst_site_content_is_table( $block ) ) {
			return false;
		}

		if ( mb_strlen( $block ) > 72 || preg_match( '/[.؟?،,]$/u', $block ) ) {
			return false;
		}

		return '' !== $next_block;
	}
}

if ( ! function_exists( 'bst_site_content_render_list' ) ) {
	function bst_site_content_render_list( $items ) {
		$output = '<ul class="bst-content-list">';

		foreach ( $items as $item ) {
			$output .= '<li>' . esc_html( trim( $item ) ) . '</li>';
		}

		return $output . '</ul>';
	}
}

if ( ! function_exists( 'bst_site_content_render_table' ) ) {
	function bst_site_content_render_table( $block ) {
		$rows   = preg_split( '/\\R/', $block );
		$output = '<div class="bst-content-table-wrap"><table class="bst-content-table">';

		foreach ( $rows as $row_index => $row ) {
			$cells = array_map( 'trim', explode( '|', $row ) );
			$output .= '<tr>';

			foreach ( $cells as $cell ) {
				$tag     = 0 === $row_index ? 'th' : 'td';
				$output .= '<' . $tag . '>' . esc_html( $cell ) . '</' . $tag . '>';
			}

			$output .= '</tr>';
		}

		return $output . '</table></div>';
	}
}

if ( ! function_exists( 'bst_site_content_render_actions' ) ) {
	function bst_site_content_render_actions( $block ) {
		$actions = array_map( 'trim', explode( '·', $block ) );
		$output  = '';

		foreach ( $actions as $index => $action ) {
			$class   = 0 === $index ? 'bst-content-button' : 'bst-content-button bst-content-button--secondary';
			$output .= '<span class="' . esc_attr( $class ) . '">' . esc_html( $action ) . '</span>';
		}

		return $output;
	}
}

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
