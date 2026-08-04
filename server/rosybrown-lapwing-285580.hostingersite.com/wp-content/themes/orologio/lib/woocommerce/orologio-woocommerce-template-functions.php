<?php

use OrologioTheme\Classes\Orologio_Helper;

//Get the store theme options
$activate_product_image_sizes = Orologio_Helper::get_option('activate_product_image_sizes', 'enabled');
$store_catalog_mode           = Orologio_Helper::get_option('store_catalog_mode', 'disabled');
$product_loop_columns         = Orologio_Helper::get_option('product_loop_columns', '3');
$product_loop_per_page        = Orologio_Helper::get_option('product_loop_per_page', '9');

//var_dump($product_loop_columns, $store_catalog_mode, $product_loop_per_page);


$store_sale_flash             = Orologio_Helper::get_option('store_sale_flash', 'enabled');
$store_products_price         = Orologio_Helper::get_option('store_products_price', 'enabled');
$store_add_to_cart            = Orologio_Helper::get_option('store_add_to_cart', 'enabled');

$store_products_excerpt       = Orologio_Helper::get_option('store_products_excerpt', 'enabled');
$product_title_first_word_italic = Orologio_Helper::get_option('product_title_first_word_italic', 'enabled');


$product_pdf_factsheet        = Orologio_Helper::get_option('product_pdf_factsheet', 'enabled');
$product_sale_flash           = Orologio_Helper::get_option('product_sale_flash', 'enabled');
$product_products_price       = Orologio_Helper::get_option('product_products_price', 'enabled');
$product_products_excerpt     = Orologio_Helper::get_option('product_products_excerpt', 'enabled');
$product_products_meta        = Orologio_Helper::get_option('product_products_meta', 'enabled');
$product_add_to_cart          = Orologio_Helper::get_option('product_add_to_cart', 'enabled');
$product_related_products     = Orologio_Helper::get_option('product_related_products', 'enabled');
$product_description_tab      = Orologio_Helper::get_option('product_description_tab', 'enabled');

$product_crosssells_products  = Orologio_Helper::get_option('product_crosssells_products', 'enabled');

//Get the Shop filter options
$store_filter = Orologio_Helper::get_option('store_filter', 'disabled');

/**
 * Modify add to cart button to use cart icon
 */
/**
 * Replace add to cart button with just an icon
 */
add_filter('woocommerce_loop_add_to_cart_link', 'orologio_custom_add_to_cart_icon', 10, 3);
function orologio_custom_add_to_cart_icon($html, $product, $args)
{
    // Get default button args
    $defaults = array(
        'quantity'   => 1,
        'class'      => implode(
            ' ',
            array_filter(
                array(
                    'button',
                    'product_type_' . $product->get_type(),
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                )
            )
        ),
        'attributes' => array(
            'data-product_id'  => $product->get_id(),
            'data-product_sku' => $product->get_sku(),
            'aria-label'       => $product->add_to_cart_description(),
            'rel'              => 'nofollow',
        ),
    );

    $args = wp_parse_args($args, $defaults);

    // Build cart icon button without text
    $cart_icon = orologio_get_icons('add-to-cart');

    $html = sprintf(
        '<a href="%s" data-quantity="%s" class="%s cart-icon-button" %s>
            <span class="cart-icon">%s</span>
            <span class="view-cart-text">%s</span>
        </a>',
        esc_url($product->add_to_cart_url()),
        esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
        esc_attr(isset($args['class']) ? $args['class'] : 'button'),
        isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
        $cart_icon,
        esc_html__('View cart', 'orologio')
    );

    return $html;
}


/**
 * Enable/Disable the products filter
 */
if ($store_filter == 'enabled') {
    require get_template_directory() . '/lib/woocommerce/class-orologio-shop-filters.php';
}


/**
 * Set default columns number
 */
if ($product_loop_columns) {
    add_filter('loop_shop_columns', function ($cols) use ($product_loop_columns) {
        return (int)$product_loop_columns;
    });
}

/**
 * Set default products per page
 */
if ($product_loop_per_page) {
    add_filter('loop_shop_per_page', function ($cols) use ($product_loop_per_page) {
        // Handle both array format (when theme options are active) and string format
        if (is_array($product_loop_per_page) && isset($product_loop_per_page['number'])) {
            return (int)$product_loop_per_page['number'];
        } else {
            return (int)$product_loop_per_page;
        }
    }, 20);
}

/**
 * Hide shop page title
 */
add_filter('woocommerce_show_page_title', 'orologio_remove_shop_title');
function orologio_remove_shop_title()
{
    return false;
}

/**
 * Wrap the products loop and add a spinner
 */
add_action('woocommerce_before_shop_loop', 'orologio_products_loop_open', 40);
function orologio_products_loop_open()
{
    echo '<div class="products-wrapper">';
    echo '<div class="spinner"></div>';
}
add_action('woocommerce_after_shop_loop', 'orologio_products_loop_close', 5);
function orologio_products_loop_close()
{
    echo '</div>';
}

/*
 * Add custom pagination
 */
function orologio_wc_pagination()
{
    orologio_pagination();
}
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_after_shop_loop', 'orologio_wc_pagination', 10);


if (! function_exists('orologio_open_product_category_wrap')) {
    /**
     * Product category wrap - open
     */
    function orologio_open_product_category_wrap()
    {
        echo '<div class="product-category-wrap">';
    }
}

if (! function_exists('orologio_close_product_category_wrap')) {
    /**
     * Product category wrap - close
     */
    function orologio_close_product_category_wrap()
    {
        echo '</div>';
    }
}

if (! function_exists('orologio_add_category_description')) {
    /**
     * Add category description
     */
    function orologio_add_category_description($category)
    {
        $cat_id      =    $category->term_id;
        $prod_term   =    get_term($cat_id, 'product_cat');
        $description =    $prod_term->description;

        if ($description) {
            echo '<div class="term-description">' . wc_format_content($description) . '</div>';
        }
    }
}


if (! function_exists('orologio_open_product_image_wrap')) {
    /**
     * Product image wrap - open
     */
    function orologio_open_product_image_wrap()
    {
        echo '<div class="product-image-wrap">';
    }
}

if (! function_exists('orologio_close_product_image_wrap')) {
    /**
     * Product image wrap - close
     */
    function orologio_close_product_image_wrap()
    {
        echo '</div>';
    }
}

if (! function_exists('orologio_open_product_meta_wrap')) {
    /**
     * Product meta wrap - open
     */
    function orologio_open_product_meta_wrap()
    {
        echo '<div class="product-meta-wrap">';
    }
}

if (! function_exists('orologio_close_product_meta_wrap')) {
    /**
     * Product meta wrap - close
     */
    function orologio_close_product_meta_wrap()
    {
        echo '</div>';
    }
}


if (!function_exists('orologio_add_permalink_to_title')) {
    /**
     * Add product permalink to product title and optionally make first word italic
     */
    function orologio_add_permalink_to_title()
    {
        global $product_title_first_word_italic;

        $title = get_the_title();

        // Check if the italic option is enabled
        if ($product_title_first_word_italic == 'enabled') {
            $words = explode(' ', $title, 2); // Split into 2 parts - first word and the rest

            if (count($words) > 1) {
                $formatted_title = '<span class="first-word">' . $words[0] . '</span> ' . $words[1];
            } else {
                $formatted_title = '<span class="first-word">' . $title . '</span>';
            }
        } else {
            $formatted_title = $title;
        }

        echo '<h2 class="woocommerce-loop-product__title"><a href="' . get_the_permalink() . '">' . $formatted_title . '</a></h2>';
    }
}

if (! function_exists('orologio_category_on_title')) {
    /**
     * Add product category on top of the product title
     */
    function orologio_category_on_title()
    {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<span class="product_posted_in">', '</span>');
    }
}

if (! function_exists('orologio_open_product_gallery_div')) {
    /**
     * Product images and summary div - open
     */
    function orologio_open_product_gallery_div()
    {
        echo '<div class="product-gallery-wrap">';
    }
}

if (! function_exists('orologio_close_product_gallery_div')) {
    /**
     * Product images and summary div - close
     */
    function orologio_close_product_gallery_div()
    {
        echo '</div>';
    }
}

if (! function_exists('orologio_open_images_summary_div')) {
    /**
     * Product images and summary div - open
     */
    function orologio_open_images_summary_div()
    {
        echo '<div class="product-images-summary-wrap">';
    }
}

if (! function_exists('orologio_close_images_summary_div')) {
    /**
     * Product images and summary div - close
     */
    function orologio_close_images_summary_div()
    {
        echo '</div>';
    }
}


if (! function_exists('orologio_product_description')) {
    /**
     * Add product description before upsells
     */
    function orologio_product_description()
    {
        echo '<div class="product-description-wrapper">';
        wc_get_template('single-product/tabs/description.php');
        echo '</div>';
    }
}

if (! function_exists('orologio_rename_additional_information_tab')) {
    /**
     * Add product description before upsells
     */
    function orologio_rename_additional_information_tab($tabs)
    {
        if (isset($tabs['additional_information'])) {
            $tabs['additional_information']['title'] = esc_html__('Product Data', 'orologio');
        }
        return $tabs;
    }
}

if (! function_exists('orologio_get_product_acf_fields')) {
    /**
     * Return public ACF fields assigned to the current product.
     */
    function orologio_get_product_acf_fields($product_id)
    {
        if (! function_exists('get_fields')) {
            return array();
        }

        $fields = get_fields($product_id);

        if (! is_array($fields)) {
            return array();
        }

        return array_filter(
            $fields,
            function ($value, $name) {
                return 0 !== strpos((string) $name, '_') && '' !== $value && null !== $value && array() !== $value;
            },
            ARRAY_FILTER_USE_BOTH
        );
    }
}

if (! function_exists('orologio_format_acf_product_value')) {
    /**
     * Format ACF values for product data output.
     */
    function orologio_format_acf_product_value($value)
    {
        if (is_bool($value)) {
            return $value ? esc_html__('Yes', 'orologio') : esc_html__('No', 'orologio');
        }

        if (is_numeric($value)) {
            return esc_html($value);
        }

        if (is_string($value)) {
            return wp_kses_post(wpautop($value));
        }

        if (is_array($value)) {
            if (isset($value['ID']) && wp_attachment_is_image($value['ID'])) {
                return wp_get_attachment_image($value['ID'], 'medium');
            }

            $items = array();
            foreach ($value as $item) {
                if (is_array($item) && isset($item['url'])) {
                    $items[] = sprintf(
                        '<a href="%1$s">%2$s</a>',
                        esc_url($item['url']),
                        esc_html(isset($item['title']) ? $item['title'] : $item['url'])
                    );
                } elseif (is_scalar($item)) {
                    $items[] = esc_html($item);
                }
            }

            return implode(', ', $items);
        }

        return '';
    }
}

if (! function_exists('orologio_get_product_data_rows')) {
    /**
     * Return core WooCommerce product data and attributes for custom Elementor product output.
     */
    function orologio_get_product_data_rows($product_id)
    {
        $product = wc_get_product($product_id);

        if (! $product) {
            return array();
        }

        $rows = array(
            esc_html__('Product', 'orologio') => esc_html($product->get_name()),
        );

        if ($product->get_sku()) {
            $rows[esc_html__('SKU', 'orologio')] = esc_html($product->get_sku());
        }

        if ($product->get_price_html()) {
            $rows[esc_html__('Price', 'orologio')] = wp_kses_post($product->get_price_html());
        }

        foreach ($product->get_attributes() as $attribute) {
            $label = wc_attribute_label($attribute->get_name(), $product);

            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
            } else {
                $values = $attribute->get_options();
            }

            if (empty($values)) {
                continue;
            }

            $rows[$label] = esc_html(wc_implode_text_attributes($values));
        }

        return $rows;
    }
}

if (! function_exists('orologio_get_product_image_ids')) {
    /**
     * Return featured and gallery image IDs for a product.
     */
    function orologio_get_product_image_ids($product_id)
    {
        $product = wc_get_product($product_id);

        if (! $product) {
            return array();
        }

        return array_values(
            array_filter(
                array_unique(
                    array_merge(
                        array($product->get_image_id()),
                        $product->get_gallery_image_ids()
                    )
                )
            )
        );
    }
}

if (! function_exists('orologio_render_elementor_product_images')) {
    /**
     * Render product images for Elementor full-width product pages.
     */
    function orologio_render_elementor_product_images($product_id)
    {
        $image_ids = orologio_get_product_image_ids($product_id);

        if (empty($image_ids)) {
            return;
        }

        echo '<div class="orologio-elementor-product-images">';

        foreach ($image_ids as $image_id) {
            echo '<figure class="orologio-elementor-product-image">';
            echo wp_get_attachment_image($image_id, 'large');
            echo '</figure>';
        }

        echo '</div>';
    }
}

if (! function_exists('orologio_render_acf_product_data')) {
    /**
     * Render WooCommerce product data and ACF fields.
     */
    function orologio_render_acf_product_data($product_id, $show_heading = false)
    {
        $product_rows = orologio_get_product_data_rows($product_id);
        $fields       = orologio_get_product_acf_fields($product_id);

        if (empty($product_rows) && empty($fields)) {
            return;
        }

        if ($show_heading) {
            echo '<section class="orologio-elementor-acf-product-data">';
            echo '<div class="theme-container">';
            orologio_render_elementor_product_images($product_id);
            echo '<h2 class="orologio-acf-product-data-title">' . esc_html__('Product Data', 'orologio') . '</h2>';
        }

        echo '<table class="woocommerce-product-attributes shop_attributes orologio-acf-product-data">';

        foreach ($product_rows as $label => $formatted_value) {
            if ('' === $formatted_value) {
                continue;
            }

            echo '<tr class="woocommerce-product-attributes-item">';
            echo '<th class="woocommerce-product-attributes-item__label">' . esc_html($label) . '</th>';
            echo '<td class="woocommerce-product-attributes-item__value">' . $formatted_value . '</td>';
            echo '</tr>';
        }

        $hidden_acf_fields = array(
            'btv_tire_front_image',
            'btv_tire_back_image',
            'btv_tire_side_image',
        );

        $details_value = '';

        foreach ($fields as $name => $value) {
            if (in_array($name, $hidden_acf_fields, true)) {
                continue;
            }

            $formatted_value = orologio_format_acf_product_value($value);

            if ('' === $formatted_value) {
                continue;
            }

            if ('details' === $name) {
                $details_value = $formatted_value;
                continue;
            }

            echo '<tr class="woocommerce-product-attributes-item">';
            echo '<th class="woocommerce-product-attributes-item__label">' . esc_html(ucwords(str_replace(array('_', '-'), ' ', $name))) . '</th>';
            echo '<td class="woocommerce-product-attributes-item__value">' . $formatted_value . '</td>';
            echo '</tr>';
        }

        echo '</table>';

        if ('' !== $details_value) {
            echo '<div class="orologio-product-details-acf">' . $details_value . '</div>';
        }

        if ($show_heading) {
            echo '</div>';
            echo '</section>';
        }
    }
}

if (! function_exists('orologio_render_acf_product_data_tab')) {
    /**
     * Render ACF product data in a WooCommerce tab.
     */
    function orologio_render_acf_product_data_tab()
    {
        global $product;

        if (! $product) {
            return;
        }

        orologio_render_acf_product_data($product->get_id());
    }
}

if (! function_exists('orologio_add_acf_fields_to_product_attributes')) {
    /**
     * Add ACF product fields to WooCommerce's native Product Data table.
     */
    function orologio_add_acf_fields_to_product_attributes($product_attributes, $product)
    {
        if (! $product) {
            return $product_attributes;
        }

        $fields = orologio_get_product_acf_fields($product->get_id());

        if (empty($fields)) {
            return $product_attributes;
        }

        $hidden_acf_fields = array(
            'btv_tire_front_image',
            'btv_tire_back_image',
            'btv_tire_side_image',
        );

        foreach ($fields as $name => $value) {
            if (in_array($name, $hidden_acf_fields, true)) {
                continue;
            }

            if ('details' === $name) {
                continue;
            }

            $formatted_value = orologio_format_acf_product_value($value);

            if ('' === $formatted_value) {
                continue;
            }

            $product_attributes['orologio_acf_' . sanitize_key($name)] = array(
                'label' => ucwords(str_replace(array('_', '-'), ' ', $name)),
                'value' => $formatted_value,
            );
        }

        return $product_attributes;
    }
}
add_filter('woocommerce_display_product_attributes', 'orologio_add_acf_fields_to_product_attributes', 20, 2);

if (! function_exists('orologio_add_acf_product_data_tab')) {
    /**
     * Add product ACF data when WooCommerce has no attributes tab to display.
     */
    function orologio_add_acf_product_data_tab($tabs)
    {
        global $product;

        if (! $product || isset($tabs['additional_information'])) {
            return $tabs;
        }

        if (empty(orologio_get_product_acf_fields($product->get_id()))) {
            return $tabs;
        }

        $tabs['orologio_acf_product_data'] = array(
            'title'    => esc_html__('Product Data', 'orologio'),
            'priority' => 20,
            'callback' => 'orologio_render_acf_product_data_tab',
        );

        return $tabs;
    }
}
add_filter('woocommerce_product_tabs', 'orologio_add_acf_product_data_tab', 99);

if (! function_exists('orologio_render_acf_product_data_after_elementor_content')) {
    /**
     * Elementor full-width products bypass WooCommerce tabs, so print ACF product data after the Elementor body.
     */
    function orologio_render_acf_product_data_after_elementor_content()
    {
        if (! is_singular('product')) {
            return;
        }

        $product_id = get_queried_object_id();

        if (! $product_id || empty(orologio_get_product_acf_fields($product_id))) {
            return;
        }

        orologio_render_acf_product_data($product_id, true);
    }
}
add_action('elementor/page_templates/header-footer/after_content', 'orologio_render_acf_product_data_after_elementor_content', 20);

if (! function_exists('orologio_remove_description_tab')) {
    /**
     * Remove Description tab 
     */
    function orologio_remove_description_tab($tabs)
    {
        unset($tabs['description']);
        return $tabs;
    }
}


if (! function_exists('orologio_wrap_qty_add_to_cart_open')) {
    /**
     * Product QTY and add to cart div - open
     */
    function orologio_wrap_qty_add_to_cart_open()
    {
        echo '<div class="wrap-qty-add-to-cart">';
    }
}

if (! function_exists('orologio_wrap_qty_add_to_cart_close')) {
    /**
     * Product QTY and add to cart div - open
     */
    function orologio_wrap_qty_add_to_cart_close()
    {
        echo '</div>';
    }
}

if (! function_exists('orologio_open_my_account_wrapper')) {
    /**
     * Wrap my account page - open div
     */
    function orologio_open_my_account_wrapper()
    {
        echo '<div class="woocommerce-MyAccount-content-wrapper">';
    }
}

if (! function_exists('orologio_close_my_account_wrapper')) {
    /**
     * Wrap my account page - close div
     */
    function orologio_close_my_account_wrapper()
    {
        echo '</div>';
    }
}

if (! function_exists('orologio_wrap_checkout_order_review_open')) {
    /**
     * Wrap my account page - open div
     */
    function orologio_wrap_checkout_order_review_open()
    {
        echo '<div class="woocommerce-order-details-wrapper">';
    }
}

if (! function_exists('orologio_wrap_checkout_order_review_close')) {
    /**
     * Wrap my account page - close div
     */
    function orologio_wrap_checkout_order_review_close()
    {
        echo '</div>';
    }
}

if (!function_exists('orologio_template_loop_excerpt')) {
    /**
     * Display product excerpt in loop
     */
    function orologio_template_loop_excerpt()
    {
        global $product;

        // Get product short description
        $short_description = apply_filters('woocommerce_short_description', $product->get_short_description());

        if ($short_description) {
            echo '<div class="woocommerce-product-excerpt">' . $short_description . '</div>';
        }
    }
}

/**
 * Hide category product count in product archives
 */
add_filter('woocommerce_subcategory_count_html', '__return_false');


/* Store theme options */


/* Shop page */

/**
 * Enable/Disable Sale Flash
 */
if ($store_sale_flash == 'disabled') {
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
}

/**
 * Enable/Disable Products excerpt in loop
 */
if ($store_products_excerpt == 'enabled') {
    add_action('woocommerce_shop_loop_item_title', 'orologio_template_loop_excerpt', 15);
}

/**
 * Enable/Disable Products price
 */
if ($store_products_price == 'disabled') {
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
}

/**
 * Enable/Disable Add to cart
 */
if ($store_add_to_cart == 'disabled') {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

/**
 * Options for product page
 */

/**
 * Enable/Disable Product PDF factsheet
 */
if ($product_pdf_factsheet == 'disabled') {
    remove_action('woocommerce_single_product_summary', 'orologio_product_factsheet_link', 75);
}

/*Sale flash*/
if ($product_sale_flash == 'disabled') {
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
}

/*Price*/
if ($product_products_price == 'disabled') {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
}

/*Product summary*/
if ($product_products_excerpt == 'disabled') {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
}

/*Add to cart*/
if ($product_add_to_cart == 'disabled') {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
}

/*Meta*/
if ($product_products_meta == 'disabled') {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 80);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 80);
}

/**
 * Enable/Disable Related products
 */
if ($product_related_products == 'disabled') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

/**
 * Enable/Disable Up Sells products
 */
//if ( $product_upsells_products == 'disabled' ) {
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
//}


/**
 * Enable/Disable product data tabs
 */
add_filter('woocommerce_product_tabs', 'orologio_remove_product_tabs', 98);
function orologio_remove_product_tabs($tabs)
{

    $product_reviews_tab          = Orologio_Helper::get_option('product_reviews_tab', 'enabled');
    $product_attributes_tab       = Orologio_Helper::get_option('product_attributes_tab', 'enabled');
    $product_awards               = Orologio_Helper::get_option('product_awards', 'enabled');

    if ($product_reviews_tab == 'disabled') {
        unset($tabs['reviews']);
    }
    if ($product_attributes_tab == 'disabled') {
        unset($tabs['additional_information']);
    }
    if ($product_awards == 'disabled') {
        unset($tabs['awards']);
    }

    return $tabs;
}

/**
 * Enable/Disable produc main description
 */
if ($product_description_tab == 'disabled') {
    remove_action('woocommerce_after_single_product_summary', 'orologio_product_description', 12);
}

/**
 * Enable/Disable Cross Sells products
 */
if ($product_crosssells_products == 'disabled') {
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
}

/**
 * Catalog mode functions (must be always the last function)
 */

if ($store_catalog_mode == 'enabled') {
    // Remove add to cart button from the product loop
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

    // Remove add to cart button from the product details page
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

    add_filter('woocommerce_add_to_cart_validation', '__return_false', 10, 2);

    // check for clear-cart get param to clear the cart
    add_action('init', 'orologio_wc_clear_cart_url');
    function orologio_wc_clear_cart_url()
    {
        if (isset($_GET['clear-cart'])) {
            WC()->cart->empty_cart();
        }
    }

    add_action('wp', 'orologio_check_pages_redirect');
    function orologio_check_pages_redirect()
    {
        $cart     = is_page(wc_get_page_id('cart'));
        $checkout = is_page(wc_get_page_id('checkout'));

        if ($cart || $checkout) {
            wp_redirect(esc_url(home_url('/')));
            exit;
        }
    }
}
