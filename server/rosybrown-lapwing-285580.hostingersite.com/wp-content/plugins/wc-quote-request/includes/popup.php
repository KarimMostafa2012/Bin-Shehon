<?php


function wcqr_get_popup_markup() {

ob_start();
?>

<div id="wcqr-overlay" class="wcqr-overlay" aria-hidden="true">
    <div id="wcqr-modal" class="wcqr-modal" role="dialog" aria-modal="true" aria-labelledby="wcqr-title">
        <button id="wcqr-close" class="wcqr-close" type="button" aria-label="Close">&times;</button>

        <h2 id="wcqr-title">Request a Quote</h2>

        <form id="wcqr-form">

            <input type="hidden" id="wcqr-product-id" name="product_id">

            <label>Product</label>

            <input
                type="text"
                id="wcqr-product-name"
                readonly
            >

            <label>Quantity</label>

            <input
                type="number"
                name="quantity"
                value="1"
                min="1"
            >

            <label>Name</label>

            <input
                type="text"
                name="name"
                required
            >

            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >

            <label>Phone</label>

            <input
                type="text"
                name="phone"
                required
            >

            <label>Address</label>

            <textarea
                name="address"
                required
            ></textarea>

            <label>Notes</label>

            <textarea
                name="notes"
            ></textarea>

            <button type="submit">
                Submit Quote
            </button>

        </form>

        <div id="wcqr-success" class="wcqr-success" hidden>
            <svg width="70" height="70" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#28a745"
                    d="M20.285 2.859l1.415 1.414L9 17.973l-6.7-6.7
                    1.414-1.414L9 15.145z"/>
            </svg>

            <h3>Quote Submitted!</h3>

            <p>Thank you for contacting us.</p>

            <strong id="wcqr-success-order"></strong>

            <button type="button" id="wcqr-close-success">
                Close
            </button>
        </div>

    </div>

</div>

<?php
return ob_get_clean();
}

function wcqr_get_popup_once() {
    static $rendered = false;

    if ($rendered) {
        return '';
    }

    $rendered = true;

    return wcqr_get_popup_markup();
}

add_shortcode('quote_request_popup', function () {
    return wcqr_get_popup_once();
});

add_action('wp_footer', function () {
    if (function_exists('is_product') && is_product()) {
        echo wcqr_get_popup_once();
    }
}, 20);
