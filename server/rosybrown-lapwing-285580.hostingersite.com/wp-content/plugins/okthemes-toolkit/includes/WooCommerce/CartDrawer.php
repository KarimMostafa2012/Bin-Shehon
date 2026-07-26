<?php
namespace OKThemes\Toolkit\WooCommerce;
use OKThemes\Toolkit\Functions\Helpers as OKT_Helpers;

if (!defined('ABSPATH')) exit;

class CartDrawer {

    public static function init() {
        add_filter('woocommerce_add_to_cart_fragments', [__CLASS__, 'menu_cart_fragments']);
    }

    public static function render_cart_count() {
        if ( null === WC()->cart ) {
            return;
        }

        if (WC()->cart->get_cart_contents_count() > 0) {
            echo '<span class="count">' . WC()->cart->get_cart_contents_count() . '</span>';
        } else {
            echo '<span class="count"></span>'; //we need this because we're only refreshing the span
        }
    }

    public static function render_toggle_button($customIcon = '') {

        echo '<div class="cart-drawer-wrapper">';
        echo '<a id="cart-drawer-trigger" href="' . esc_url(wc_get_cart_url()) . '" title="' . esc_attr__('View your shopping cart', 'okthemes-toolkit') . '">';
        if (!empty($customIcon)) {
            echo !empty($customIcon) ? wp_kses($customIcon, 'post') : '<i class="fas fa-shopping-cart"></i>';
        } else {
            echo OKT_Helpers::get_theme_icon('header-minicart-v2');
        }

        self::render_cart_count();

        echo '</a></div>';

    }

    public static function render_drawer() {
        if (is_cart() || is_checkout() || null === WC()->cart) return;

        echo '<div id="cartDrawer"><div class="cart-drawer-container">';
        echo '<div class="cart-drawer-header"><h4>' . esc_html__('Shopping cart', 'okthemes-toolkit') . '</h4>';
        echo '<a href="#" id="closeDrawerbtn"><i class="fas fa-times"></i></a></div>';
        echo '<div class="cart-drawer-content">';
        the_widget('WC_Widget_Cart', ['title' => false]);
        echo '</div></div></div><div id="panelOverlay"></div>';
    }

    public static function render($customIcon = '') {
        self::render_toggle_button($customIcon);
        self::render_drawer();
    }

    public static function menu_cart_fragments($fragments) {
        ob_start();
        self::render_cart_count();
        $fragments['body:not(.elementor-editor-active) #cart-drawer-trigger .count'] = ob_get_clean();
        return $fragments;
    }
}
