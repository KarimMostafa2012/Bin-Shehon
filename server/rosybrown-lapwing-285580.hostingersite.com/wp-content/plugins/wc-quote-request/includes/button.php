<?php

add_action('wp', function () {

    // Remove Add to Cart
    remove_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_add_to_cart',
        30
    );

    add_action(
        'woocommerce_single_product_summary',
        'wcqr_quote_button',
        35
    );

    add_action(
        'woocommerce_after_single_product_summary',
        'wcqr_quote_button',
        15
    );

    add_action(
        'wp_footer',
        'wcqr_quote_button_fallback',
        25
    );
});

function wcqr_quote_button()
{
    echo wcqr_get_quote_button_html();
}

function wcqr_get_quote_button_html()
{

    global $product;

    if (!$product && function_exists('wc_get_product')) {
        $product = wc_get_product(get_queried_object_id());
    }

    $product_id = $product ? $product->get_id() : get_queried_object_id();
    $product_name = $product ? $product->get_name() : get_the_title($product_id);

    if (!$product_id) {
        return '';
    }

    static $rendered = [];

    if (isset($rendered[$product_id])) {
        return '';
    }

    $rendered[$product_id] = true;

    ob_start();
?>
    <div class="wcqr-wrapper">

        <button
            class="wcqr-open button alt"
            data-id="<?php echo esc_attr($product_id); ?>"
            data-name="<?php echo esc_attr($product_name); ?>"
            onclick="if (window.wcqrOpenQuote) { window.wcqrOpenQuote(this, event); }">
            Request a Quote
        </button>

    </div>
<?php

    return ob_get_clean();
}

function wcqr_quote_button_fallback()
{
    if (!function_exists('is_product') || !is_product()) {
        return;
    }

    $button_html = wcqr_get_quote_button_html();

    if (!$button_html) {
        return;
    }
?>
    <template id="wcqr-button-template"><?php echo $button_html; ?></template>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.wcqr-wrapper')) {
                return;
            }

            var template = document.getElementById('wcqr-button-template');
            var target = document.querySelector('.single-product div.product .summary, .product .summary, .product_title');

            if (!template || !target) {
                return;
            }

            var node = template.content.firstElementChild.cloneNode(true);

            if (target.classList.contains('product_title')) {
                target.insertAdjacentElement('afterend', node);
            } else {
                target.appendChild(node);
            }
        });
    </script>
<?php
}
